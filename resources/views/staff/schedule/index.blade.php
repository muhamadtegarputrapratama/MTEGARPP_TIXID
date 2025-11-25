@extends('templates.app')

@section('content')
<div class="container my-3">
    <div class="d-flex justify-content-end">
        <a href="{{route('staff.staff.schedules.trash')}}" class="btn btn-secondary me-2">Data Sampah</a>
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalAdd">Tambah Data</button>
         <a href="{{route('staff.staff.schedules.export')}}" class="btn btn-secondary me-2">Export (.xlsx)</a>
    </div>
</div>
<h3 class="my-3">Data Jadwal Tayangan</h3>
@if (Session::get('success'))
      <div class="alert alert-success">{{Session::get('success')}}</div>
@endif
<table class="table table-bordered" id="tableSchedule">
    <thead>
        <tr>
        <th>#</th>
        <th>Nama Bioskop</th>
        <th>Judul Film</th>
        <th>Jam Tayang</th>
        <th>Harga</th>
        <th>Aksi</th>
        </tr>
    </thead>
</table>

<div class="modal fade" id="modalAdd" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Tambah Data</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
          <form method="POST" action="{{ route('staff.staff.schedules.store') }}">
            @csrf
      <div class="modal-body">
          <div class="mb-3">
            <label for="cinema_id" class="col-form-label">Bioskop:</label>
            <select name="cinema_id" class="form-select @error('cinema_id') is-invalid @enderror">
                <option disabled hidden selected>Pilih Bioskop</option>
                @foreach ($cinemas as $cinema )
                   <option value="{{$cinema['id']}}">{{$cinema['name']}}</option>
                @endforeach
            </select>
            @error('cinema_id')
             <small class="text-danger">{{$message}}</small>
            @enderror
          </div>

          <div class="mb-3">
            <label for="movie_id" class="col-form-label">Film:</label>
            <select name="movie_id" id="movie_id" class="form-select @error('movie_id') is-invalid @enderror">
                <option disabled hidden selected>Pilih Film</option>
                @foreach ($movies as $movie)
                <option value="{{$movie['id']}}">{{$movie['title']}}</option>
                @endforeach
            </select>
            @error('movie_id')
             <small class="text-danger">{{$message}}</small>
            @enderror
          </div>
            <div class="mb-3">
                <label for="price" class="col-form-label">Harga:</label>
                <input type="number" name="price" id="price" class="form-control @error('price') is-invalid @enderror">
                @error('price')
                 <small class="text-danger">{{$message}}</small>
                @enderror
            </div>
            <div class="mb-3">
                @if ($errors->has('hours.*'))
                    <small class="text-danger">{{$errors->first('hours.*')}}</small>
                @endif
                <label for="hours" class="form-label">Jam Tayang:</label>
                <input type="time" name="hours[]" id="hours" class="form-control @if ($errors->has('hours.*')) is-invalid @endif">
                <div id="addtionalInput"></div>
                <span class="text-primary mt-3" style="cursor: pointer" onclick="addInput()">+Tambah Input</span>
            </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-primary">Kirim</button>
      </div>
    </form>
    </div>
  </div>
</div>
@endsection

@push('script')
<script>
    $(function() {
        $("#tableSchedule").DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{route('staff.staff.schedules.datatables')}}",
            columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
            {data: 'cinema.name', name:'cinema.name', orderable:true, searchable: true},
            {data: 'movie.title', name:'movie.title', orderable:true, searchable: false},
            {data: 'hours', name:'hours', orderable:true, searchable: false},
            {data: 'price', name:'price', orderable:true, searchable: false},
            {data: 'btnActions', name: 'btnActions', orderable: false, searchable: false}
            ]
        })
    })
    function addInput() {
        let content = `<input type="time" name="hours[]" class="form-control mt-3">`;
        //panggil bagian yg akan diiisi
        let wadah = document.querySelector("#addtionalInput");
        //tambahkan konten, karna akan terus bertambah gunakan +=
        wadah.innerHTML += content;
    }
    </script>
   @if ($errors->any())
   <script>
   let modalAdd = document.querySelector("#modalAdd");
   new bootstrap.Modal(modalAdd).show();
    </script>
    @endif
@endpush
