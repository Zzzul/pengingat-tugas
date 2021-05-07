<?php

namespace App\Http\Controllers;

use App\Models\Tugas;
use App\Models\Matkul;
use App\Models\Semester;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\DB;

class PdfController extends Controller
{
    /**
     * Download mata kuliah pada semester sekarang
     * */
    public function downloadMatkulActive()
    {
        $jadwal = Matkul::where('user_id', auth()->id())
            ->whereHas('semester', function ($q) {
                $q->where('aktif_smt', 1)->where('user_id', auth()->id());
            })
            ->get();

        $pdf = PDF::loadView('pdf.matkul-aktif', compact('jadwal'))->setPaper('a4', 'potrait');
        return $pdf->stream();
    }

    /**
     * Download semua mata kuliah
     * */
    public function downloadAllMatkul()
    {
        $matkuls = Matkul::where('user_id', auth()->id())->with('semester')->get();
        // dd($matkuls);
        $pdf = PDF::loadView('pdf.all-matkul', compact('matkuls'))->setPaper('a4', 'potrait');
        // return $pdf->download('Daftar semua mata kuliah.pdf');
        return $pdf->stream();
    }

    /**
     * Download semua tugas
     * */
    public function downloadAllTugas()
    {
        $all_tugas = Matkul::with('semester', 'tugas')->get();
        // echo json_encode($all_tugas);
        // die;
        // return view('pdf.all-tugas', compact('all_tugas'));

        $pdf = PDF::loadView('pdf.all-tugas', compact('all_tugas'))->setPaper('a4', 'landscape');

        return $pdf->stream();
    }

    /**
     * Download tugas yang belom selesai
     */
    public function downladTugas()
    {
        $tugas_yg_ga_dikerjain = DB::table('tugas')
            ->join('matkuls', 'matkuls.id', '=', 'tugas.matkul_id')
            ->join('semesters', 'semesters.id', '=', 'matkuls.semester_id')
            ->select('*')
            ->where('tugas.selesai', null)
            ->where('tugas.user_id', auth()->id())
            ->where('matkuls.user_id', auth()->id())
            ->where('semesters.user_id', auth()->id())
            ->where('semesters.aktif_smt', '!=', null)
            ->get();

        $pdf = PDF::loadView('pdf.tugas-bd', compact('tugas_yg_ga_dikerjain'));
        return $pdf->stream();
        // return view('pdf.tugas-bd', compact('tugas_yg_ga_dikerjain'));
        // echo json_encode($tugas_yg_ga_dikerjain);
    }

    /**
     * Download semua semester
     * */
    public function downloadAllSemester()
    {
        $semesters = Semester::where('user_id', auth()->id())->orderBy('semester_ke', 'asc')->get();
        // dd($semesters);
        $pdf = PDF::loadView('pdf.all-semester', compact('semesters'));
        return $pdf->stream();
    }
}
