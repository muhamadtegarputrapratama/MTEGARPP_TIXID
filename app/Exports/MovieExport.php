<?php

namespace App\Exports;

use App\Models\Movie;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use carbon\Carbon; //untuk manipulasi datetime

class MovieExport implements FromCollection, WithHeadings, WithMapping
{
    //membuat propeerty untuk no urutan data
    private $key = 0;
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Movie::all();
    }

    //ingin menentukan header data
    public function headings(): array
    {
        return ['No', 'Judul Film', 'Durasi', 'Genre', 'Sutradara', 'Usia Minimal', 'Poster', 'Sinopsis'];
    }

    //menentukan isi data
    public function map($movie): array
    {
        return [
            //menambahkan sebanyak 1 setiap data dari diatas
          ++$this->key,
           $movie->title,
           Carbon::parse($movie->duration)->format("H") . " Jam" . Carbon::parse($movie->duration)->format('i') . " Menit",
           $movie->genre,
           $movie->director,
           //format usia
           $movie->age_rating . "+",
           //asset() : link buat gambar
           asset('storage/' . $movie->poster),
           $movie->description
        ];
    }
}
