@extends('templates.app')

@section('content')
<div class="container">
    <h3>Daftar Promo</h3>
    <a href="{{route ('staff.promo.export')}}" class="btn btn-secondary me-2">Export (.xlsx)</a>
    <a href="{{ route('staff.promo.create') }}" class="btn btn-primary mb-3">Tambah Promo</a>
    <a href="{{ route('staff.promo.trash') }}" class="btn btn-secondary mb-3">Data Sampah</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered" id="tablePromo">
        <thead>
            <tr>
                <th>no</th>
                <th>Nama Diskon</th>
                <th>Diskon</th>
                <th>Type</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>

        </tbody>
    </table>

    {{ $promos->links() }}
</div>
@endsection

@push('script')
<script>
 $(function() {
    $("#tablePromo").DataTable({
       processing: true,
       serverSide: true,
       ajax: "{{route('staff.promo.datatables')}}",
       columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
            { data: 'promo_code', name: 'promo_code', orderable: true, searchable: true},
            {data: 'discount', name: 'discount', orderable: true, searchable: true},
            {data: 'type', name: 'type', orderable: true, searchable: true},
            {data: 'btnActions', name: 'btnActions', orderable: false, searchable: false}
       ]
    })
 })
</script>
@endpush
