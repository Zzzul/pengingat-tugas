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
        'matkul'        => 'required|numeric',
        'deskripsi'     => 'required|string|min:3',
        'pertemuan_ke'  => 'required|in:1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18',
        //Y-m-d\TH:i = format datetime-local
        'batas_waktu'   => 'required|date_format:Y-m-d\TH:i',
        'selesai'       => 'nullable|date_format:Y-m-d\TH:i|before:batas_waktu',
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
            $all_tugas = ModelsTugas::where('user_id', auth()->id())
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
            ->where('tugas.user_id', auth()->id())
            ->where('matkuls.user_id', auth()->id())
            ->where('semesters.user_id', auth()->id())
            ->where('semesters.aktif_smt', '!=', null)
            ->get();

        // dd($tugas_yg_ga_selesai);

        return view('livewire.tugas', compact('all_tugas', 'tugas_yg_ga_selesai'));
    }

    public function showForm($type)
    {
        $this->form = $type;
        $this->noValidate();
        $this->emptyItems();
        $this->milik_user = '';

        if ($type == 'add') {
            $this->matkuls = Matkul::where('user_id', auth()->id())->get();
        }
    }

    public function hideForm()
    {
        $this->form = '';
        $this->dispatchBrowserEvent('close-modal');
        $this->emptyItems();
        $this->noValidate();
        $this->milik_user = '';
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

        // jika sudah ada tugas dengan matkul dan pertemuan yang sama
        $check = $this->checkDuplicateTugasDanPertemuan();

        if ($check) {
            $this->pertemuan_ke = '';
            $this->matkul = '';

            $this->validate(
                ['matkul' => 'required'],
                ['required' => 'Tugas ' . $check['matkul']->name .  ' pertmuan ke ' . $check->pertemuan_ke  . ' sudah ada.']
            );
        }

        $tugas = new ModelsTugas;
        $tugas->matkul_id      = $this->matkul;
        $tugas->user_id        = auth()->id();
        $tugas->deskripsi      = $this->deskripsi;
        $tugas->batas_waktu    = $this->batas_waktu;
        $tugas->pertemuan_ke   = $this->pertemuan_ke;
        $tugas->save();

        $this->hideForm();

        $this->showAlert('success', 'Tugas berhasil ditambahkan.');
    }

    public function show($id)
    {
        $this->noValidate();
        $this->id_tugas = $id;
        $tugas = ModelsTugas::findOrfail($id);

        if (auth()->user()->hasRole('admin') || $tugas->user_id == auth()->id()) {

            if ($tugas->user_id != auth()->id()) {
                // jika admin yg edit
                $this->milik_user = User::find($tugas->user_id);

                // matkul sesuai user_id tugas
                $this->matkuls = Matkul::where('user_id', $tugas->user_id)->get();
            } else {
                // user biasa yg edit
                // matkul sesuai user yg login
                $this->matkuls = Matkul::where('user_id', auth()->id())->get();
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

        $check = $this->checkDuplicateTugasDanPertemuan();
        $tugas = ModelsTugas::find($id);

        // jika tugas sudah ada tapi bukan tugas yang sama
        if ($check && $tugas->id != $check->id) {
            $this->pertemuan_ke = '';
            $this->matkul = '';

            $this->validate(
                ['matkul' => 'required'],
                ['required' => $this->milik_user ?
                    $this->milik_user->name . ' sudah memiliki tugas ' . $check['matkul']->name .  ' pertmuan ke ' . $check->pertemuan_ke :

                    'Tugas ' . $check['matkul']->name .  ' pertmuan ke ' . $check->pertemuan_ke  . ' sudah ada.']
            );
        }

        // cek jika tugas milik user yang sedang login
        if (auth()->user()->hasRole('admin') || $tugas->user_id == auth()->id()) {

            // jika tgl selesai tidak lebih besar dari batas waktu (deadline belum selesai)
            $tugas->matkul_id      = $this->matkul;
            $tugas->deskripsi      = $this->deskripsi;
            $tugas->batas_waktu    = $this->batas_waktu;
            $tugas->selesai        = $this->selesai ? $this->selesai : null;
            $tugas->pertemuan_ke   = $this->pertemuan_ke;
            $tugas->save();

            $this->hideForm();

            $this->showAlert('success', 'Tugas berhasil diubah.');
        } else {
            // jika user biasa ingin ubah tugas user lain
            $this->showAlert('error', 'Tugas tidak dapat diubah.');
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

        if (auth()->user()->hasRole('admin') || $tugas->user_id == auth()->id()) {
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

    // jika matkul dan pertemuan ke sama
    public function checkDuplicateTugasDanPertemuan()
    {
        /**
         * jika admin ingin ubah tugas user lain
         * cek apakah user tersebut sudah punya tugas dan pertemuan yang sama
         */
        if ($this->milik_user) {
            $tugas = ModelsTugas::with('matkul')->where([
                'matkul_id' => $this->matkul,
                'pertemuan_ke' => $this->pertemuan_ke,
                // user id
                'user_id' => $this->milik_user->id
            ])->latest()->first();
        } else {
            $tugas = ModelsTugas::with('matkul')->where([
                'matkul_id' => $this->matkul,
                'pertemuan_ke' => $this->pertemuan_ke,
                'user_id' => auth()->id()
            ])->latest()->first();
        }

        if ($tugas) {
            return $tugas;
        } else {
            return false;
        }
    }
}
