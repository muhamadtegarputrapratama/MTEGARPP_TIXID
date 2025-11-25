@extends('templates.app')

@section('content')
<div class="w-75 d-block mx-auto my-5">

    <form action="{{ route('staff.promo.store') }}" method="POST">
        @csrf
        <div class="row mb-3">
            <div class="col-6">
                <label for="promo_code" class="form-label">Kode Promo</label>
                <input type="text" name="promo_code" id="promo_code" value="{{ old('promo_code') }}"
                    class="form-control @error('promo_code') is-invalid @enderror">
                @error('promo_code')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="col-6">
                <label for="type" class="form-label">Tipe Promo</label>
                <select name="type" id="type" class="form-control @error('type') is-invalid @enderror">
                    <option value="">-- Pilih --</option>
                    <option value="percent" {{ old('type') == 'percent' ? 'selected' : '' }}>Percent</option>
                    <option value="rupiah" {{ old('type') == 'rupiah' ? 'selected' : '' }}>Rupiah</option>
                </select>
                @error('type')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-6">
                <label for="discount" class="form-label">Discount</label>
                <input type="number" name="discount" id="discount" value="{{ old('discount') }}"
                    class="form-control @error('discount') is-invalid @enderror">
                @error('discount')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
        </div>


        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <button type="submit" class="btn btn-primary">Simpan</button>
    </form>
</div>
@endsection
