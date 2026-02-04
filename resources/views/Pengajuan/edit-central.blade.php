@extends('base.base')
@section('title', 'Update Pengajuan Central')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card custom-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="card-title mb-0">Update Pengajuan Central - {{ $pengajuan->kode_pengajuan }}</h5>
                </div>
                <a href="{{ route('pengajuan.list') }}" class="btn btn-sm btn-secondary">Kembali</a>
            </div>
            <div class="card-body">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <form method="POST" action="{{ route('pengajuan.update', $pengajuan->id) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="jenis" value="central">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Kode Pengajuan</label>
                            <input type="text" readonly value="{{ $pengajuan->kode_pengajuan }}" class="form-control bg-light">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Central</label>
                            <select name="central" class="form-select @error('central') is-invalid @enderror">
                                <option value="">Pilih central</option>
                                @foreach($listAsets as $aset)
                                    <option value="{{ $aset->kode_aset }}" {{ old('central', $pengajuan->central_kode) == $aset->kode_aset ? 'selected' : '' }}>
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
                            <input type="text" name="perusahaan_tamu" value="{{ old('perusahaan_tamu', $pengajuan->perusahaan_tamu) }}" class="form-control @error('perusahaan_tamu') is-invalid @enderror" placeholder="Nama perusahaan tamu">
                            @error('perusahaan_tamu')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Perusahaan Pengutus</label>
                            <input type="text" name="perusahaan_pengutus" value="{{ old('perusahaan_pengutus', $pengajuan->perusahaan_pengutus) }}" class="form-control @error('perusahaan_pengutus') is-invalid @enderror" placeholder="Perusahaan pengutus">
                            @error('perusahaan_pengutus')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Jumlah Orang</label>
                            <input type="text" name="jumlah" value="{{ old('jumlah', $pengajuan->jumlah) }}" class="form-control @error('jumlah') is-invalid @enderror" placeholder="Contoh: 3 orang">
                            @error('jumlah')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Jam Mulai</label>
                            <input type="time" name="jam_mulai" value="{{ old('jam_mulai', $pengajuan->jam_mulai) }}" class="form-control @error('jam_mulai') is-invalid @enderror">
                            @error('jam_mulai')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Jam Selesai <small class="text-muted">(maks 17:00)</small></label>
                            <input type="time" name="jam_selesai" max="17:00" value="{{ old('jam_selesai', $pengajuan->jam_selesai) }}" class="form-control @error('jam_selesai') is-invalid @enderror">
                            @error('jam_selesai')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label class="form-label">Kategori</label>
                            <select name="kategori" class="form-control @error('kategori') is-invalid @enderror">
                                <option value="">-- Pilih Kategori --</option>
                                <option value="Dismantel" {{ old('kategori', $pengajuan->kategori) == 'Dismantel' ? 'selected' : '' }}>Dismantel</option>
                                <option value="Maintenance" {{ old('kategori', $pengajuan->kategori) == 'Maintenance' ? 'selected' : '' }}>Maintenance</option>
                                <option value="Perbaikan" {{ old('kategori', $pengajuan->kategori) == 'Perbaikan' ? 'selected' : '' }}>Perbaikan</option>
                                <option value="Pengecekan" {{ old('kategori', $pengajuan->kategori) == 'Pengecekan' ? 'selected' : '' }}>Pengecekan</option>
                            </select>
                            @error('kategori')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label class="form-label">Keperluan</label>
                            <textarea name="keperluan" rows="3" class="form-control @error('keperluan') is-invalid @enderror" placeholder="Jelaskan tujuan kunjungan">{{ old('keperluan', $pengajuan->keperluan) }}</textarea>
                            @error('keperluan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Identitas Yang Ditinggalkan</label>
                            <select name="identitas" class="form-select @error('identitas') is-invalid @enderror">
                                <option value="">Pilih identitas</option>
                                <option value="KTP" {{ old('identitas', $pengajuan->identitas) === 'KTP' ? 'selected' : '' }}>KTP</option>
                                <option value="SIM" {{ old('identitas', $pengajuan->identitas) === 'SIM' ? 'selected' : '' }}>SIM</option>
                            </select>
                            @error('identitas')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Upload Identitas</label>
                            <input type="file" name="identitas_file" class="form-control @error('identitas_file') is-invalid @enderror">
                            @if($pengajuan->identitas_file)
                                <small class="text-muted">File saat ini: <a href="{{ asset('storage/'.$pengajuan->identitas_file) }}" target="_blank">Lihat</a></small>
                            @endif
                            @error('identitas_file')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Masa Berlaku Identitas</label>
                            <input type="text" name="masa_berlaku" value="{{ old('masa_berlaku', $pengajuan->masa_berlaku) }}" class="form-control @error('masa_berlaku') is-invalid @enderror" placeholder="Contoh: s.d 05/2027">
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
                            $oldDetails = old('details', $pengajuanDetails->map(function($detail) {
                                return [
                                    'nama_tamu' => $detail->nama_tamu,
                                    'no_tlp' => $detail->no_tlp,
                                    'email' => $detail->email,
                                ];
                            })->toArray());
                            
                            if (empty($oldDetails)) {
                                $oldDetails = [['nama_tamu' => '', 'no_tlp' => '', 'email' => '']];
                            }
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
                        <a href="{{ route('pengajuan.list') }}" class="btn btn-light">Batal</a>
                        <button type="submit" class="btn btn-primary">Update Pengajuan</button>
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

