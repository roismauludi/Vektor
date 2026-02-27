@extends('base.base')
@section('title', 'Tower - Central')
@section('content')

<div class="row">
    <div class="col-xl-12">
        <div class="card custom-card">
            <div class="card-header d-flex justify-content-between align-items-center mb-3">
                <h5 class="card-title mb-0">Data Aset</h5>
                <div class="d-flex gap-2">
                    @can('view-pengajuan')
                    <a href="{{ route('pengajuan.list') }}" class="btn btn-outline-primary btn-wave">Peminjaman</a>
                    @endcan
                    @can('create-aset')
                    <a href="{{ route('aset.create') }}" class="btn btn-primary btn-wave">Tambah Aset</a>
                    @endcan
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert" id="flashMessage">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>

                <script>
                    setTimeout(function() {
                        var alert = document.getElementById('flashMessage');
                        if (alert) {
                            var bsAlert = new bootstrap.Alert(alert);
                            bsAlert.close();
                        }
                    }, 3000);
                </script>
            @endif

            <div class="card-body">
                <div class="table-responsive">
                    <table id="file-export" class="table table-bordered text-nowrap w-100">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode Asset</th>
                                <th>Kategori</th>
                                <th>Nama Aset</th>
                                <th>Lokasi</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($listAsets as $index => $asset)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $asset->kode_aset }}</td>
                                <td>{{ $asset->kategori }}</td>
                                <td>{{ $asset->nama }}</td>
                                <td>{{ $asset->lokasi }}</td>
                                <td>
                                    @can('detail-aset')
                                    <a class="btn btn-sm btn-primary" href="{{ route('aset.detail', $asset->id) }}">Detail</a>
                                    @endcan
                                </td>
                            </tr>
                            @endforeach
                        </tbody>                      
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.bootstrap5.min.css">

<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap5.min.js"></script>

<script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.print.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.6/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>

@endsection
