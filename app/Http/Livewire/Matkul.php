<?php

namespace App\Http\Livewire;

use App\Models\Matkul as ModelsMatkul;
use App\Models\Semester;
use Livewire\Component;
use Livewire\WithPagination;
use PhpParser\Builder\Function_;
use PhpParser\Node\Expr\FuncCall;
use Jantinnerezo\LivewireAlert\LivewireAlert;
 
class Matkul extends Component
{
    use LivewireAlert, WithPagination;

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

        //  ->orWhereHas('semester', function ($q) {
        //     $q->where('semester_ke', 'like', '%' . $this->search . '%');
        // })

        $matkuls = ModelsMatkul::where('name', 'like', '%' . strtolower($this->search) . '%')
            ->orWhere('sks', 'like', '%' . strtolower($this->search) . '%')
            ->orWhere('created_at', 'like', '%' . strtolower($this->search) . '%')
            ->orWhere('updated_at', 'like', '%' . strtolower($this->search) . '%')
            ->orWhereHas('semester', function ($q) {
                $q->where('semester_ke', 'like', '%' . strtolower($this->search) . '%');
            })
            ->orderBy('updated_at', 'desc')
            ->paginate($this->paginate_per_page);

        // get all semesters
        $this->semesters = Semester::get();

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
        $matkul->name = strtolower($this->name);
        $matkul->sks = $this->sks;
        $matkul->semester_id = $this->semester_id;
        $matkul->save();

        $this->hideForm();

        $this->showAlert('Mata Kuliah berhasil ditambahkan.');
    }

    public function show($id)
    {
        $this->noValidate();

        $this->id_matkul = $id;
        $matkul = ModelsMatkul::find($id);

        // get all semesters
        $this->semesters = Semester::get();

        $this->name = ucwords($matkul->name);
        $this->sks = $matkul->sks;
        $this->semester_id = $matkul->semester_id;
        $this->form = 'edit';
    }

    public function update($id)
    {
        $this->validate();

        $matkul = ModelsMatkul::find($id);
        $matkul->name = strtolower($this->name);
        $matkul->sks = $this->sks;
        $matkul->semester_id = $this->semester_id;
        $matkul->save();

        $this->hideForm();

        $this->showAlert('Mata Kuliah berhasil diubah.');
    }


    public function showAlert($message)
    {
        $this->alert('success', $message, [
            'position'          =>  'top',
            'timer'             =>  1500,
            'toast'             =>  true,
            'showCancelButton'  =>  false,
            'showConfirmButton' =>  false
        ]);
    }

    public function confirmed()
    {
        ModelsMatkul::destroy($this->id_matkul);

        $this->showAlert('Mata Kuliah berhasil dihapus.');
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
