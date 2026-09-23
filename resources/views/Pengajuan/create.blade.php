@extends('base.base')
@section('title', 'Form Pengajuan Peminjaman')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card custom-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="card-title mb-0">Form Pengajuan Peminjaman</h5>
                </div>
                <a href="{{ route('pengajuan.list') }}" class="btn btn-sm btn-secondary">Kembali</a>
            </div>
            <div class="card-body">
                <div id="pengajuan-data"
                     data-list-asets='@json($listAsetsForFilter)'
                     data-old-aset-kode="{{ old('aset_kode') }}"
                     data-old-masa-berlaku="{{ old('masa_berlaku') }}"></div>
                <form method="POST" action="{{ route('pengajuan.store') }}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="status" value="{{ old('status', 'open') }}">
                    <input type="hidden" name="progress" value="{{ old('progress', 'Menunggu verifikasi') }}">

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Kode Pengajuan</label>
                            <input type="text" readonly name="kode_pengajuan"
                                   value="{{ old('kode_pengajuan', $kodePengajuanDefault) }}"
                                   class="form-control @error('kode_pengajuan') is-invalid @enderror"
                                   placeholder="PMT-25-000001">
                            @error('kode_pengajuan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Kategori Aset</label>
                            <select id="kategori_aset" class="form-select @error('kategori_aset') is-invalid @enderror">
                                <option value="">Pilih kategori aset</option>
                                @foreach($kategoriAsetOptions as $kat)
                                    <option value="{{ $kat }}" {{ old('kategori_aset') == $kat ? 'selected' : '' }}>{{ $kat }}</option>
                                @endforeach
                            </select>
                            @error('kategori_aset')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Kategori ini hanya untuk menampilkan aset yang sesuai.</div>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Aset</label>
                            <select name="aset_kode" id="aset_kode" class="form-select @error('aset_kode') is-invalid @enderror">
                                <option value="">Pilih aset</option>
                            </select>
                            @error('aset_kode')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Perusahaan Tamu</label>
                            <input type="text" name="perusahaan_tamu" value="{{ old('perusahaan_tamu') }}"
                                   class="form-control @error('perusahaan_tamu') is-invalid @enderror"
                                   placeholder="Nama perusahaan tamu">
                            @error('perusahaan_tamu')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Perusahaan Pengutus</label>
                            <input type="text" name="perusahaan_pengutus" value="{{ old('perusahaan_pengutus') }}"
                                   class="form-control @error('perusahaan_pengutus') is-invalid @enderror"
                                   placeholder="Perusahaan pengutus">
                            @error('perusahaan_pengutus')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Jumlah Orang</label>
                            <input type="text" name="jumlah" value="{{ old('jumlah') }}"
                                   class="form-control @error('jumlah') is-invalid @enderror"
                                   placeholder="Contoh: 5">
                            @error('jumlah')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Jam Mulai <small class="text-muted">(min 08:30)</small></label>
                            <input type="time" id="jam_mulai" name="jam_mulai" min="08:30" value="{{ old('jam_mulai') }}"
                                   class="form-control @error('jam_mulai') is-invalid @enderror">
                            <div id="jam_mulai_error" class="invalid-feedback" style="display:none;">Jam mulai minimal pukul 08:30.</div>
                            @error('jam_mulai')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Jam Selesai <small class="text-muted">(maks 17:00)</small></label>
                            <input type="time" id="jam_selesai" name="jam_selesai" max="17:00" value="{{ old('jam_selesai') }}"
                                   class="form-control @error('jam_selesai') is-invalid @enderror">
                            <div id="jam_selesai_error" class="invalid-feedback" style="display:none;">Jam selesai maksimal pukul 17:00.</div>
                            @error('jam_selesai')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label class="form-label">Kategori Pekerjaan</label>
                            <select name="kategori" class="form-control @error('kategori') is-invalid @enderror">
                                <option value="">-- Pilih Kategori --</option>
                                <option value="Dismantel" {{ old('kategori') == 'Dismantel' ? 'selected' : '' }}>Dismantel</option>
                                <option value="Maintenance" {{ old('kategori') == 'Maintenance' ? 'selected' : '' }}>Maintenance</option>
                                <option value="Perbaikan" {{ old('kategori') == 'Perbaikan' ? 'selected' : '' }}>Perbaikan</option>
                                <option value="Pengecekan" {{ old('kategori') == 'Pengecekan' ? 'selected' : '' }}>Pengecekan</option>
                            </select>
                            @error('kategori')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label class="form-label">Keperluan</label>
                            <textarea name="keperluan" rows="3"
                                      class="form-control @error('keperluan') is-invalid @enderror"
                                      placeholder="Jelaskan tujuan kunjungan">{{ old('keperluan') }}</textarea>
                            @error('keperluan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Identitas</label>
                            <select name="identitas" id="identitas" class="form-select @error('identitas') is-invalid @enderror">
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
                            <label class="form-label">Masa Berlaku</label>
                            <input type="text" name="masa_berlaku" id="masa_berlaku" value="{{ old('masa_berlaku') }}"
                                   class="form-control @error('masa_berlaku') is-invalid @enderror"
                                   placeholder="Masa berlaku identitas">
                            @error('masa_berlaku')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
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
                            <button type="submit" class="btn btn-primary">Ajukan Peminjaman</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    (function () {
        const kategoriSelect = document.getElementById('kategori_aset');
        const asetSelect = document.getElementById('aset_kode');
        const dataEl = document.getElementById('pengajuan-data');
        const all = dataEl ? JSON.parse(dataEl.dataset.listAsets || '[]') : [];
        const oldAsetKode = dataEl?.dataset.oldAsetKode || '';

        function optionLabel(a) {
            return `${a.nama}${a.lokasi ? ' - ' + a.lokasi : ''}    `;
        }

        function renderAsetOptions() {
            const kat = (kategoriSelect?.value || '').toLowerCase();
            const filtered = kat ? all.filter(a => (a.kategori || '').toLowerCase() === kat) : all;

            asetSelect.innerHTML = '<option value="">Pilih aset</option>';
            filtered.forEach(a => {
                const opt = document.createElement('option');
                opt.value = a.kode_aset;
                opt.textContent = optionLabel(a);
                if (oldAsetKode && oldAsetKode === a.kode_aset) opt.selected = true;
                asetSelect.appendChild(opt);
            });
        }

        if (kategoriSelect && asetSelect) {
            kategoriSelect.addEventListener('change', renderAsetOptions);
            renderAsetOptions();
        }
    })();
</script>

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

<script>
document.addEventListener('DOMContentLoaded', function() {
    const identitasSelect = document.getElementById('identitas');
    const masaBerlakuInput = document.getElementById('masa_berlaku');

    function updateMasaBerlaku() {
        const identitasValue = identitasSelect.value;

        if (identitasValue === 'KTP') {
            masaBerlakuInput.type = 'text';
            masaBerlakuInput.value = 'Seumur Hidup';
            masaBerlakuInput.setAttribute('readonly', 'readonly');
            masaBerlakuInput.style.backgroundColor = '#e9ecef';
        } else if (identitasValue === 'SIM') {
            masaBerlakuInput.type = 'date';
            const dataEl = document.getElementById('pengajuan-data');
            masaBerlakuInput.value = dataEl?.dataset.oldMasaBerlaku || '';
            masaBerlakuInput.removeAttribute('readonly');
            masaBerlakuInput.style.backgroundColor = '';
            masaBerlakuInput.placeholder = '';
        } else {
            masaBerlakuInput.type = 'text';
            masaBerlakuInput.value = '';
            masaBerlakuInput.removeAttribute('readonly');
            masaBerlakuInput.style.backgroundColor = '';
            masaBerlakuInput.placeholder = 'Masa berlaku identitas';
        }
    }

    updateMasaBerlaku();
    identitasSelect.addEventListener('change', updateMasaBerlaku);
});
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const jamSelesaiInput = document.getElementById('jam_selesai');
    const jamSelesaiError = document.getElementById('jam_selesai_error');
    const MAX_TIME = '17:00';

    function enforceMaxJamSelesai() {
        if (!jamSelesaiInput.value) return;
        if (jamSelesaiInput.value > MAX_TIME) {
            jamSelesaiInput.value = MAX_TIME;
            jamSelesaiInput.classList.add('is-invalid');
            jamSelesaiError.style.display = 'block';
        } else {
            jamSelesaiInput.classList.remove('is-invalid');
            jamSelesaiError.style.display = 'none';
        }
    }

    jamSelesaiInput.addEventListener('change', enforceMaxJamSelesai);
    jamSelesaiInput.addEventListener('blur', enforceMaxJamSelesai);
    jamSelesaiInput.addEventListener('input', enforceMaxJamSelesai);

    const jamMulaiInput = document.getElementById('jam_mulai');
    const jamMulaiError = document.getElementById('jam_mulai_error');
    const MIN_TIME = '08:30';

    function enforceMinJamMulai() {
        if (!jamMulaiInput.value) return;
        if (jamMulaiInput.value < MIN_TIME) {
            jamMulaiInput.value = MIN_TIME;
            jamMulaiInput.classList.add('is-invalid');
            jamMulaiError.style.display = 'block';
        } else {
            jamMulaiInput.classList.remove('is-invalid');
            jamMulaiError.style.display = 'none';
        }
    }

    jamMulaiInput.addEventListener('change', enforceMinJamMulai);
    jamMulaiInput.addEventListener('blur', enforceMinJamMulai);
    jamMulaiInput.addEventListener('input', enforceMinJamMulai);
});
</script>
@endpush
@endsection


