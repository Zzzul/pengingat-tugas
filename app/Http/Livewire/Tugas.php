<?php

namespace App\Http\Livewire;

use App\Models\Matkul;
use App\Models\Tugas as ModelsTugas;
use App\Models\User;
use App\Traits\LivewireAlert;
use DateTime;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class Tugas extends Component
{
    use WithPagination;
    use LivewireAlert;

    public $form, $id_tugas, $deskripsi, $batas_waktu, $tugas, $selesai, $pertemuan_ke, $matkul = '', $matkuls, $milik_user;

    public $paginate_per_page = 5;

    public $search = '';
    public $page = 1;

    protected $paginationTheme = 'bootstrap';

    protected $rules = [
        'matkul'        => 'required',
        'deskripsi'     => 'required',
        'batas_waktu'   => 'required',
        'pertemuan_ke'  => 'required',
    ];

    protected $queryString = [
        'search' => ['except' => ''],
        'page' => ['except' => 1],
    ];

    protected $listeners = [
        'confirmed',
        'cancelled',
    ];

    public function mount()
    {
        $this->fill(request()->only('search', 'page'));
    }

    public function render()
    {
        if (auth()->user()->hasRole('admin')) {
            // jika yg login admin
            $all_tugas = ModelsTugas::where('deskripsi', 'like', '%' . $this->search . '%')
                ->orWhere('batas_waktu', 'like', '%' . $this->search . '%')
                ->orWhere('selesai', 'like', '%' . $this->search . '%')
                ->orWhere('pertemuan_ke', 'like', '%' . $this->search . '%')
                ->orWhere('created_at', 'like', '%' . $this->search . '%')
                ->orWhere('updated_at', 'like', '%' . $this->search . '%')
                ->orWhereHas('matkul', function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%');
                })
                ->orWhereHas('user', function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('username', 'like', '%' . $this->search . '%');
                })
                ->orderBy('selesai', 'asc')
                ->paginate($this->paginate_per_page);
        } else {
            // jika yg login user biasa
            $all_tugas = ModelsTugas::where('user_id', auth()->user()->id)
                ->where(function ($q) {
                    $q->where('deskripsi', 'like', '%' . $this->search . '%')
                        ->orWhere('batas_waktu', 'like', '%' . $this->search . '%')
                        ->orWhere('selesai', 'like', '%' . $this->search . '%')
                        ->orWhere('pertemuan_ke', 'like', '%' . $this->search . '%')
                        ->orWhere('created_at', 'like', '%' . $this->search . '%')
                        ->orWhere('updated_at', 'like', '%' . $this->search . '%')
                        ->orWhereHas('matkul', function ($q) {
                            $q->where('name', 'like', '%' . $this->search . '%');
                        });
                })
                ->orderBy('selesai', 'asc')
                ->paginate($this->paginate_per_page);
        }

        $tugas_yg_ga_selesai = DB::table('tugas')
            ->join('matkuls', 'matkuls.id', '=', 'tugas.matkul_id')
            ->join('semesters', 'semesters.id', '=', 'matkuls.semester_id')
            ->select('*')
            ->where('tugas.selesai', null)
            ->where('tugas.user_id', auth()->user()->id)
            ->where('matkuls.user_id', auth()->user()->id)
            ->where('semesters.user_id', auth()->user()->id)
            ->where('semesters.aktif_smt', '!=', null)
            ->get();

        // dd($tugas_yg_ga_selesai);

        return view('livewire.tugas', compact('all_tugas', 'tugas_yg_ga_selesai'));
    }

    public function showForm($type)
    {
        $this->form = $type;

        if ($type == 'add') {
            $this->matkuls = Matkul::where('user_id', auth()->user()->id)->get();
            $this->emptyItems();
        }
    }

    public function hideForm()
    {
        $this->form = '';
        $this->emptyItems();
        $this->noValidate();
    }

    public function emptyItems()
    {
        $this->matkul = '';
        $this->deskripsi = '';
        $this->batas_waktu = '';
        $this->selesai = '';
        $this->pertemuan_ke = '';
    }

    public function store()
    {
        $this->validate();

        $tugas = $this->checkTugas($this->matkul, $this->pertemuan_ke);

        if ($tugas) {
            $this->showAlert('error', 'Tugas ' . $tugas['matkul']->name .  ' pertmuan ' . $tugas->pertemuan_ke  . ' sudah ada.');
            $this->pertemuan_ke = '';
        } else {
            $new_tugas = new ModelsTugas;
            $new_tugas->matkul_id      = $this->matkul;
            $new_tugas->user_id        = auth()->user()->id;
            $new_tugas->deskripsi      = $this->deskripsi;
            $new_tugas->batas_waktu    = $this->batas_waktu;
            $new_tugas->pertemuan_ke   = $this->pertemuan_ke;
            $new_tugas->save();

            $this->hideForm();

            $this->showAlert('success', 'Tugas berhasil ditambahkan.');
        }
    }

    public function show($id)
    {
        $this->noValidate();
        $this->id_tugas = $id;
        $tugas = ModelsTugas::findOrfail($id);

        if (auth()->user()->hasRole('admin') || $tugas->user_id == auth()->user()->id) {

            if ($tugas->user_id != auth()->user()->id) {
                // jika admin yg edit
                $this->milik_user = User::find($tugas->user_id);

                // matkul sesuai user_id tugas
                $this->matkuls = Matkul::where('user_id', $tugas->user_id)->get();
            } else {
                // user biasa yg edit
                // matkul sesuai user yg login
                $this->matkuls = Matkul::where('user_id', auth()->user()->id)->get();
                $this->milik_user = [];
            }

            $this->matkul       = $tugas->matkul_id;
            $this->deskripsi    = $tugas->deskripsi;
            $this->batas_waktu  = date('Y-m-d\TH:i', strtotime($tugas->batas_waktu));
            $this->selesai      = !$tugas->selesai ? $tugas->selesai : date('Y-m-d\TH:i', strtotime($tugas->selesai));
            $this->pertemuan_ke = $tugas->pertemuan_ke;
            $this->form         = 'edit';
        } else {
            $this->showAlert('error', 'Tugas tidak dapat diubah.');
        }
    }

    public function update($id)
    {
        $this->validate();

        $check_tugas = $this->checkTugas($this->matkul, $this->pertemuan_ke);
        $tugas = ModelsTugas::find($id);

        // jika tugas sudah ada tapi bukan tugas yang sama
        if ($check_tugas && $tugas->id != $check_tugas->id) {
            $this->showAlert('error', 'Tugas ' . $check_tugas['matkul']->name .  ' pertmuan ' . $check_tugas->pertemuan_ke  . ' sudah ada.');
        } else {
            $tugas = ModelsTugas::find($id);

            $batasWaktuCount = date('YmdHi', strtotime($this->batas_waktu));

            $tglSelesaiCount =  date('YmdHi', strtotime($this->selesai));

            // cek jika tugas milik user yang sedang login
            if (auth()->user()->hasRole('admin') || $tugas->user_id == auth()->user()->id) {

                // jika batas waktu telah habis dan tgl selesai lebih besar dari tgl batas waktu
                if ($tglSelesaiCount > $batasWaktuCount) {

                    $this->selesai = null;

                    $this->validate(
                        ['selesai' => 'required'],
                        ['required' => 'Tanggal selesai tidak boleh lebih besar dari batas waktu!']
                    );
                } else {
                    // jika tgl selesai tidak lebih besar dari batas waktu (deadline belum selesai)
                    $tugas->matkul_id      = $this->matkul;
                    $tugas->deskripsi      = $this->deskripsi;
                    $tugas->batas_waktu    = $this->batas_waktu;
                    $tugas->selesai        = $this->selesai;
                    $tugas->pertemuan_ke   = $this->pertemuan_ke;
                    $tugas->save();

                    $this->hideForm();

                    $this->showAlert('success', 'Tugas berhasil diubah.');
                }
            } else {
                // jika user biasa ingin ubah tugas user lain
                $this->showAlert('error', 'Tugas tidak dapat diubah.');
            }
        }
    }

    public function noValidate()
    {
        $this->validate([
            'matkul'        => '',
            'deskripsi'     => '',
            'batas_waktu'   => '',
            'selesai'       => ''
        ]);
    }

    public function confirmed()
    {
        $tugas = ModelsTugas::findOrfail($this->id_tugas);

        if (auth()->user()->hasRole('admin') || $tugas->user_id == auth()->user()->id) {
            $tugas->destroy($this->id_tugas);
            $this->showAlert('success', 'Tugas berhasil dihapus.');
        } else {
            $this->showAlert('error', 'Tugas tidak dapat dihapus.');
        }

        $this->id_tugas = '';
        $this->hideForm();
    }

    public function cancelled()
    {
        $this->id_tugas = '';
        $this->alert('error', 'Dibatalkan', [
            'position'          =>  'top',
            'timer'             =>  1500,
            'toast'             =>  true,
            'showCancelButton'  =>  false,
            'showConfirmButton' =>  false
        ]);
    }

    public function triggerConfirm($id)
    {
        $this->id_tugas = $id;
        $this->confirm('Yakin ingin menghapus data ini?', [
            'toast' => false,
            'position' => 'center',
            'confirmButtonText' =>  'Ya',
            'cancelButtonText' =>  'Batal',
            'onConfirmed' => 'confirmed',
            'onCancelled' => 'cancelled'
        ]);
    }

    public function checkTugas($matkul_id, $pertemuan_ke)
    {
        // jika matkul dan pertemuan ke sama
        $tugas = ModelsTugas::with('matkul')->where('matkul_id', $matkul_id)
            ->where('pertemuan_ke', $pertemuan_ke)->latest()->first();

        if ($tugas) {
            return $tugas;
        } else {
            return false;
        }
    }
}
