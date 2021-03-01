<?php

namespace App\Http\Livewire;

use App\Models\Matkul as ModelsMatkul;
use App\Models\Semester;
use App\Traits\LivewireAlert;
use Exception;
use Livewire\Component;
use Livewire\WithPagination;

class Matkul extends Component
{
    use WithPagination;
    use LivewireAlert;

    public $form, $id_matkul, $name, $sks, $semesters, $semester_id = '';
    public $paginate_per_page = 5;
    protected $paginationTheme = 'bootstrap';

    public $search = '';
    public $page = 1;

    protected $rules = [
        'name' => 'required',
        'sks' => 'required',
        'semester_id' => 'required',
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
            $matkuls = ModelsMatkul::where('name', 'like', '%' . $this->search . '%')
                ->orWhere('sks', 'like', '%' . $this->search . '%')
                ->orWhere('created_at', 'like', '%' . $this->search . '%')
                ->orWhere('updated_at', 'like', '%' . $this->search . '%')
                ->orWhereHas('semester', function ($q) {
                    $q->where('semester_ke', 'like', '%' . $this->search . '%');
                })
                ->orWhereHas('user', function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%');
                })
                ->orderBy('updated_at', 'desc')
                ->paginate($this->paginate_per_page);
        } else {
            $matkuls = ModelsMatkul::where('user_id', auth()->user()->id)
                ->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('sks', 'like', '%' . $this->search . '%')
                        ->orWhere('created_at', 'like', '%' . $this->search . '%')
                        ->orWhere('updated_at', 'like', '%' . $this->search . '%')
                        ->orWhereHas('semester', function ($q) {
                            $q->where('semester_ke', 'like', '%' . $this->search . '%');
                        });
                })
                ->orderBy('updated_at', 'desc')
                ->paginate($this->paginate_per_page);
        }

        // get all semesters
        $this->semesters = Semester::where('user_id', auth()->user()->id)->get();

        return view('livewire.matkul', compact('matkuls'));
    }

    public function showForm($type)
    {
        $this->form = $type;

        if ($this->name) {
            $this->emptyItems();
        }
    }

    public function hideForm()
    {
        $this->form = '';
        $this->emptyItems();
        $this->noValidate();
    }

    public function noValidate()
    {
        $this->validate([
            'name' => '',
            'sks' => '',
            'semester_id' => ''
        ]);
    }

    public function emptyItems()
    {
        $this->name = '';
        $this->sks = '';
        $this->semester_id = '';
    }

    public function store()
    {
        $this->validate();

        $matkul = new ModelsMatkul;
        $matkul->name = $this->name;
        $matkul->user_id = auth()->user()->id;
        $matkul->sks = $this->sks;
        $matkul->semester_id = $this->semester_id;
        $matkul->save();

        $this->hideForm();

        $this->showAlert('success', 'Mata Kuliah berhasil ditambahkan.');
    }

    public function show($id)
    {
        $this->noValidate();

        $this->id_matkul = $id;
        $matkul = ModelsMatkul::findOrfail($id);

        if (auth()->user()->hasRole('admin') || $matkul->user_id == auth()->user()->id) {
            // get all semesters
            $this->semesters = Semester::get();

            $this->name = $matkul->name;
            $this->sks = $matkul->sks;
            $this->semester_id = $matkul->semester_id;
            $this->form = 'edit';
        } else {
            $this->showAlert('error', 'Mata Kuliah tidak dapat diubah.');
        }
    }

    public function update($id)
    {
        $this->validate();

        $matkul = ModelsMatkul::findOrFail($id);

        if (auth()->user()->hasRole('admin') || $matkul->user_id == auth()->user()->id) {
            $matkul->name = $this->name;
            $matkul->sks = $this->sks;
            $matkul->semester_id = $this->semester_id;
            $matkul->save();

            $this->showAlert('success', 'Mata Kuliah berhasil diubah.');
        } else {
            $this->showAlert('error', 'Mata Kuliah tidak dapat diubah.');
        }

        $this->hideForm();
    }


    public function confirmed()
    {
        $matkul = ModelsMatkul::findOrFail($this->id_matkul);

        if (auth()->user()->hasRole('admin') || $matkul->user_id == auth()->user()->id) {
            try {
                $matkul->destroy($this->id_matkul);
                $this->showAlert('success', 'Mata Kuliah berhasil dihapus.');
            } catch (Exception $ex) {
                $this->showAlert('error', 'Tidak dapat dihapus karena terdapat tugas pada mata kuliah : ' . $matkul->name);
            }
        } else {
            $this->showAlert('error', 'Mata Kuliah tidak dapat dihapus.');
        }

        $this->id_matkul = '';
        $this->hideForm();
    }

    public function cancelled()
    {
        $this->id_matkul = '';
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
        $this->id_matkul = $id;
        $this->confirm('Yakin ingin menghapus data ini?', [
            'toast' => false,
            'position' => 'center',
            'confirmButtonText' =>  'Ya',
            'cancelButtonText' =>  'Batal',
            'onConfirmed' => 'confirmed',
            'onCancelled' => 'cancelled'
        ]);
    }
}
