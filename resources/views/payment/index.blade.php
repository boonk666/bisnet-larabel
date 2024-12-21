@extends('layouts.app')

@section('content')
    <h1 class="h3 mb-4 text-gray-800">Data Pembayaran</h1>

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Data Pembayaran</h6>
            <div class="d-flex">
                <select id="filter-month" class="form-control form-control-sm mr-2">
                    @php
                        $currentMonth = now()->month   
                    @endphp
                    <option value="1" {{ $currentMonth == 1 ? 'selected' : '' }}>Januari</option>
                    <option value="2" {{ $currentMonth == 2 ? 'selected' : '' }}>Februari</option>
                    <option value="3" {{ $currentMonth == 3 ? 'selected' : '' }}>Maret</option>
                    <option value="4" {{ $currentMonth == 4 ? 'selected' : '' }}>April</option>
                    <option value="5" {{ $currentMonth == 5 ? 'selected' : '' }}>Mei</option>
                    <option value="6" {{ $currentMonth == 6 ? 'selected' : '' }}>Juni</option>
                    <option value="7" {{ $currentMonth == 7 ? 'selected' : '' }}>Juli</option>
                    <option value="8" {{ $currentMonth == 8 ? 'selected' : '' }}>Agustus</option>
                    <option value="9" {{ $currentMonth == 9 ? 'selected' : '' }}>September</option>
                    <option value="10" {{ $currentMonth == 10 ? 'selected' : '' }}>Oktober</option>
                    <option value="11" {{ $currentMonth == 11 ? 'selected' : '' }}>November</option>
                    <option value="12" {{ $currentMonth == 12 ? 'selected' : '' }}>Desember</option>
                </select>
                <select class="flex-shrink-0 form-control form-control-sm" style="width: fit-content" id="filter-is-paid">
                    <option value="1" selected>Sudah Bayar</option>
                    <option value="0">Belum Bayar</option>
                </select>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="example" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Nama Pelanggan</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('style')
    <link href="{{ asset('sbadmin/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endsection

@section('script')
    <script src="{{ asset('sbadmin/vendor/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('sbadmin/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>

    <script>
        const table = $('#example').DataTable({
            ajax: {
                url: '{{ route('api.payments.per-customer-for-current-month') }}',
                data: query => {
                    query.is_paid = $('#filter-is-paid').val()
                    query.month = $('#filter-month').val()
                } 
            },
            processing: true,
            serverSide: true,
            columns: [
                {
                    data: 'created_at',
                    render: (data, type, row) => {
                        if (!row.payments.length) {
                            return '-'
                        }

                        return new Date(row.payments[0].updated_at).toLocaleString()
                    }
                },
                { data: 'nama' },
            ]
        })
        $('#filter-is-paid').on('change', (e) => {
            table.ajax.reload()
        })
        $('#filter-month').on('change', (e) => {
            table.ajax.reload()
        })
    </script>
@endsection