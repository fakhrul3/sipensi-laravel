<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class LembagaInkubatorExport implements FromCollection, WithHeadings, WithMapping
{
    protected $inkubators;

    public function __construct(Collection $inkubators)
    {
        $this->inkubators = $inkubators;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return $this->inkubators;
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'NO',
            'ACCOUNT',
            'TANDA DAFTAR',
            'JENIS LEMBAGA INKUBATOR',
            'NAMA LEMBAGA INKUBATOR',
            'LEMBAGA INDUK INKUBATOR',
            'NAMA KETUA LEMBAGA INKUBATOR',
            'NO KONTAK',
            'EMAIL',
            'PERINGKAT',
            'ALAMAT KANTOR',
            'TANGGAL DAFTAR',
            'TANGGAL UPDATE',
            'PROVINSI',
            'TANGGAL SK TERBIT',
            'USERNAME',
            'VERIFIKASI'
        ];
    }

    /**
     * @param mixed $inkubator
     * @return array
     */
    public function map($inkubator): array
    {
        static $index = 0;
        $index++;
        
        return [
            $inkubator->Nomor ?? $index,
            $inkubator->Account ?? '',
            $inkubator->Tanda_Daftar ?? '',
            $inkubator->Jenis_Lembaga_Inkubator ?? '',
            $inkubator->Nama_Lembaga_Inkubator ?? '',
            $inkubator->Lembaga_Induk_Inkubator ?? '',
            $inkubator->Nama_Ketua_Lembaga_Inkubator ?? '',
            $inkubator->No_Kontak ?? '',
            $inkubator->Email ?? '',
            $inkubator->Peringkat ?? '',
            $inkubator->Alamat_Kantor ?? '',
            $inkubator->Tanggal_Daftar ? (is_string($inkubator->Tanggal_Daftar) ? date('d/m/Y', strtotime($inkubator->Tanggal_Daftar)) : $inkubator->Tanggal_Daftar->format('d/m/Y')) : '',
            $inkubator->Tanggal_Update ? (is_string($inkubator->Tanggal_Update) ? date('d/m/Y', strtotime($inkubator->Tanggal_Update)) : $inkubator->Tanggal_Update->format('d/m/Y')) : '',
            $inkubator->Provinsi ?? '',
            $inkubator->Tanggal_SK_Terbit ? (is_string($inkubator->Tanggal_SK_Terbit) ? date('d/m/Y', strtotime($inkubator->Tanggal_SK_Terbit)) : ($inkubator->Tanggal_SK_Terbit ? $inkubator->Tanggal_SK_Terbit->format('d/m/Y') : '')) : '',
            $inkubator->username ?? '',
            ($inkubator->is_verify == 1 || $inkubator->is_verify == 2) ? 'Terverifikasi' : 'Belum Terverifikasi'
        ];
    }
}
