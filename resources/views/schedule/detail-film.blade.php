@extends ('templates.app')
@section('content')
    <div class="container pt-5">
        <div class="w-75 d-block m-auto">
            <div class="d-flex">
                <div style="width: 150px; height: 200px">
                    <img src= "{{ asset('storage/' . $movie['poster']) }}" class="w-100">
                </div>
                <div class="ms-5 mt-4">
                    <h5>{{ $movie['title'] }}</h5>
                    <table>
                        <tr>
                            <td><b class="text-secondary">Genre</b></td>
                            <td class="px-3"></td>
                            <td>{{ $movie['genre'] }}</td>
                        </tr>
                        <tr>
                            <td><b class="text-secondary">Durasi</b></td>
                            <td class="px-3"></td>
                            <td>{{ $movie['duration'] }}</td>
                        </tr>
                        <tr>
                            <td><b class="text-secondary">Sutradara</b></td>
                            <td class="px-3"></td>
                            <td>{{ $movie['director'] }}</td>
                        </tr>
                        <tr>
                                <td><b class="text-secondary">Rating Usia</b></td>
                                <td class="px-3">:</td>
                                <td>
                                    <span class="badge" style="background-color: #f8d7da; color: #a71d2a;">
                                        {{ $movie['age_rating'] }}
                                    </span>
                                </td>
                            </tr>
                    </table>
                </div>
            </div>

            <div class="w-100 row mt-5">
                <!-- Bagian Rating -->
                <div class="col-6 pe-5">
                    <div class="d-flex flex-column justify-content-end align-items-end">
                        <div class="d-flex align-items-center">
                            <h3 class="text-warning me-2 mb-0">9.5</h3>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                             <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star-half-alt text-warning"></i>
                        </div>
                        <small class="text-muted">4.114 vote</small>
                    </div>
                </div>

                <!-- Bagian Watchlist -->
                <div class="col-6 ps-5" style="border-left: 2px solid #c7c7c7">
                    <div class="d-flex flex-column justify-content-start align-items-start">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-heart text-danger me-2"></i>
                            <b>Masukkan watchlist</b>
                        </div>
                        <small class="text-muted">9.000.000</small>
                    </div>
                </div>
            </div>

            <div class="d-flex w-100 bg-light mt-3">
                <div class="dropwdown">
                    <button class="btn btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        Bioskop
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#">Bogor</a></li>
                        <li><a class="dropdown-item" href="#">Jakarta Timur</a></li>
                        <li><a class="dropdown-item" href="#">Jakarta Barat</a></li </ul>
                </div>
                @php
                    if (request()->get('sortirHarga') == 'ASC') {
                        //kalau yang dipilih ASC, maka ubah ke DESC
                        $sortirHarga = 'DESC';
                    } elseif (request()->get('sortirHarga') == 'DESC') {
                        //kalau yang dipilih DESC, maka ubah ke ASC
                        $sortirHarga = 'ASC';
                        //kalau gak ada yang dipilih, default ke ASC
                    } else {
                        $sortirHarga = 'ASC';
                    }

                    if (request()->get('sortirAlfabet') == 'ASC') {
                        $sortirAlfabet = 'DESC';
                    } elseif (request()->get('sortirAlfabet') == 'DESC') {
                        $sortirAlfabet = 'ASC';
                    } else {
                        $sortirAlfabet = 'ASC';
                    }
                @endphp
                <div class="dropwdown">
                    <button class="btn btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        Sortir
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="?sortirHarga={{ $sortirHarga }}">Harga</a></li>
                        <li><a class="dropdown-item" href="?sortirAlfabet={{ $sortirAlfabet }}">Alfabet</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="mb-5">
            @foreach ($movie['schedules'] as $schedule)
                <div class="w-100 my-3">
                    <div class="d-flex justify-content-between">
                        <div>
                            <i class="fa-solid fa-building"></i><b class="ms-2">{{ $schedule['cinema']['name'] }}</b>
                            <br>
                            <small class="ms-3">{{ $schedule['cinema']['location'] }}</small>
                        </div>

                        <div>
                            <b>Rp. {{ number_format($schedule['price'], 0, ',', '.') }}</b>
                        </div>
                    </div>

                    <div class="d-flex gap-3 ps-3 my2">
                        @foreach ($schedule['hours'] as $index => $hours)
                            <div class="btn btn-outline-secondary"
                                onclick="selectedHour('{{ $schedule->id }}', '{{ $index }}', this)">{{ $hours }}</div>
                        @endforeach
                    </div>
                </div>
            @endforeach
            <hr>


            <div class="w-100 p-2 text-center fixed-bottom" id="wrapper-btn">
                <a href="" id="btn-ticket"><i class="fa-solid fa-ticket"></i>BELI TIKET</a>
            </div>
        @endsection

        @push('script')
            <script>
                let selectedHours = null;
                let selectedSchedule = null;
                let lastClickedElement = null;

                function selectedHour(scheduleId, hourId, el) {
                    //memindahkan data dari parameter ke var luar
                    selectedHours = hourId;
                     //memberikan styling warna ke kotak jam
                    selectedSchedule = scheduleId;
                    //hilangkan warna dari element yang terakhir diklik
                    if (lastClickedElement) {
                        lastClickedElement.style.background = "";
                        lastClickedElement.style.color = "";
                        lastClickedElement.style.borderColor = "";
                    }

                    //beri warna ke element yang baru dikliik
                    el.style.background = "#112646"; //warna biru
                    el.style.color = "white";
                    el.style.borderColor = "#112646";
                    //update lastclickemelement ke el baru
                    lastClickedElement = el;
                    //buat ganti tombol beli tiket
                   let btnWrapper = document.getElementById('wrapper-btn');
                   let btnTicket = document.getElementById('btn-ticket');

                   btnWrapper.style.background = "#112646";
                   btnTicket.style.color = "white";
                   btnWrapper.style.borderColor = "#112646";

                   //set route
                   let url = "{{route('schedules.show_seats', ['scheduleId' => ':schedule', 'hourId' => ':hour'])}}"
                   .replace(':schedule', scheduleId)
                   .replace(':hour', hourId);
                   //replace mengganti schedule dan hour jadi data yang sebenarnya
                   //isi href pada a beli tiket
                   btnTicket.href = url;
                }
            </script>
        @endpush
