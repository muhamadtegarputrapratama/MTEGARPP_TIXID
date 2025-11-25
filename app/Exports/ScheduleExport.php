<?php

namespace App\Exports;

use App\Models\Schedule;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use carbon\Carbon; //untuk manipulasi datetime

class ScheduleExport implements FromCollection, WithHeadings, WithMapping
{

    private $key = 0;
    public function collection()
    {
        return Schedule::all();
    }


    public function headings(): array
    {
        return ['No', 'Bioskop', 'Film', 'Jam Tayang', 'Harga'];
    }

    public function map($schedule): array
    {
        return [
          ++$this->key,
           $schedule->cinema->name,
           $schedule->movie->title,
           implode(", ", $schedule->hours),
           $schedule->price,
        ];
    }
}
{
    /**
    * @return \Illuminate\Support\Collection
    */

}
