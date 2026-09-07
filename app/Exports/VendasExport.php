<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class VendasExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    public function __construct(private readonly Collection $registros)
    {
    }

    public function collection(): Collection
    {
        return $this->registros;
    }

    public function headings(): array
    {
        return [
            'Data',
            'Produto',
            'Categoria',
            'Comprador',
            'Vendedor',
            'Quantidade',
            'Valor',
            'Status',
        ];
    }

    public function map(mixed $registro): array
    {
        return [
            $registro['date'],
            $registro['produto'],
            $registro['category'],
            $registro['comprador'],
            $registro['vendedor'],
            $registro['quantidade'],
            (float) $registro['value'],
            ucfirst($registro['status']),
        ];
    }
}