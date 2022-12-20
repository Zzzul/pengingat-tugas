<?php

namespace App\Http\Livewire;

use App\Models\Semester;
use Livewire\Component;
use Livewire\WithPagination;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use App\Rules\SemesterKeMustBeUnique;
 
class SemesterComponent extends Component
{
    use LivewireAlert, WithPagination;

    public $form, $id_semester, $semester_ke, $aktif_smt, $select_semesters = [];

    public $search = '';
    public $page = 1;
    public $paginate_per_page = 5;

    protected $paginationTheme = 'bootstrap';

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
        $this->select_semesters = Semester::byLoggedInUser()->get();

        $this->aktif_smt = Semester::activeSemester()->first();

        $semesters = Semester::byLoggedInUser()
            ->where('semester_ke', 'like', '%' . $this->search . '%')
            ->orderBy('updated_at', 'desc')
            ->paginate($this->paginate_per_page);

        return view('livewire.semester', compact('semesters'));
    }

    public function showForm($type)
    {
        $this->form = $type;

        if ($this->semester_ke) {
            $this->emptyItems();
        }
    }

    public function hideForm()
    {
        $this->form = '';
        
        $this->semester_ke = '';
        
        $this->noValidate();
    }

    public function noValidate()
    {
        $this->validate([
            'semester_ke' => ''
        ]);
    }

    public function store()
    {
        $this->validate([
            'semester_ke' => ['required', 'numeric', 'min:1', 'max:8', new SemesterKeMustBeUnique(0)],
        ]);

        Semester::create([
            'user_id' => auth()->id(),
            'semester_ke' => $this->semester_ke,
        ]);

        $this->alert('success', 'Semester berhasil ditambahkan.');

        $this->semester_ke = '';
        $this->form = '';
    }

    public function show($id)
    {
        $this->noValidate();

        $semester = Semester::ByLoggedInUserAndId($id)->firstOrFail();

        $this->id_semester = $semester->id;
        $this->semester_ke = $semester->semester_ke;

        $this->form = 'edit';
    }

    public function update($id)
    {
        $this->validate([
            'semester_ke' => ['required', 'numeric', 'min:1', 'max:8', new SemesterKeMustBeUnique($id)],
        ]);

        $semester = Semester::ByLoggedInUserAndId($id)->update(['semester_ke' => $this->semester_ke]);

        $this->alert('success', 'Semester berhasil diubah.');

        $this->hideForm();
    }

    public function emptyItems()
    {
        $this->semester_ke = '';
    }

    public function setAktifSmt($id)
    {
        if ($this->aktif_smt) {
            if ($id != $this->aktif_smt['id']) {
                $this->updateAktifSmt($id);

                // set smt aktif sebelumnya ke null, agar cuma ada 1 smt aktif
                $semester = Semester::where(['id' => $this->aktif_smt['id'], 'user_id' => auth()->id()])
                    ->update(['aktif_smt' => null]);
            }
        } else {
            $this->updateAktifSmt($id);
        }
    }

    public function updateAktifSmt($id)
    {
        $semester_aktif = Semester::ByLoggedInUserAndId($id)->update(['aktif_smt' => 1]);

        $this->alert('success', 'Semester sekarang berhasil diubah.');
    }

    public function confirmed()
    {
        Semester::byLoggedInUser()
            ->where('id', $this->id_semester)
            ->delete();

        $this->alert('success', 'Semester berhasil dihapus.');
        
        $this->id_semester = '';

        $this->hideForm();
    }

    public function cancelled()
    {
        $this->id_semester = '';

        $this->alert('error', 'Dibatalkan');
    }

    public function triggerConfirm($id)
    {
        $this->id_semester = $id;

        $this->confirm('Yakin ingin menghapus data ini?', [
            'toast' => false,
            'position' => 'center',
            'confirmButtonText' =>  'ya',
            'cancelButtonText' =>  'Batal',
            'onConfirmed' => 'confirmed',
            'onCancelled' => 'cancelled'
        ]);
    }
}
