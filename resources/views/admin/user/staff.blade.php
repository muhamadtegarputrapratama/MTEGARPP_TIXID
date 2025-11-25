@extends('templates.app')

@section('content')
<div class="container my-4">

    {{-- Notifikasi sukses/error --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>Data Pengguna (Admin & Staff)</h4>
        <a href="{{route('admin.users.export')}}" class="btn btn-secondary me-2"> Export (.xlsx)</a>
        <a href="{{ route('admin.users.create') }}" class="btn btn-success shadow">
            TAMBAH DATA
        </a>
        <a href="{{ route('admin.users.trash') }}" class="btn btn-secondary me-2"> Data Sampah</a>
    </div>

    <table class="table table-bordered align-middle" id="tableUsers">
        <thead class="table-light">
            <tr>
                <th style="width: 5%">No</th>
                <th style="width: 25%">Nama</th>
                <th style="width: 25%">Email</th>
                <th style="width: 15%">Role</th>
                <th style="width: 30%">Aksi</th>
            </tr>
        </thead>
    </table>
</div>
@endsection

@push('script')
<script>
    $(function(){
        $("#tableUsers").DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{route('admin.users.datatables')}}",
            columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
            {data: 'name', name:'name', orderable:true, searchable: true},
            {data: 'email', name:'email', orderable:true, searchable: false},
            {data: 'role', name:'role', orderable:true, searchable: false},
            {data: 'btnActions', name: 'btnActions', orderable: false, searchable: false},
            ]
        })
    })
</script>
@endpush
