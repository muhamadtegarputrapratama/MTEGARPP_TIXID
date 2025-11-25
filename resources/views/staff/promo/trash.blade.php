@extends('templates.app')

@section('content')
    <div class="container my-3">
        <div class="d-flex justify-content-end">
            <a href="{{ route('staff.dashboard') }}" class="btn btn-secondary">Kembali</a>
        </div>
        <h3 class="my-3">Data Promo</h3>
        @if (Session::get('success'))
            <div class="alert alert-success">{{ Session::get('success') }}</div>
        @endif
         @if (Session::get('error'))
            <div class="alert alert-danger">
                {{ Session::get('error') }}
            </div>
            @endif
        <table class="table table-bordered">
            <tr>
                <th>#</th>
                <th>Nama Bioskop</th>
                <th>Judul Film</th>
                <th>Aksi</th>
            </tr>
            @foreach ($promoTrash as $key => $promos)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $promo['cinema']['name'] ?? ''}}</td>
                    <td>{{ $promo['movie']['title'] ?? ''}}</td>
                    <td class="d-flex">
                        <form action="{{route('staff.promo.restore', $promos->id)}}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button class="btn btn-secondary ms-2">Kembalikan</button>
                        </form>
                           <form action="{{route('staff.promo.delete_permanent', $promos->id)}}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger ms-2">Hapus Permanen</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </table>
    </div>
    @endsection
