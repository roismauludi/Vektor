@extends('base.base')
@section('title', 'Form Pengajuan Central')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card custom-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="card-title mb-0">Form Pengajuan Central</h5>
                </div>
                <a href="{{ route('pengajuan.list') }}" class="btn btn-sm btn-secondary">Kembali</a>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('pengajuan.store') }}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="jenis" value="central">
                    <input type="hidden" name="status" value="{{ old('status', 'open') }}">
                    <input type="hidden" name="progress" value="{{ old('progress', 'Menunggu verifikasi') }}">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Kode Pengajuan</label>
                            <input type="text" readonly name="kode_pengajuan" value="{{ old('kode_pengajuan', $kodePengajuanDefault) }}" class="form-control @error('kode_pengajuan') is-invalid @enderror" placeholder="PMT-25-000001">
                            @error('kode_pengajuan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Central</label>
                            <select name="central" class="form-select @error('central') is-invalid @enderror">
                                <option value="">Pilih central</option>
                                @foreach($listAsets as $aset)
                                    <option value="{{ $aset->kode_aset }}" {{ old('central') == $aset->kode_aset ? 'selected' : '' }}>
                                        {{ $aset->nama }} @if($aset->lokasi) - {{ $aset->lokasi }} @endif
                                    </option>
                                @endforeach
                            </select>
                            @error('central')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Perusahaan Tamu</label>
                            <input type="text" name="perusahaan_tamu" value="{{ old('perusahaan_tamu') }}" class="form-control @error('perusahaan_tamu') is-invalid @enderror" placeholder="Nama perusahaan tamu">
                            @error('perusahaan_tamu')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Perusahaan Pengutus</label>
                            <input type="text" name="perusahaan_pengutus" value="{{ old('perusahaan_pengutus') }}" class="form-control @error('perusahaan_pengutus') is-invalid @enderror" placeholder="Perusahaan pengutus">
                            @error('perusahaan_pengutus')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Jumlah Orang</label>
                            <input type="text" name="jumlah" value="{{ old('jumlah') }}" class="form-control @error('jumlah') is-invalid @enderror" placeholder="Contoh: 3 orang">
                            @error('jumlah')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Jam Mulai</label>
                            <input type="time" name="jam_mulai" value="{{ old('jam_mulai') }}" class="form-control @error('jam_mulai') is-invalid @enderror">
                            @error('jam_mulai')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Jam Selesai <small class="text-muted">(maks 17:00)</small></label>
                            <input type="time" name="jam_selesai" max="17:00" value="{{ old('jam_selesai') }}" class="form-control @error('jam_selesai') is-invalid @enderror">
                            @error('jam_selesai')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label class="form-label">Kategori</label>
                            <select name="kategori" class="form-control @error('kategori') is-invalid @enderror">
                                <option value="">-- Pilih Kategori --</option>
                                <option value="Dismantel" {{ old('keterangan') == 'Dismantel' ? 'selected' : '' }}>Dismantel</option>
                                <option value="Maintenance" {{ old('keterangan') == 'Maintenance' ? 'selected' : '' }}>Maintenance</option>
                                <option value="Perbaikan" {{ old('keterangan') == 'Perbaikan' ? 'selected' : '' }}>Perbaikan</option>
                                <option value="Pengecekan" {{ old('keterangan') == 'Pengecekan' ? 'selected' : '' }}>Pengecekan</option>
                            </select>

                            @error('kategori')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label class="form-label">Keperluan</label>
                            <textarea name="keperluan" rows="3" class="form-control @error('keperluan') is-invalid @enderror" placeholder="Jelaskan tujuan kunjungan">{{ old('keperluan') }}</textarea>
                            @error('keperluan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Identitas Yang Ditinggalkan</label>
                            <select name="identitas" class="form-select @error('identitas') is-invalid @enderror">
                                <option value="">Pilih identitas</option>
                                <option value="KTP" {{ old('identitas') === 'KTP' ? 'selected' : '' }}>KTP</option>
                                <option value="SIM" {{ old('identitas') === 'SIM' ? 'selected' : '' }}>SIM</option>
                            </select>
                            @error('identitas')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Upload Identitas</label>
                            <input type="file" name="identitas_file" class="form-control @error('identitas_file') is-invalid @enderror">
                            @error('identitas_file')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Masa Berlaku Identitas</label>
                            <input type="text" name="masa_berlaku" value="{{ old('masa_berlaku') }}" class="form-control @error('masa_berlaku') is-invalid @enderror" placeholder="Contoh: s.d 05/2027">
                            @error('masa_berlaku')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <hr class="my-4">

                    <div>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6 class="mb-0">Detail Tamu</h6>
                            <button type="button" class="btn btn-sm btn-outline-primary" id="addDetailBtn">Tambah Tamu</button>
                        </div>
                    </div>

                    <div id="detailWrapper" class="mt-3">
                        @php
                            $oldDetails = old('details', [
                                ['nama_tamu' => '', 'no_tlp' => '', 'email' => ''],
                            ]);
                        @endphp

                        @foreach($oldDetails as $index => $detail)
                            <div class="detail-item border rounded p-3 mb-3">
                                <div class="row g-3 align-items-end">
                                    <div class="col-md-4">
                                        <label class="form-label">Nama Tamu</label>
                                        <input type="text" name="details[{{ $index }}][nama_tamu]" value="{{ $detail['nama_tamu'] ?? '' }}" class="form-control" placeholder="Nama lengkap">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">No. Telepon</label>
                                        <input type="text" name="details[{{ $index }}][no_tlp]" value="{{ $detail['no_tlp'] ?? '' }}" class="form-control" placeholder="0812xxxx">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Email</label>
                                        <input type="email" name="details[{{ $index }}][email]" value="{{ $detail['email'] ?? '' }}" class="form-control" placeholder="mail@example.com">
                                    </div>
                                    <div class="col-md-1 text-end">
                                        <button type="button" class="btn btn-outline-danger btn-sm removeDetailBtn" {{ $loop->first ? 'disabled' : '' }}>
                                            <i class="bi bi-x-lg"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <button type="reset" class="btn btn-light">Reset</button>
                        <button type="submit" class="btn btn-primary">Ajukan Central</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    (function () {
        const detailWrapper = document.getElementById('detailWrapper');
        const addDetailBtn = document.getElementById('addDetailBtn');

        if (!detailWrapper || !addDetailBtn) return;

        addDetailBtn.addEventListener('click', function () {
            const currentIndex = detailWrapper.querySelectorAll('.detail-item').length;
            const template = `
                <div class="detail-item border rounded p-3 mb-3">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-4">
                            <label class="form-label">Nama Tamu</label>
                            <input type="text" name="details[${currentIndex}][nama_tamu]" class="form-control" placeholder="Nama lengkap">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">No. Telepon</label>
                            <input type="text" name="details[${currentIndex}][no_tlp]" class="form-control" placeholder="0812xxxx">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="details[${currentIndex}][email]" class="form-control" placeholder="mail@example.com">
                        </div>
                        <div class="col-md-1 text-end">
                            <button type="button" class="btn btn-outline-danger btn-sm removeDetailBtn">
                                <i class="bi bi-x-lg"></i>
                            </button>
                        </div>
                    </div>
                </div>
            `;
            detailWrapper.insertAdjacentHTML('beforeend', template);
        });

        detailWrapper.addEventListener('click', function (event) {
            if (event.target.closest('.removeDetailBtn')) {
                const items = detailWrapper.querySelectorAll('.detail-item');
                if (items.length <= 1) return;
                event.target.closest('.detail-item').remove();
            }
        });
    })();
</script>
@endpush
@endsection