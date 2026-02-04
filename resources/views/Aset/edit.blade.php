@extends('base.base')
@section('title', 'Update Data Aset')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card custom-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="card-title mb-0">Edit Aset</h5>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('aset.update', $listAset->id) }}" method="POST" class="needs-validation" novalidate>
                    @csrf
                    @method('PUT')
                    
                    <input type="hidden" name="id" value="{{ $listAset->id }}">
                    <div class="row g-4">
                        <div class="col-md-6" >
                            <label for="kode_aset" class="form-label">Kode Aset</label>
                            <input type="text" class="form-control bg-light text-dark soft-readonly" name="kode_aset" id="kode_aset" class="form-control @error('kode_aset') is-invalid @enderror" value="{{ old('kode_aset', $listAset->kode_aset) }}"readonly required>
                        </div>
                        <div class="col-md-6">
                            <label for="nama" class="form-label">Nama</label>
                            <input type="text" name="nama" id="nama" class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama', $listAset->nama) }}" placeholder="Nama Aset" required>
                            @error('nama')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="kategori" class="form-label">Kategori</label>
                            <select name="kategori" id="kategori" class="form-select @error('kategori') is-invalid @enderror" required>
                                <option value="" disabled>-- Pilih Kategori --</option>
                                <option value="Tower" {{ old('kategori', $listAset->kategori) == 'Tower' ? 'selected' : '' }}>Tower</option>
                                <option value="Central" {{ old('kategori', $listAset->kategori) == 'Central' ? 'selected' : '' }}>Central</option>
                            </select>
                            @error('kategori')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="lokasi" class="form-label">Lokasi</label>
                            <input type="text" name="lokasi" id="lokasi" class="form-control @error('lokasi') is-invalid @enderror" value="{{ old('lokasi', $listAset->lokasi) }}" placeholder="Lokasi Aset" required>
                            @error('lokasi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <a href="{{ route('aset.detail', $listAset->id) }}" class="btn btn-light">Batal</a>
                        <button type="submit" class="btn btn-primary btn-wave">Update Aset</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection