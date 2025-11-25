@extends('templates.app')
@section('content')
@if (Session::get('success'))
    <div class="alert alert-success w-100">{{ Session::get('success') }}<b> selamat datang, {{Auth::user()->name}}</b></div>
@endif
@if (Session::get('logout'))
    <div class="alert alert-warning">{{ Session::get('logout') }}</div>
@endif
<div class="dropdown">
    <button class="btn btn-light dropdown-toggle d-flex align-items-center w-100" type="button" data-bs-toggle="dropdown" aria-expanded="false">
      <i class="fas fa-location-dot me-2"></i>
      Bogor
    </button>
    <ul class="dropdown-menu w-100">
        <li><a class="dropdown-item" href="#">Bogor</a></li>
        <li><a class="dropdown-item" href="#">Jakarta</a></li>
        <li><a class="dropdown-item" href="#">Bandung</a></li>
    </ul>
 </div>

 <!-- Carousel wrapper -->
<div id="carouselBasicExample" class="carousel slide carousel-fade" data-mdb-ride="carousel" data-mdb-carousel-init>
  <!-- Indicators -->
  {{-- <div class="carousel-indicators">
    <button
      type="button"
      data-mdb-target="#carouselBasicExample"
      data-mdb-slide-to="0"
      class="active"
      aria-current="true"
      aria-label="Slide 1"
    ></button>
    <button
      type="button"
      data-mdb-target="#carouselBasicExample"
      data-mdb-slide-to="1"
      aria-label="Slide 2"
    ></button>
    <button
      type="button"
      data-mdb-target="#carouselBasicExample"
      data-mdb-slide-to="2"
      aria-label="Slide 3"
    ></button>
  </div> --}}

  <!-- Inner -->
  <div class="carousel-inner">
    <!-- Single item -->
    <div class="carousel-item active">
      <img src="https://asset.tix.id/microsite_v2/d2b394a8-caae-4e0b-b455-7fdb2139ec29.webp" class="d-block w-100" alt="Sunset Over the City"/>
      <div class="carousel-caption d-none d-md-block">

      </div>
    </div>

    <!-- Single item -->
    <div class="carousel-item">
      <img src="https://asset.tix.id/microsite_v2/2e71513b-00b2-4c2f-ae86-e62abb3dd24e.webp" class="d-block w-100" alt="Canyon at Nigh"/>
      <div class="carousel-caption d-none d-md-block">

      </div>
    </div>

    <!-- Single item -->
    <div class="carousel-item">
      <img src="https://asset.tix.id/microsite_v2/4a4a17ec-db5c-4513-a9a8-2a1d64ec0150.webp" class="d-block w-100" alt="Cliff Above a Stormy Sea"/>
      <div class="carousel-caption d-none d-md-block">

      </div>
    </div>
  </div>
  <!-- Inner -->

  <!-- Controls -->
  <button class="carousel-control-prev" type="button" data-mdb-target="#carouselBasicExample" data-mdb-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Previous</span>
  </button>
  <button class="carousel-control-next" type="button" data-mdb-target="#carouselBasicExample" data-mdb-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Next</span>
  </button>
</div>
<!-- Carousel wrapper -->
<div class="container my-3">
    <div class="d-flex justify-content-between align-items-center w-100">
        <div class="mt-3">
            <h5>
                <i class="fa-solid fa-clapperboard"></i> Sedang Tayang
            </h5>
        </div>
        <div>
            <a href="{{route('home.movies.active')}}" class ="btn btn-warning rounded-pill">Semua</a>
        </div>
    </div>
    <div class="d-flex my-3 gap-2">
        <a href="{{route('home.movies.active')}}" class="btn btn-outline-primary rounded-pill"
        style="padding: 5px 10px !important"><small>Semua Film</small></a>
        <a href="" class="btn btn-outline-primary rounded-pill"
        style="padding: 5px 10px !important"><small>XXI</small></a>
        <a href="" class="btn btn-outline-primary rounded-pill"
        style="padding: 5px 10px !important"><small>CGV</small></a>
        <a href="" class="btn btn-outline-primary rounded-pill"
        style="padding: 5px 10px !important"><small>Cinepolis</small></a>
    </div>

    <div class="d-flex justify-content-center gap-2 my-3">
    @foreach ($movies as $movie)
        <div class="card" style="width: 13rem">
            <img src="{{asset('storage/' . $movie->poster)}}"
            class="card-img-top" alt="{{$movie->title}}"  style="height: 300px; object-fit: cover,">
            <div class="card-body" style="padding: 0 !important">
                <p class="card-text text-center bg-primary py-2"><a href="{{ route('schedules.detail', $movie->id)}}"
                    class="text-warning"><b>BELI TIKET</b></a></p>
            </div>
        </div>
        @endforeach
    </div>
</div>
</div>
<footer class="bg-body-tertiary text-center text-lg-start mt-5">
    <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.05); max-width: 100%">
      © 2025 Copyright:
      <a class="text-body" href="https://tix.id/">TixID</a>
    </div>
</footer>
@endsection
