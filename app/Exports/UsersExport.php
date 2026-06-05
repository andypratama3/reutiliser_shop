<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class UsersExport implements FromCollection, WithHeadings, WithStyles
{
    public function collection()
    {
        return User::withCount('orders')->get()->map(function ($user) {
            return [
                $user->name,
                $user->email,
                $user->phone ?? '-',
                $user->status,
                $user->orders_count,
                $user->created_at->format('d/m/Y'),
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Nama',
            'Email',
            'No. Telepon',
            'Status',
            'Total Pesanan',
            'Tanggal Bergabung',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
