@extends('templates.app')

@section('content')
    <div class="container my-5 card">
        @foreach ($schedules as $schedule)
            <div class="card-body">
                <i class="fa-solid fa-location-dot me-2"></i>
                <span>{{ $schedule['cinema']['location'] }}</span>
            </div>
            <hr>
            <div class="my-2">
                <div class="d-flex">
                    <div style="width: 150px; height: 200px"> <img
                            src= "{{ asset('storage/' . $schedule['movie']['poster']) }}" class="w-100"> </div>
                    <div class="ms-5 mt-4">
                        <h5>{{ $schedule['movie']['title'] }}</h5>
                        <table>
                            <tr>
                                <td><b class="text-secondary">Genre</b></td>
                                <td class="px-3"></td>
                                <td>{{ $schedule['movie']['genre'] }}</td>
                            </tr>
                            <tr>
                                <td><b class="text-secondary">Durasi</b></td>
                                <td class="px-3"></td>
                                <td>{{ $schedule['movie']['duration'] }}</td>
                            </tr>
                            <tr>
                                <td><b class="text-secondary">Sutradara</b></td>
                                <td class="px-3"></td>
                                <td>{{ $schedule['movie']['director'] }}</td>
                            </tr>
                            <tr>
                                <td><b class="text-secondary">Rating Usia</b></td>
                                <td class="px-3">:</td>
                                <td>
                                    <span class="badge" style="background-color: #f8d7da; color: #a71d2a;">
                                        {{ $schedule['movie']['age_rating'] }}
                                    </span>
                                </td>
                            </tr>

                        </table>
                    </div>
                </div>

                <!-- Harga -->
                <div class="d-flex justify-content-end mt-3">
                    <h6 class="fw-bold">Rp. {{ number_format($schedule['price'], 0, ',', '.') }}</h6>
                </div>

                <!-- Jam Tayang -->
                <div class="d-flex gap-3 mt-3 flex-wrap">
                    @foreach ($schedule['hours'] as $index => $hours)
                        <button class="btn btn-outline-secondary rounded-3 px-3"
                            onclick="selectedHour('{{ $schedule->id }}', '{{ $index }}', this)">
                            {{ $hours }}
                        </button>
                    @endforeach
                </div>
            </div>
    </div>
    @endforeach
    </div>


    <div class="w-100 p-3 text-center fixed-bottom" id="wrapper-btn" style="background:#f8f9fa; transition:0.3s;">
        <a href="#" id="btn-ticket" class="fw-semibold text-decoration-none" style="color:#2e7dcd; transition:0.3s;">
            <i class="fa-solid fa-ticket me-2"></i>BELI TIKET
        </a>
    </div>
@endsection

@push('script')
    <script>
        let selectedHours = null;
        let selectedSchedule = null;
        let lastClickedElement = null;

        function selectedHour(scheduleId, hourId, el) {
            selectedHours = hourId;
            selectedSchedule = scheduleId;

            // Reset warna sebelumnya
            if (lastClickedElement) {
                lastClickedElement.style.background = "";
                lastClickedElement.style.color = "";
                lastClickedElement.style.borderColor = "";
            }

            // Ubah warna tombol jam yang dipilih
            el.style.background = "#112646";
            el.style.color = "white";
            el.style.borderColor = "#112646";
            lastClickedElement = el;

            // Tombol beli tiket
            const btnWrapper = document.getElementById('wrapper-btn');
            const btnTicket = document.getElementById('btn-ticket');

            btnWrapper.style.background = "#112646";
            btnWrapper.style.borderColor = "#112646";

            // pakai !important agar menang lawan CSS Bootstrap
            btnTicket.style.setProperty("color", "white", "important");

            // set URL tujuan
            let url = "{{ route('schedules.show_seats', ['scheduleId' => ':schedule', 'hourId' => ':hour']) }}"
                .replace(':schedule', scheduleId)
                .replace(':hour', hourId);

            btnTicket.href = url;
        }
    </script>
@endpush
