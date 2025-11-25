@extends('templates.app')

@section('content')
<div class="w-75 d-block mx-auto my-5 p-4">
    <div class="card">
        <div class="m-3 form-label">Pengguna / Data / Tambah</div>
    </div>
     <div class="card mt-4">
     <div class="card-body">
    <h5 class="text-center my-3">Buat Data Staff</h5>
    <form method="POST" action="{{ route('admin.users.store') }}">
        @csrf

        <div class="mb-3">
            <label for="first_name" class="form-label">Nama Depan :</label>
            <input type="text" class="form-control @error('first_name') is-invalid @enderror"
                   id="first_name" name="first_name" value="{{ old('first_name') }}">
            @error('first_name') <small class="text-danger">{{$message}}</small> @enderror
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email :</label>
            <input type="email" class="form-control @error('email') is-invalid @enderror"
                   id="email" name="email" value="{{ old('email') }}">
            @error('email') <small class="text-danger">{{$message}}</small> @enderror
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Password :</label>
            <input type="password" class="form-control @error('password') is-invalid @enderror"
                   id="password" name="password">
            @error('password') <small class="text-danger">{{$message}}</small> @enderror
        </div>

        <div class="mb-3">
            <label for="role" class="form-label">Role :</label>
            <select name="role" id="role" class="form-control @error('role') is-invalid @enderror">
                <option value="">-- Pilih Role --</option>
                <option value="admin" {{ old('role')=='admin' ? 'selected' : '' }}>Admin</option>
                <option value="staff" {{ old('role')=='staff' ? 'selected' : '' }}>Staff</option>
            </select>
            @error('role') <small class="text-danger">{{$message}}</small> @enderror
        </div>

        <button type="submit" class="btn btn-primary w-100">Tambah Data</button>
    </form>
    </div>
     </div>
</div>
@endsection
