@extends('base.base')
@section('title', 'Detail Data Aset')
@section('content')
<div class="row justify-content-center">
    <div class="col-xl-8">
        <div class="card custom-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="card-title mb-0">{{ $listAset->nama }}</h5>
                    <small class="text-muted">Kode: {{ $listAset->kode_aset }}</small>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('aset.edit', $listAset->id) }}" class="btn btn-sm btn-primary">Edit</a>
                    <a href="{{ route('aset.index') }}" class="btn btn-sm btn-secondary">Kembali</a>
                </div>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="text-muted mb-1">Kategori</label>
                        <div class="fw-semibold">{{ $listAset->kategori }}</div>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted mb-1">Lokasi</label>
                        <div class="fw-semibold">{{ $listAset->lokasi }}</div>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted mb-1">Dibuat</label>
                        <div class="fw-semibold">{{ $listAset->created_at?->translatedFormat('d F Y H:i') ?? '-' }}</div>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted mb-1">Diperbarui</label>
                        <div class="fw-semibold">{{ $listAset->updated_at?->translatedFormat('d F Y H:i') ?? '-' }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection