<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AdminExport implements FromCollection, WithHeadings, WithMapping
{
    protected $users;

    public function __construct(Collection $users)
    {
        $this->users = $users;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return $this->users;
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'NO',
            'USERNAME',
            'PASSWORD',
            'IS_ADMIN',
            'IS_VERIFY',
            'CREATED_AT',
            'UPDATED_AT'
        ];
    }

    /**
     * @param mixed $user
     * @return array
     */
    public function map($user): array
    {
        static $index = 0;
        $index++;
        
        return [
            $index,
            $user->username ?? '',
            '***********', // Password masked
            $user->is_admin ?? 0,
            $user->is_verify ?? 0,
            $user->created_at ?? '',
            $user->updated_at ?? ''
        ];
    }
}
