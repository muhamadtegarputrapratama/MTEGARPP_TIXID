<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\Cinema;
use App\Models\Movie;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ScheduleExport;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cinemas = Cinema::all();
        $movies = Movie::all();
        //mengambil detail relasi cinema dan movie pakai with biar ga angka doang yg muncul
        $schedules = Schedule::with(['cinema', 'movie'])->get();
        return view('staff.schedule.index', compact('cinemas', 'movies', 'schedules'));
    }

    public function datatables() {
        $schedules = Schedule::with(['cinema', 'movie']);
        return DataTables::of($schedules)
        ->addIndexColumn()
        ->addColumn('btnActions', function($schedule){
        $btnEdit = '<a href="'.route('staff.staff.schedules.edit', $schedule['id']).'"class="btn btn-primary me-2">Edit</a>';
        $btnDelete = '<form action="'. route('staff.staff.schedules.delete', $schedule['id']). '" method="post">'.
        @csrf_field().
        @method_field('DELETE').'
        <button type="submit" class="btn btn-danger me-2">Hapus</button>
         </form>';
        return '<div class="d-flex">'. $btnEdit . $btnDelete . '</div>';
        })
        ->addColumn('hours', function($schedule) {
            $list = '';
            foreach($schedule->hours as $hour) {
                $list .= '<li>'. $hour .'</li>';
            }
            return '<ul>'. $list .'</ul>';
        })
        ->addColumn('price', function($schedule) {
            return 'Rp '. number_format($schedule->price, 0, ',', '.');
        })
        ->rawColumns(['btnActions', 'hours', 'price'])
        ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'cinema_id' => 'required',
            'movie_id' => 'required',
            'price' => 'required | numeric',
            //karna hours array yg divalidasi isi array nya (tanda . dan ini divalidasi semua isi item aray (tanda *)),
            'hours.*' => 'required | date_format:H:i',
        ], [
            'cinema_id.required' => 'Bioskop harus dipilih',
            'movie_id.required' => 'Film harus dipilih',
            'price.required' => 'harga harus diisi',
            'price.numeric' => 'harga harus diisi dengan angka',
            'hours.*.required' => 'Jam tayang harus diisi minimal 1 data',
            'hours.*.date_format' => 'Format jam tayang harus berformat jam:menit',
        ]);

        //pengecekan apakah ada bioskop dan film yg dipilih skrng di dbnya kalo ad ambil jamnya
        $hours = Schedule::where('cinema_id', $request->cinema_id)->where('movie_id', $request->movie_id)->value('hours');
        //jika sudah ada data dngan bioskop dan  film yg sm mka ambil data jam tsb jika tidak array kosong
        $hoursBefore = $hours ?? [];
        $mergeHours = array_merge($hoursBefore, $request->hours);
        $newHours = array_unique($mergeHours);

        $createData = Schedule::updateOrCreate([
            //array pertama acuan pencarian data
            'cinema_id' => $request->cinema_id,
            'movie_id' => $request->movie_id,
        ], [
            'price' => $request->price,
            'hours' => $newHours,
        ]);

        if ($createData) {
            return redirect()->route('staff.staff.schedules.index')->with('success', 'Berhasil menambahkan data!');
        } else {
            return redirect()->route('staff.staff.schedules.index')->with('error', 'Gagal! coba lagi');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Schedule $schedule)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Schedule $schedule, $id)
    {
       $schedule = Schedule::where('id', $id)->with(['cinema', 'movie'])->first();
       return view('staff.schedule.edit', compact('schedule'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Schedule $schedule, $id)
    {
        $request->validate([
            'price' => 'required | numeric',
            'hours.*' => 'required | date_format:H:i',
        ], [
            'price.required' => 'Harga harus diisi',
            'price.numeric' => 'Harga harus diisi dengan angka',
            'hours.required' => 'Jam tayang harus diisi',
            'hours.date_format' => 'Jam tayang harus diisi dengan format jam:menit',
        ]);

        $updateData = Schedule::where('id', $id)->update([
            'price' => $request->price,
            'hours' => $request->hours,
        ]);

        if ($updateData) {
            return redirect()->route('staff.staff.schedules.index')->with ('success', 'Berhasil mengubah data!');
        } else {
            return redirect()->back()->with('error', 'Gagal! coba laigi');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Schedule $schedule, $id)
    {
           Schedule::where('id', $id)->delete();
           return redirect()->route('staff.staff.schedules.index')->with('success', 'Berhasil menghapus data!');
    }

    public function trash() {
        $scheduleTrash = Schedule::with(['cinema', 'movie'])->onlyTrashed()->get();
        return view('staff.schedule.trash', compact('scheduleTrash'));
    }

    public function restore($id) {
        $schedule = Schedule::onlyTrashed()->find($id);
        $schedule->restore();
        return redirect()->route('staff.staff.schedules.index')->with('success', 'Berhasil mengembalikan data!');
    }

    public function deletePermanent($id) {
        $schedule = Schedule::onlyTrashed()->find($id);
        $schedule->forceDelete();
        return redirect()->back()->with('success', 'Berhasil menghapus seutuhnya!');
    }

    public function exportExcel() {
        $filename = 'data-jadwal-tayang.xlsx';
        return Excel::download(new ScheduleExport, $filename);
    }
}
