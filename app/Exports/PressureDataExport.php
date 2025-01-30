<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PressureDataExport implements FromCollection, WithHeadings
{
    protected $pressureData;

    public function __construct($pressureData)
    {
        $this->pressureData = $pressureData;
    }

    public function collection()
    {
        return collect($this->pressureData);
    }

    public function headings(): array
    {
        if (isset($this->pressureData[0])) {
            return array_keys((array) $this->pressureData[0]);
        }

        return [];
    }
}

