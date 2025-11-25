@extends('templates.app')

@section('content')
<div class="w-75 d-block mx-auto my-5 p-4">
    <h5 class="text-center my-3">Ubah Data Staff</h5>
    <form method="POST" action="{{ route('admin.users.update', $user->id) }}">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="first_name" class="form-label">Nama Depan :</label>
            <input type="text" class="form-control @error('first_name') is-invalid @enderror"
                   id="first_name" name="first_name"
                   value="{{ old('first_name', explode(' ', $user->name)[0] ?? '') }}">
            @error('first_name') <small class="text-danger">{{$message}}</small> @enderror
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email :</label>
            <input type="email" class="form-control @error('email') is-invalid @enderror"
                   id="email" name="email" value="{{ old('email', $user->email) }}">
            @error('email') <small class="text-danger">{{$message}}</small> @enderror
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Password :</label>
            <input type="password" class="form-control @error('password') is-invalid @enderror"
                   id="password" name="password">
            @error('password') <small class="text-danger">{{$message}}</small> @enderror
            <small class="text-muted">Kosongkan jika tidak ingin mengubah password</small>
        </div>

        <div class="mb-3">
            <label for="role" class="form-label">Role :</label>
            <select name="role" id="role" class="form-select @error('role') is-invalid @enderror">
                <option value="admin" {{ old('role', $user->role)=='admin' ? 'selected' : '' }}>Admin</option>
                <option value="staff" {{ old('role', $user->role)=='staff' ? 'selected' : '' }}>Staff</option>
            </select>
            @error('role') <small class="text-danger">{{$message}}</small> @enderror
        </div>

        <button type="submit" class="btn btn-primary w-100">Simpan Perubahan</button>
    </form>
</div>
@endsection
