<?php

namespace App\Http\Livewire;

use App\Models\Semester as SemesterModels;
use App\User;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\TableComponent;
use Rappasoft\LaravelLivewireTables\Traits\HtmlComponents;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Http\Livewire\Semester;
use App\Models\Tugas;

class SemestersTable extends TableComponent
{
    use HtmlComponents;

    public function query(): Builder
    {
        return Tugas::with('matkul')
            ->where('deskripsi', 'like', '%' . $this->search . '%')
            ->orWhere('batas_waktu', 'like', '%' . $this->search . '%')
            ->orWhere('selesai', 'like', '%' . $this->search . '%')
            ->orWhereHas('matkul', function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%');
            })
            ->orderBy('selesai', 'asc');
    }

    public function columns(): array
    {
        $this->index = $this->page > 1 ? ($this->page - 1) * $this->perPage : 0;
        return [
            Column::make(__('No.'))->format(fn () => ++$this->index),
            Column::make('Mata Kuliah', 'matkul.name')
                ->searchable()
                ->sortable(),
            Column::make('Deskripsi', 'deskripsi')
                ->searchable()
                ->sortable(),
            Column::make('Batas Waktu', 'batas_waktu')
                ->searchable()
                ->sortable(),
            Column::make('Pertemuan Ke', 'pertemuan_ke')
                ->searchable()
                ->sortable(),
            Column::make('Dibuat Pada', 'created_at')
                ->searchable()
                ->sortable()
                ->format(function (Tugas $model) {
                    return $model->created_at->diffForHumans();
                }),
            Column::make('Terakhit Diubah', 'updated_at')
                ->searchable()
                ->sortable(),
        ];
    }
}
