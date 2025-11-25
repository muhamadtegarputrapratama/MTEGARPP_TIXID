@extends('templates.app')

@section('content')
<div class="container">
    <h3>Edit Promo</h3>
    <form action="{{ route('staff.promo.update', $promo->id) }}" method="POST">
        @csrf @method('PUT')

        <div class="mb-3">
            <label>Kode Promo</label>
            <input type="text" name="promo_code" class="form-control" value="{{ old('promo_code', $promo->promo_code) }}">
        </div>

        <div class="mb-3">
            <label>Tipe Promo</label>
            <select name="type" class="form-control">
                <option value="percent" {{ $promo->type=='percent'?'selected':'' }}>%</option>
                <option value="rupiah" {{ $promo->type=='rupiah'?'selected':'' }}>Rupiah</option>
            </select>
        </div>

        <div class="mb-3">
            <label>Jumlah Potongan</label>
            <input type="number" name="discount" class="form-control" value="{{ old('discount', $promo->discount) }}">
        </div>

        <button type="submit" class="btn btn-success">Update</button>
    </form>
</div>
@endsection
