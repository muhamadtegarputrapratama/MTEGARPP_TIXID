<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\MovieExport;
use App\Models\Schedule;
use Yajra\DataTables\Facades\DataTables;

class MovieController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $movies = Movie::all();
        return view('admin.movie.index', compact('movies'));
    }

    public function datatables() {
        //jika data yg diambil tidak ada relasi gunakan query() untuk menyiapkan,
        //jika ada relasi gunakan with([]) : Movie::with(['relasi1', 'relasi2'])->get();
        //query() : menyiapkan query eloquent model untuk dipake di datatables
        $movies = Movie::query();
        //of() : mengambil query eloquent dari model yg akan diproses datanya
        return DataTables::of($movies)
        //memunculkan angka 1-dst di table
        ->addIndexColumn()
        //membuat column menyajikan data selain data asli dari db
        ->addColumn('imgPoster', function($movie) {
            $imgUrl = asset('storage/' . $movie->poster);
            return '<img src="' . $imgUrl . '" width="120" />';
        })
        ->addColumn('activeBadge', function($movie) {
            if ($movie->actived ==1) {
                return '<span class="badge bg-success">Aktif</span>';
            } else {
                return '<span class="badge bg-secondary">Non-aktif</span>';
            }
        })
        ->addColumn('btnActions', function($movie) {
          $btnDetail = '<button class="btn btn-secondary me-2" onclick=\'showModal
          ('.json_encode($movie) .')\'>Detail</button>';

          $btnEdit = '<a href="'.route('admin.movies.edit', $movie['id']).'"
          class="btn btn-primary me-2">Edit</a>';

          $btnDelete = ' <form action="'.route('admin.movies.destroy', $movie['id']).'" method="post">'.
                    @csrf_field().
                    @method_field('DELETE').'
                    <button type="submit" class="btn btn-danger me-2">Hapus</button>
               </form>';

               if ($movie['actived'] == 1) {
                $btnNonAKTIF =  ' <form action="'.route('admin.movies.actived', $movie['id']).'" method="post">'.
                    @csrf_field().
                    @method_field('PATCH').'
                    <button type="submit" class="btn btn-danger me-2">Non-aktif</button>
               </form>';
               } else {
                $btnNonAKTIF = '';
               }
               return '<div class="d-flex">'. $btnDetail . $btnEdit . $btnDelete . $btnNonAKTIF . '</div>';
        })

        //daftarkan nama dari addColumn untuk dipanggil di js datatables nya
        ->rawColumns(['imgPoster', 'activeBadge', 'btnActions'])
        //ubah query jadi json agar bisa dibaca js datatables
        ->make(true);
    }

    public function home()
    {
        //where untuk mencari data (field, operator, value)
        //get() ->mengambil semua data hasil filter
        //first()->mengambil satu data pertama hasil filter
        //paginate()->membagi data menjadi beberapa halaman
        //orderby() ->untuk mengurut data formatnya(field, type)
        //type ASC->a-z/0-9/lama->Baru
        //type DESC->z-a/9-0/Baru-Lama
        //limit()->mengambil data dengan jumlah tertentu formatnya (angka)
        $movies = Movie::where('actived', 1)->orderBy('created_at', 'desc')->limit(4)->get();
        return view('home', compact('movies'));
    }

    public function homeMovies(Request $request)
    {
        //pengambilan hasil data dari input
        $nameMovie = $request->search_movie;
        if ($nameMovie != "") {
            //like=mencari data yang mirip
            //% depan=mencari kata belakang, $belakang=mencari kata depan, $depan belakang=mencari kata depan belakang
            $movies = Movie::where('title', 'LIKE', '%' . $nameMovie . '%')->where('actived', 1)->orderBy('created_at', 'desc')->get();
        } else {
            $movies = Movie::where('actived', 1)->orderBy('created_at', 'desc')->get();
        }
        return view('movies', compact('movies'));
    }

    public function movieSchedule($movie_id, Request $request)
    {
        $sortirHarga = $request->sortirHarga; //mengambil ? bisa dengan request $request
        if ($sortirHarga) {
            $movie = Movie::where('id', $movie_id)->with(['schedules' => function ($q) use ($sortirHarga) {
                $q->orderBy('price', $sortirHarga);
            }, 'schedules.cinema'])->first();
        } else {
            $movie = Movie::where('id', $movie_id)->with(['schedules', 'schedules.cinema'])->first();
        }

        $sortirAlfabet = $request->sortirAlfabet;
        if ($sortirAlfabet == 'ASC') {
            $movie->schedules = $movie->schedules->sortBy(function ($schedule) {
                return $schedule->cinema->name;
            })->values();

        } elseif ($sortirAlfabet == 'DESC') {
            $movie->schedules = $movie->schedules->sortByDesc(function ($schedule) {
                return $schedule->cinema->name;
            })->values();
        }

        return view('schedule.detail-film', compact('movie'));
    }

    public function actived($id)
    {
        $movie = Movie::find($id);
        $movie->actived = !$movie->actived;
        $movie->save();
        return redirect()->route('admin.movies.index')->with('success', 'Data Berhasil Non-aktif!');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.movie.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'title' => 'required',
            'duration' => 'required',
            'genre' => 'required',
            'director' => 'required',
            'age_rating' => 'required',
            'poster' => 'required|mimes:jpg, jpeg, png, svg, webp',
            'description' => 'required|min:10'
        ], [
            'title.required' => 'Judul film harus diisi',
            'duration.required' => 'Durasi film harus diisi',
            'genre.required' => 'Genre film harus diisi',
            'director.required' => 'Sutradara harus diisi',
            'age_rating.required' => 'Usia minimal harus diisi',
            'poster.required' => 'Poster harus diisi',
            'poster.mimes' => 'Poster harus berbentuk JPG/JPEG/PNG/SVG/WEBP',
            'description.required' => 'Sinopsis harus diisi',
            'description.min' => 'Sinopsis diisi minimal 10 karakter'
        ]); //ambil file dari input

        $poster = $request->file('poster');
        //buat nama file yg akan disimpan di folder public /storage
        //nama dibuat baru dan unik untuk menghindari duplikasi file
        $namaFile = rand(1, 10) . "-poster." . $poster->getClientOriginalExtension();
        //mengambil ekstensi file yg diupload
        $path = $poster->storeAs("poster", $namaFile, "public");
        //storeas("namafolder, namafile, visibility)

        $createData = Movie::create([
            'title' => $request->title,
            'duration' => $request->duration,
            'genre' => $request->genre,
            'director' => $request->director,
            'age_rating' => $request->age_rating,
            'poster' => $path,
            'description' => $request->description,
            'actived' => 1
        ]);
        if ($createData) {
            return redirect()->route('admin.movies.index')->with('success', 'Berhasil membuat data!');
        } else {
            return redirect()->back()->with('error', 'Gagal menambahkan detail');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Movie $movie)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $movie = Movie::find($id);
        return view('admin.movie.edit', compact('movie'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required',
            'duration' => 'required',
            'genre' => 'required',
            'director' => 'required',
            'age_rating' => 'required',
            'poster' => 'mimes:jpg, jpeg, png, svg, webp',
            'description' => 'required|min:10'
        ], [
            'title.required' => 'Judul film harus diisi',
            'duration.required' => 'Durasi film harus diisi',
            'genre.required' => 'Genre film harus diisi',
            'director.required' => 'Sutradara harus diisi',
            'age_rating.required' => 'Usia minimal harus diisi',
            'poster.required' => 'Poster harus diisi',
            'poster.mimes' => 'Poster harus berbentuk JPG/JPEG/PNG/SVG/WEBP',
            'description.required' => 'Sinopsis harus diisi',
            'description.min' => 'Sinopsis diisi minimal 10 karakter'
        ]); //ambil file dari input
        $movie = Movie::find($id);
        if ($request->file('poster')) {
            $posterSebelumnya = storage_path('app/public/' . $movie['poster']);

            if (file_exists($posterSebelumnya)) {
                unlink($posterSebelumnya);
            }
            $poster = $request->file('poster');
            //buat nama file yg akan disimpan di folder public /storage
            //nama dibuat baru dan unik untuk menghindari duplikasi file
            $namaFile = rand(1, 10) . "-poster." . $poster->getClientOriginalExtension();
            //mengambil ekstensi file yg diupload
            $path = $poster->storeAs("poster", $namaFile, "public");
            //storeas("namafolder, namafile, visibility)
        }

        $createData = Movie::where('id', $id)->update([
            'title' => $request->title,
            'duration' => $request->duration,
            'genre' => $request->genre,
            'director' => $request->director,
            'age_rating' => $request->age_rating,
            //jika ada ambil jika tidak gunakan yang disini
            'poster' => $path ?? $movie['poster'],
            'description' => $request->description,
            'actived' => 1
        ]);
        if ($createData) {
            return redirect()->route('admin.movies.index')->with('success', 'Berhasil mengubah data!');
        } else {
            return redirect()->back()->with('error', 'Gagal menambahkan detail');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $schedule = Schedule::where('movie_id', $id)->count();
        if ($schedule) {
            return redirect()->route('admin.movies.index')->with('error', 'Tidak dapat menghapus data film! Data tertaut dengan jadwal tayang');
        }

        $movie = Movie::find($id);

        if ($movie->poster && storage::disk('public')->exists($movie->poster)) {
            storage::disk('public')->delete($movie->poster);
        }
        $movie->delete();
        Movie::where('id', $id)->delete();
        return redirect()->route('admin.movies.index')->with('success', 'Berhasil hapus data!');
    }

    public function exportExcel()
    {
        //nama file yg akan diunduh
        $fileName = 'data-film.xlsx';
        //proses download
        return Excel::download(new MovieExport, $fileName);
    }

    public function trash()
    {
        $movieTrash = Movie::onlyTrashed()->get();
        return view('admin.movie.trash', compact('movieTrash'));
    }
    public function restore($id)
    {
        $movie = Movie::onlyTrashed()->find($id);
        $movie->restore();
        return redirect()->route('admin.movies.index')->with('success', 'Berhasil mengembalikan data!');
    }

    public function deletePermanent($id)
    {
        $movie = Movie::onlyTrashed()->find($id);
        if ($movie->poster && storage::disk('public')->exists($movie->poster)) {
            storage::disk('public')->delete($movie->poster);
        }
        $movie->forceDelete();
        return redirect()->back()->with('success', 'Berhasil menghapus seutuhnya!');
    }

    public function chart() {
        $movieActive = Movie::where('actived', 1)->count();
        $movieNonActive = MOvie::where('actived', 0)->count();
        //yg diperlukan jumlah data gunakan count untuk menghitungnya
        $data = [$movieActive, $movieNonActive];
        return response()->json([
            'data' => $data
        ]);
    }
}
