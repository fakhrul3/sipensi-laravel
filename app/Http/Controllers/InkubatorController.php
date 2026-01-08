<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class InkubatorController extends Controller
{
    public function index(Request $request)
    {
        // Ambil data asli dari database
        $query = \App\Models\Inkubator::query();
    
        // Filter berdasarkan kode_provinsi dari URL
        if ($request->filled('kode_provinsi')) {
            $query->where('kode_provinsi', $request->kode_provinsi);
        }
    
        // Eksekusi dan ambil hasilnya
        $inkubators = $query->get();
    
        // Mapping jenis lembaga (untuk label di tabel)
        $jenisMap = [
            1 => 'Pemerintah Pusat',
            2 => 'Pemerintah Daerah',
            3 => 'Lembaga Pendidikan',
            4 => 'Badan Usaha',
            5 => 'Masyarakat'
        ];
    
        return view('inkubator.index', compact('inkubators', 'jenisMap'));
    }
}