@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="bg-white rounded-lg shadow-sm border">
                <div class="p-4">
                    <div class="text-center">
                        <div class="d-inline-flex align-items-center justify-content-center w-16 h-16 bg-primary bg-opacity-10 rounded-circle mb-3">
                            <i class="fas fa-user-graduate text-primary fa-2x"></i>
                        </div>
                        <h1 class="h3 mb-2 fw-bold text-dark">Selamat Datang, {{ auth()->user()->nama_lengkap ?? auth()->user()->name }}!</h1>
                        <p class="text-muted mb-0">Selamat menggunakan Sistem Peminjaman Ruang Kelas</p>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Daftar Kelas -->
    <div class="row mb-5">
        <div class="col-12">
            <h4 class="mb-3 text-secondary">
                <i class="fas fa-chalkboard me-2"></i>Daftar Kelas Tersedia
            </h4>
        </div>

        @forelse($kelas as $k)
        <div class="col-lg-3 col-md-4 col-sm-6 mb-3">
            <div class="card h-100 shadow-sm border-0" style="background-color: #f8f9fa;">
                <div class="card-body text-center p-3">
                    <h6 class="card-title mb-2 fw-bold" style="font-size: 0.9rem;">
                        {{ $k->nama_kelas }}
                    </h6>
                    <p class="text-muted small mb-2">{{ $k->code_kelas }}</p>

                    <div class="mb-2">
                        <span class="badge fs-6 px-2 py-1
                            @if(isset($k->real_time_status) && $k->real_time_status === 'user_has_booking') bg-danger
                            @elseif(isset($k->real_time_status) && $k->real_time_status === 'occupied') bg-danger
                            @elseif(isset($k->real_time_status) && $k->real_time_status === 'warning') bg-warning text-dark
                            @elseif($k->jumlah_peminjaman < $k->kapasitas) bg-success
                            @else bg-danger @endif">
                            @if(isset($k->real_time_status) && $k->real_time_status === 'user_has_booking')
                                <i class="fas fa-ban me-1"></i>Tidak Tersedia
                            @elseif(isset($k->real_time_status) && $k->real_time_status === 'occupied')
                                <i class="fas fa-times-circle me-1"></i>Sedang Digunakan
                            @elseif(isset($k->real_time_status) && $k->real_time_status === 'warning')
                                <i class="fas fa-clock me-1"></i>Akan Digunakan
                            @elseif($k->jumlah_peminjaman < $k->kapasitas)
                                <i class="fas fa-check-circle me-1"></i>Tersedia
                            @else
                                <i class="fas fa-times-circle me-1"></i>Penuh
                            @endif
                        </span>
                    </div>

                    <div class="small text-muted mb-3">
                        Kuota: {{ $k->kuota_tersisa }} tersisa
                    </div>

                    @if(isset($k->real_time_status) && ($k->real_time_status === 'user_has_booking' || $k->real_time_status === 'occupied'))
                        @if(auth()->user()->role === 'admin')
                            <div class="text-center">
                                <i class="fas fa-user-check text-info fa-lg mb-1"></i>
                                <br>
                                <small class="text-muted fw-bold">Sedang Digunakan</small>
                            </div>
                        @else
                            <button class="btn btn-sm btn-danger w-100" disabled style="font-size: 0.8rem;">
                                <i class="fas fa-ban me-1"></i>{{ $k->status_message ?? 'Tidak Tersedia' }}
                            </button>
                        @endif
                    @elseif($k->jumlah_peminjaman < $k->kapasitas)
                        @if(auth()->user()->role === 'admin')
                            <button class="btn btn-sm btn-success w-100" disabled style="font-size: 0.8rem;">
                                <i class="fas fa-check-circle me-1"></i>Tersedia
                            </button>
                        @else
                            <button type="button" class="btn btn-sm text-white w-100" style="background-color: #7F1D1D; font-size: 0.8rem;" data-bs-toggle="modal" data-bs-target="#pinjamModal{{ $k->id }}">
                                <i class="fas fa-calendar-plus me-1"></i>Pinjam
                            </button>
                        @endif
                    @else
                        <button class="btn btn-sm btn-secondary w-100" disabled style="font-size: 0.8rem;">
                            <i class="fas fa-ban me-1"></i>Penuh
                        </button>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="alert alert-info text-center" style="background-color: #e3f2fd; border: 1px solid #2196f3; color: #0d47a1;">
                <i class="fas fa-info-circle fa-2x mb-2"></i>
                <h5>Tidak ada kelas tersedia</h5>
                <p class="mb-0">Belum ada data kelas yang dapat dipinjam.</p>
            </div>
        </div>
        @endforelse
    </div>

    <!-- Modal Pinjam -->
    @foreach($kelas as $k)
    <div class="modal fade" id="pinjamModal{{ $k->id }}" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header text-white" style="background-color: #7F1D1D;">
                    <h5 class="modal-title">
                        <i class="fas fa-calendar-plus me-2"></i>Pinjam {{ $k->nama_kelas }}
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="{{ route('pinjam.store') }}">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="kelas_id" value="{{ $k->id }}">

                        <!-- Data User (Auto-filled) -->
                        <div class="row mb-3">
                            <div class="col-md-4 mb-2">
                                <label class="form-label fw-bold">NIM</label>
                                <input type="text" class="form-control" value="{{ auth()->user()->nim }}" readonly>
                            </div>
                            <div class="col-md-4 mb-2">
                                <label class="form-label fw-bold">Nama Lengkap</label>
                                <input type="text" class="form-control" value="{{ auth()->user()->nama_lengkap }}" readonly>
                            </div>
                            <div class="col-md-4 mb-2">
                                <label class="form-label fw-bold">Program Studi</label>
                                <input type="text" class="form-control" value="{{ auth()->user()->prodi }}" readonly>
                            </div>
                        </div>

                        <hr>

                        <!-- Form Peminjaman -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="tanggal{{ $k->id }}" class="form-label fw-bold">Tanggal</label>
                                <input type="date" class="form-control" id="tanggal{{ $k->id }}" name="tanggal" required min="{{ date('Y-m-d') }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="nama_matkul{{ $k->id }}" class="form-label fw-bold">Nama Mata Kuliah</label>
                                <input type="text" class="form-control" id="nama_matkul{{ $k->id }}" name="nama_matkul" placeholder="Contoh: Algoritma Pemrograman" onkeyup="generateKodeMatkul({{ $k->id }})">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="kelas_matkul{{ $k->id }}" class="form-label fw-bold">Kelas</label>
                                <input type="text" class="form-control" id="kelas_matkul{{ $k->id }}" name="kelas_matkul" placeholder="01-99" maxlength="2" onkeyup="generateKodeMatkul({{ $k->id }})">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="kode_matkul{{ $k->id }}" class="form-label fw-bold">Kode Mata Kuliah <small class="text-muted">(Otomatis)</small></label>
                                <input type="text" class="form-control" id="kode_matkul{{ $k->id }}" name="kode_matkul" readonly>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="jam_mulai{{ $k->id }}" class="form-label fw-bold">Jam Mulai</label>
                                <input type="time" class="form-control" id="jam_mulai{{ $k->id }}" name="jam_mulai" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="jam_selesai{{ $k->id }}" class="form-label fw-bold">Jam Selesai</label>
                                <input type="time" class="form-control" id="jam_selesai{{ $k->id }}" name="jam_selesai" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="keterangan{{ $k->id }}" class="form-label fw-bold">Keterangan</label>
                            <textarea class="form-control" id="keterangan{{ $k->id }}" name="keterangan" rows="3" placeholder="Jelaskan keperluan peminjaman (kelas pengganti, kegiatan organisasi, dll)" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn text-white" style="background-color: #7F1D1D;">Ajukan Peminjaman</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endforeach

    <!-- Occupant Detail Modals for Admin -->
    @if(auth()->user()->role === 'admin')
    @foreach($kelas as $k)
    @php
        $currentBookings = \App\Models\Peminjaman::where('kelas_id', $k->id)
            ->where('status', 'disetujui')
            ->where('tanggal', now()->toDateString())
            ->where('jam_mulai', '<=', now()->toTimeString())
            ->where('jam_selesai', '>', now()->toTimeString())
            ->with('user')
            ->get();
    @endphp
    @if($currentBookings->count() > 0)
    <div class="modal fade" id="occupantModal{{ $k->id }}" tabindex="-1">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header text-white" style="background-color: #17a2b8;">
                    <h5 class="modal-title">
                        <i class="fas fa-user-clock me-2"></i>{{ $k->nama_kelas }} ({{ $k->code_kelas }})
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">
                    <i class="fas fa-user fa-3x text-info mb-3"></i>
                    <h5 class="fw-bold">Sedang Digunakan</h5>
                    @if($currentBookings->count() == 1)
                        <p class="mb-1">Ruangan ini sedang digunakan oleh:</p>
                        <h6 class="text-primary fw-bold">{{ $currentBookings->first()->user->nama_lengkap }}</h6>
                        <small class="text-muted">(NIM: {{ $currentBookings->first()->user->nim }})</small>
                    @else
                        <p class="mb-1">Ruangan ini sedang digunakan oleh {{ $currentBookings->count() }} pengguna:</p>
                        <div class="mt-3">
                            @foreach($currentBookings as $booking)
                                <div class="badge bg-primary me-1 mb-1 p-2">
                                    {{ $booking->user->nama_lengkap }}
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
                <div class="modal-footer">
                </div>
            </div>
        </div>
    </div>
    @endif
    @endforeach
    @endif

    <!-- Admin: Pending Bookings Management -->
    @if(auth()->user()->role === 'admin' && $pendingBookings->count() > 0)
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-0" style="background-color: #f8f9fa;">
                <div class="card-header" style="background-color: #6c757d; color: white;">
                    <h5 class="mb-0">
                        <i class="fas fa-user-shield me-2"></i>Manage Peminjaman ({{ $pendingBookings->count() }} pending)
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th><i class="fas fa-hashtag text-muted me-2"></i>No</th>
                                    <th><i class="fas fa-user text-muted me-2"></i>Peminjam</th>
                                    <th><i class="fas fa-book text-muted me-2"></i>Mata Kuliah</th>
                                    <th><i class="fas fa-school text-muted me-2"></i>Kode Kelas</th>
                                    <th><i class="fas fa-calendar text-muted me-2"></i>Tanggal</th>
                                    <th><i class="fas fa-clock text-muted me-2"></i>Jam</th>
                                    <th><i class="fas fa-cogs text-muted me-2"></i>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pendingBookings as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <strong>{{ $item->user->nama_lengkap }}</strong>
                                        <br><small class="text-muted">{{ $item->user->nim }}</small>
                                    </td>
                                    <td>
                                        <strong>{{ $item->nama_matkul ?? '-' }}</strong>
                                        @if($item->kode_matkul)
                                            <br><small class="text-muted">{{ $item->kode_matkul }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary">{{ $item->kelas->code_kelas }}</span>
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}</td>
                                    <td>{{ $item->jam_mulai }} - {{ $item->jam_selesai }}</td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#detailModal{{ $item->id }}">
                                            <i class="fas fa-eye me-1"></i>Detail
                                        </button>
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
    @endif

    <!-- Detail Modals for Admin -->
    @if(auth()->user()->role === 'admin' && $pendingBookings->count() > 0)
    @foreach($pendingBookings as $item)
    <div class="modal fade" id="detailModal{{ $item->id }}" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header text-white" style="background-color: #6c757d;">
                    <h5 class="modal-title">
                        <i class="fas fa-eye me-2"></i>Detail Peminjaman - {{ $item->user->nama_lengkap }}
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <!-- Data User -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="fw-bold text-primary mb-3">
                                <i class="fas fa-user me-2"></i>Data Peminjam
                            </h6>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">NIM</label>
                            <input type="text" class="form-control" value="{{ $item->user->nim }}" readonly>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">Nama Lengkap</label>
                            <input type="text" class="form-control" value="{{ $item->user->nama_lengkap }}" readonly>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">Program Studi</label>
                            <input type="text" class="form-control" value="{{ $item->user->prodi }}" readonly>
                        </div>
                    </div>

                    <hr>

                    <!-- Data Peminjaman -->
                    <div class="row">
                        <div class="col-12">
                            <h6 class="fw-bold text-primary mb-3">
                                <i class="fas fa-calendar-alt me-2"></i>Data Peminjaman
                            </h6>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Tanggal</label>
                            <input type="text" class="form-control" value="{{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}" readonly>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Ruang Kelas</label>
                            <input type="text" class="form-control" value="{{ $item->kelas->nama_kelas }} ({{ $item->kelas->code_kelas }})" readonly>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Nama Mata Kuliah</label>
                            <input type="text" class="form-control" value="{{ $item->nama_matkul ?? '-' }}" readonly>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Kelas Mata Kuliah</label>
                            <input type="text" class="form-control" value="{{ $item->kelas_matkul ?? '-' }}" readonly>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Kode Mata Kuliah</label>
                            <input type="text" class="form-control" value="{{ $item->kode_matkul ?? '-' }}" readonly>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Waktu</label>
                            <input type="text" class="form-control" value="{{ $item->jam_mulai }} - {{ $item->jam_selesai }}" readonly>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Keterangan</label>
                        <textarea class="form-control" rows="3" readonly>{{ $item->keterangan }}</textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="w-100 d-flex justify-content-end">
                        <form method="POST" action="{{ route('pinjam.reject', $item->id) }}" class="d-inline me-2">
                            @csrf
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Apakah Anda yakin ingin menolak peminjaman ini?')">
                                <i class="fas fa-times me-1"></i>Tolak
                            </button>
                        </form>
                        <form method="POST" action="{{ route('pinjam.acc', $item->id) }}" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-success" onclick="return confirm('Apakah Anda yakin ingin menyetujui peminjaman ini?')">
                                <i class="fas fa-check me-1"></i>Setujui
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
    @endif

    <!-- Riwayat Peminjaman -->
    @if($riwayatPeminjaman->count() > 0)
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0" style="background-color: #f8f9fa;">
                <div class="card-header" style="background-color: #6c757d; color: white;">
                    <h5 class="mb-0">
                        <i class="fas fa-history me-2"></i>Riwayat Peminjaman
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead style="background-color: #e9ecef;">
                                <tr>
                                    <th><i class="fas fa-book text-muted me-2"></i>Mata Kuliah</th>
                                    <th><i class="fas fa-school text-muted me-2"></i>Kode Kelas</th>
                                    <th><i class="fas fa-calendar text-muted me-2"></i>Tanggal</th>
                                    <th><i class="fas fa-clock text-muted me-2"></i>Waktu</th>
                                    <th><i class="fas fa-info-circle text-muted me-2"></i>Status</th>
                                    <th><i class="fas fa-sticky-note text-muted me-2"></i>Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($riwayatPeminjaman as $p)
                                <tr style="background-color: #ffffff;">
                                    <td>
                                        <strong>{{ $p->kode_matkul ?? '-' }}</strong>
                                        @if($p->nama_matkul)
                                            <br><small class="text-muted">{{ Str::limit($p->nama_matkul, 30) }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary">{{ $p->kelas->code_kelas }}</span>
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($p->tanggal)->format('d/m/Y') }}</td>
                                    <td>{{ $p->jam_mulai }} - {{ $p->jam_selesai }}</td>
                                    <td>
                                        <span class="badge
                                            @if($p->status == 'disetujui') bg-success
                                            @elseif($p->status == 'ditolak') bg-danger
                                            @elseif($p->status == 'selesai') bg-info
                                            @else bg-warning text-dark @endif">
                                            @if($p->status == 'selesai') Selesai @else {{ ucfirst($p->status) }} @endif
                                        </span>
                                    </td>
                                    <td>
                                        <small class="text-muted">{{ Str::limit($p->keterangan, 50) }}</small>
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
    @endif
</div>

<script>
function generateKodeMatkul(modalId) {
    const namaMatkul = document.getElementById('nama_matkul' + modalId).value;
    const kelasMatkul = document.getElementById('kelas_matkul' + modalId).value;
    const kodeMatkulField = document.getElementById('kode_matkul' + modalId);

    if (namaMatkul && kelasMatkul) {
        // Ambil 3 huruf pertama dari nama matkul, ubah ke uppercase
        const kodeAwal = namaMatkul.substring(0, 3).toUpperCase();
        // Pastikan kelas memiliki 2 digit
        const kelasFormatted = kelasMatkul.padStart(2, '0');
        // Gabungkan kode
        const kodeMatkul = kodeAwal + kelasFormatted;
        kodeMatkulField.value = kodeMatkul;
    } else {
        kodeMatkulField.value = '';
    }
}
</script>
@endsection
