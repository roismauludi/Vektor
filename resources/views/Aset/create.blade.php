@extends('base.base')
@section('title', 'Tambah Data Aset')
@section('content')
<div class="row justify-content-center">
    <div class="col-xl-8">
        <div class="card custom-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Form Tambah Aset</h5>
                <a href="{{ route('aset.index') }}" class="btn btn-sm btn-secondary">Kembali</a>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('aset.store') }}">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Kode Aset</label>
                            <input type="text" class="form-control" value="{{ $nextKode ?? 'AL-0001' }}" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Kategori</label>
                            <select name="kategori" id="kategori" class="form-select @error('kategori') is-invalid @enderror">
                                <option value="" disabled {{ old('kategori') ? '' : 'selected' }}>Pilih Kategori</option>
                                <option value="Tower" {{ old('kategori') === 'Tower' ? 'selected' : '' }}>Tower</option>
                                <option value="Central" {{ old('kategori') === 'Central' ? 'selected' : '' }}>Central</option>
                                <option value="Lainnya" {{ old('kategori') === 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                            </select>
                            @error('kategori')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 d-none" id="kategori_lainnya_wrap">
                            <label class="form-label">Kategori Aset (Lainnya)</label>
                            <input type="text"
                                   name="kategori_lainnya"
                                   id="kategori_lainnya"
                                   value="{{ old('kategori_lainnya') }}"
                                   class="form-control @error('kategori_lainnya') is-invalid @enderror"
                                   placeholder="Contoh: Router / Switch / dll">
                            @error('kategori_lainnya')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6" id="nama_default_wrap">
                            <label class="form-label">Nama Aset</label>
                            <input type="text" name="nama" value="{{ old('nama') }}" class="form-control @error('nama') is-invalid @enderror" placeholder="Nama Aset">
                            @error('nama')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Lokasi</label>
                            <input type="text" name="lokasi" value="{{ old('lokasi') }}" class="form-control @error('lokasi') is-invalid @enderror" placeholder="Lokasi Aset">
                            @error('lokasi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <button type="reset" class="btn btn-light">Reset</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    (function () {
        const kategori = document.getElementById('kategori');
        const kategoriLainnyaWrap = document.getElementById('kategori_lainnya_wrap');
        const kategoriLainnya = document.getElementById('kategori_lainnya');

        function syncLainnyaUI() {
            const isLainnya = kategori && kategori.value === 'Lainnya';

            if (kategoriLainnyaWrap) kategoriLainnyaWrap.classList.toggle('d-none', !isLainnya);
            if (kategoriLainnya) kategoriLainnya.required = isLainnya;
        }

        if (kategori) {
            kategori.addEventListener('change', syncLainnyaUI);
            syncLainnyaUI(); // initial (buat kasus old() saat validation error)
        }
    })();
</script>
@endsection