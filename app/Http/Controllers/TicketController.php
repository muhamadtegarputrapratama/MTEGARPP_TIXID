<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Schedule;
use App\Models\TicketPayment;
use App\Http\Controllers\ScheduleController;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Promo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $userId = Auth::user()->id;
        //ambil data ticket berdasarkan data siapa yang login
        $ticketActive = Ticket::where('user_id', $userId)->where('actived', 1)->where('date', now()->format('Y-m-d'))->get();
        //ambil data ticket berdasarkan data siapa yang login, yg non aktif dan sudah kadaluwarsa
        $ticketNonActive = Ticket::where('user_id', $userId)->where('date', '<>', now()->format('Y-m-d'))->get();
        // dd($userId, $ticketNonActive);
        // <> bukan sama dengan
        return view('ticket.index', compact('ticketActive', 'ticketNonActive'));
    }

    public function showSeats($scheduleId, $hourId)
    {
        $schedule = Schedule::find($scheduleId);
        $hour = $schedule['hours'][$hourId] ?? '';
        $soldSeats = Ticket::where('schedule_id', $scheduleId)->where('actived', 1)->where('date',now()->format('Y-m-d'))->pluck('rows_of_seats');
        //pluck=ambil data hanya dari 1 field/column kemudian disatukan di array

        $soldSeatsFormat = [];
        foreach ($soldSeats as $key => $seat) {
            // karena $soldseats bentuknya 2 dimensi jd loop duakali simpan ke array diatas untuk data 1 dimensi
            foreach ($seat as $item) {
                array_push($soldSeatsFormat, $item);
            }
        }
        // dd($soldSeatsFormat);
        return view('schedule.row-seats', compact('schedule', 'hour', 'soldSeatsFormat'));
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
            'user_id' => 'required',
            'schedule_id' => 'required',
            'date' => 'required',
            'hour' => 'required',
            'rows_of_seats' => 'required',
            'quantity' => 'required',
            'total_price' => 'required',
            'service_fee' => 'required',
        ]);

        $createData = Ticket::create([
            'user_id' => $request->user_id,
            'schedule_id' => $request->schedule_id,
            'date' => $request->date,
            'hour' => $request->hour,
            'rows_of_seats' => $request->rows_of_seats,
            'quantity' => $request->quantity,
            'total_price' => $request->total_price,
            'service_fee' => $request->service_fee,
            'actived' => 0,
        ]);

        return response()->json([
            'message' => 'Berhasil membuat data tiket',
            'data' => $createData,
        ]);
    }

    public function ticketOrderPage($ticketId)
    {
        $ticket = Ticket::where('id', $ticketId)->with(['schedule', 'schedule.cinema', 'schedule.movie'])->first();
        $promos = Promo::where('actived', 1)->get();
        return view('schedule.order', compact('ticket', 'promos'));
    }

    public function createBarcode(Request $request)
    {
        $kodeBarcode = 'TICKET' . $request->ticket_id;
        $qrImage = QrCode::format('svg')
            ->size(300) //ukuran pixel
            ->margin(2) //margin tepi
            ->errorCorrection('H') // tingkat koreksi error L, M, Q, H
            ->generate($kodeBarcode);

        $filename = $kodeBarcode . '.svg';
        $path = 'barcodes/' . $filename;

        Storage::disk('public')->put($path, $qrImage);

        $createData = TicketPayment::create([
            'ticket_id' => $request->ticket_id,
            'qrcode' => $path,
            'status' => 'process',
            'booked_date' => now()
        ]);

        $ticket = Ticket::find($request->ticket_id);
        $totalPrice = $ticket->total_price;

        if ($request->promo_id != NULL) {
            $promo = Promo::find($request->promo_id);
            if ($promo['type'] == 'percent') {
                $discount = $ticket['total_price'] * $promo['discount'] / 100;
            } else {
                $discount = $promo['discount'];
            }
            $totalPrice = $ticket['total_price'] - $discount;
        }
        $updateTicket = Ticket::where('id', $request->ticket_id)->update([
            'promo_id' => $request->promo_id,
            'total_price' => $totalPrice
        ]);

        return response()->json([
            'message' => 'Berhasil membuat pesanan tiket sementara!',
            'data' => $createData
        ]);
    }

    public function dataChart() {
        $month = now()->format('m');
        //hasil collection (get) dikelomokkan berdasarkan booked_date
        //toArray(): ubah collection menjadi array untuk memudahkan pengambilan data
        $tickets = Ticket::where('actived', 1)->whereHas('ticketPayment', function($q) use($month) {
            $q->whereMonth('booked_date', $month);
        })->get()->groupBy(function($ticket) {
            return Carbon::parse($ticket->ticketPayment->booked_date)->format('Y-m-d');
        })->toArray();
        //ambil key dari array assoc (tanggal)
        $labels = array_keys($tickets);
        //siapkan wadah untuk array yg akan berisi angka jumlah data di tgl tersebut
        $data = [];
        foreach ($tickets as $ticketGroup) {
            array_push($data, count($ticketGroup));
        }
        return response()->json([
            'labels' => $labels,
            'data' => $data
        ]);
    }



    /**
     * Display the specified resource.
     */
    public function show($ticketId)
    {
        $ticket = Ticket::where('id', $ticketId)->with(['schedule', 'schedule.movie', 'schedule.cinema', 'ticketPayment', ])->first();
        return view('schedule.ticket', compact('ticket'));
    }

    public function exportPdf($ticketId) {
        //siapkan data yg akan ditampilkan di pdf hasilnya harus bentuk array
        $ticket = Ticket::where('id', $ticketId)->with(['schedule', 'schedule.movie', 'schedule.cinema', 'ticketPayment'])->first()->toArray();
        //buat nama variable yg akan digunakn di blade pdf
        view()->share('ticket', $ticket);
        //menentukan file blade yg akan dicetakkan dan kirim juga datanya
        $pdf = Pdf::loadView('schedule.export-pdf', $ticket);
        //download pdf dengan nama tertentu
        $filename = 'TICKET' . $ticketId . '.pdf';
        return $pdf->download($filename);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Ticket $ticket)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Ticket $ticket)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ticket $ticket)
    {
        //
    }
    public function ticketPaymentPage($ticketId)
    {
        $ticket = Ticket::where('id', $ticketId)->with(['schedule', 'promo', 'ticketPayment'])->first();
        return view('schedule.payment', compact('ticket'));
    }

     public function updateStatusTicket($ticketId) {
        $updatePayment = TicketPayment::where('ticket_id', $ticketId)->update([
            'paid_date' => now()]);
        $updateStatus = Ticket::where('id', $ticketId)->update([
            'actived' => 1
        ]);
        // diarahkan ke halaman tiket yang sudah dibayar
        return redirect()->route('tickets.show', $ticketId);
    }
}
