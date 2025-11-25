@extends ('templates.app')
@section('content')
<div class="container pt-5">
    <div class="w-75 d-block m-auto">
        <div class="d-flex">
            <div style="width: 150px; height: 200px">
                <img src= "https://asset.tix.id/wp-content/uploads/2025/08/98b61540-970e-45d2-bb6e-5574dcb71ee4.webp" class="w-100">
            </div>
            <div class="ms-5 mt-4">
                <h5>Labinak</h5>
                <table>
                    <tr>
                        <td><b class="text-secondary">Genre</b></td>
                        <td class="px-3"></td>
                        <td>Horror, Misteri, Thriller, Drama</td>
                    </tr>
                    <tr>
                        <td><b class="text-secondary">Durasi</b></td>
                        <td class="px-3"></td>
                        <td>1 Jam 40 Menit</td>
                    </tr>
                    <tr>
                        <td><b class="text-secondary">Sutradara</b></td>
                        <td class="px-3"></td>
                        <td>Azhar Kinoi Lubis</td>
                    </tr>
                    <tr>
                        <td><b class="text-secondary">Rating usia</b></td>
                        <td class="px-3"></td>
                        <td><span class="badge badge-danger">17+</span>
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
                <button class="btn btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    Bioskop
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="#">Bogor</a></li>
                    <li><a class="dropdown-item" href="#">Jakarta Timur</a></li>
                    <li><a class="dropdown-item" href="#">Jakarta Barat</a></li
                </ul>
            </div>
             <div class="dropwdown">
                <button class="btn btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    Sortir
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="#">Harga</a></li>
                    <li><a class="dropdown-item" href="#">Alfabet</a></li>
                </ul>
            </div>
        </div>
    </div>
<div class="mb-5">
    <div class="w-100 my-3">
        <i class="fa-solid fa-building"></i><b class="ms-2">LippoPlaza Ekalokasari</b>
        <br>
        <small class="ms-3">JL. Pajajaran No. 1, Bogor Tengah, Kec. Bogor Tengah, Kota Bogor, Jawa Barat 16143</small>
    <div class="d-flex gap-3 ps-3 my2">
        <div class="btn btn-outline-secondary">10:00</div>
        <div class="btn btn-outline-secondary">12:30</div>
        <div class="btn btn-outline-secondary">15:00</div>
        <div class="btn btn-outline-secondary">17:30</div>
        <div class="btn btn-outline-secondary">20:00</div>
</div>
    </div>
    <div class="w-100 my-3">
        <i class="fa-solid fa-building"></i><b class="ms-2">Ramayana Tajur</b>
        <br>
        <small class="ms-3">JL. Pajajaran No. 1, Bogor Tengah, Kec. Bogor Tengah, Kota Bogor, Jawa Barat 16143</small>
    <div class="d-flex gap-3 ps-3 my2">
        <div class="btn btn-outline-secondary">10:00</div>
        <div class="btn btn-outline-secondary">12:30</div>
        <div class="btn btn-outline-secondary">15:00</div>
        <div class="btn btn-outline-secondary">17:30</div>
        <div class="btn btn-outline-secondary">20:00</div>
</div>
    </div>
    <div class="w-100 p-2 bg-light text-center fixed-bottom">
        <a href=""><i class="fa-solid fa-ticket"></i>BELI TIKET</a>
    </div>

@endsection

