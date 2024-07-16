<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Style\Style;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithStyles;

class UsersExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $data;

    public function __construct(Collection $data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        return $this->data;
    }

    public function headings(): array
    {
        return [
            'Id',
            'User Email',
            'Project Name',
            'Device Id',
            'Serial Id',
            'Firmware Version Id',
            'Software Version',
            'Hardware Version',
            'KTS',
            'Counter',
            'Probes',
            'Accessory HW0',
            'Accessory HW1',
            'Accessory HW2',
            'Accessory HW3',
            'MOTDEP',
            'Alarm 0',
            'Alarm 1',
            'Alarm 2',
            'Alarm 3',
            'Alarm 4',
            'Alarm 5',
            'Alarm 6',
            'Alarm 7',
            'Alarm 8',
            'Alarm 9',
            'Alarm 10',
            'Alarm 11',
            'Alarm 12',
            'Timestamp',
            'Remarks',
            'Image'
        ];
    }

    public function map($row): array
    {
        return [
            $row['Id'],
            $row['User Email'],
            $row['ProjectName'],
            $row['Device Id'],
            $row['Serial Id'],
            $row['Firmware Version Id'],
            $row['Software Version'],
            $row['Hardware Version'],
            $row['KTS'],
            $row['Counter'],
            $row['Probes'],
            $row['AccessoryHW0'],
            $row['AccessoryHW1'],
            $row['AccessoryHW2'],
            $row['AccessoryHW3'],
            $row['MOTDEP'],
            $row['Alarm0'],
            $row['Alarm1'],
            $row['Alarm2'],
            $row['Alarm3'],
            $row['Alarm4'],
            $row['Alarm5'],
            $row['Alarm6'],
            $row['Alarm7'],
            $row['Alarm8'],
            $row['Alarm9'],
            $row['Alarm10'],
            $row['Alarm11'],
            $row['Alarm12'],
            $row['Timestamp'],
            $row['Remarks'],
            isset($row['Picture']) ? '=HYPERLINK("' . asset('storage/uploads/' . $row['Picture']) . '", "' . $row['Picture'] . '")' : ''
        ];
    }
    
    public function styles(Worksheet $sheet)
    {
        $columnIndex = count($this->headings());
        for ($i = 2; $i <= $sheet->getHighestRow(); $i++) {
            $cell = $sheet->getCellByColumnAndRow($columnIndex, $i);
            if ($cell->getValue() != '') {
                $sheet->getStyleByColumnAndRow($columnIndex, $i)->applyFromArray([
                    'font' => [
                        'color' => ['argb' => 'FF0000FF'], // Blue color
                        'underline' => true,
                    ],
                    'hyperlink' => true,
                ]);
            }
        }
    }
}
