@extends('layouts.app')

@section('title', 'Data Peminjaman')

@section('content')
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0">
                <i class="fas fa-list text-primary me-2"></i>
                Daftar Peminjaman Kelas
            </h2>
            <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Kembali ke Dashboard
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-light">
                <h5 class="mb-0">
                    <i class="fas fa-calendar-check text-primary me-2"></i>
                    @if(auth()->user()->role === 'admin')
                        Semua Peminjaman
                    @else
                        Peminjaman Saya
                    @endif
                </h5>
            </div>
            <div class="card-body">
                @if($peminjaman->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th><i class="fas fa-hashtag text-muted me-2"></i>No</th>
                                    @if(auth()->user()->role === 'admin')
                                        <th><i class="fas fa-user text-muted me-2"></i>Peminjam</th>
                                    @endif
                                    <th><i class="fas fa-book text-muted me-2"></i>Mata Kuliah</th>
                                    <th><i class="fas fa-school text-muted me-2"></i>Kode Kelas</th>
                                    <th><i class="fas fa-calendar text-muted me-2"></i>Tanggal</th>
                                    <th><i class="fas fa-clock text-muted me-2"></i>Jam</th>
                                    <th><i class="fas fa-info-circle text-muted me-2"></i>Status</th>
                                    <th><i class="fas fa-sticky-note text-muted me-2"></i>Keterangan</th>
                                    @if(auth()->user()->role === 'admin')
                                        <th><i class="fas fa-cogs text-muted me-2"></i>Aksi</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($peminjaman as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    @if(auth()->user()->role === 'admin')
                                        <td>
                                            <strong>{{ $item->user->nama_lengkap }}</strong>
                                            <br><small class="text-muted">{{ $item->user->nim }}</small>
                                        </td>
                                    @endif
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
                                        <span class="badge fs-6 px-3 py-2
                                            @if($item->status == 'disetujui') bg-success
                                            @elseif($item->status == 'ditolak') bg-danger
                                            @elseif($item->status == 'selesai') bg-info
                                            @else bg-warning @endif">
                                            @if($item->status == 'disetujui')
                                                <i class="fas fa-check-circle me-1"></i>
                                            @elseif($item->status == 'ditolak')
                                                <i class="fas fa-times-circle me-1"></i>
                                            @elseif($item->status == 'selesai')
                                                <i class="fas fa-check-double me-1"></i>
                                            @else
                                                <i class="fas fa-clock me-1"></i>
                                            @endif
                                            @if($item->status == 'selesai') Selesai @else {{ ucfirst($item->status) }} @endif
                                        </span>
                                    </td>
                                    <td>
                                        <div class="text-truncate" style="max-width: 200px;" title="{{ $item->keterangan }}">
                                            {{ Str::limit($item->keterangan, 50) }}
                                        </div>
                                    </td>
                                    @if(auth()->user()->role === 'admin')
                                        <td>
                                            <button type="button" class="btn btn-sm text-white" style="background-color: #800000;" data-bs-toggle="modal" data-bs-target="#detailModal{{ $item->id }}" title="Lihat Detail">
                                                <i class="fas fa-eye me-1"></i>Detail
                                            </button>
                                        </td>
                                    @endif
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">Belum ada data peminjaman</h5>
                        <p class="text-muted mb-4">
                            @if(auth()->user()->role === 'admin')
                                Belum ada peminjaman yang diajukan oleh pengguna.
                            @else
                                Anda belum mengajukan peminjaman kelas.
                            @endif
                        </p>
                        <a href="{{ route('dashboard') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Ajukan Peminjaman Baru
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Detail Modals -->
@foreach($peminjaman as $item)
<div class="modal fade" id="detailModal{{ $item->id }}" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header text-white" style="background-color: #800000;">
                <h5 class="modal-title">
                    <i class="fas fa-eye me-2"></i>Detail Peminjaman
                    @if(auth()->user()->role === 'admin')
                        - {{ $item->user->nama_lengkap }}
                    @endif
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                @if(auth()->user()->role === 'admin')
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
                @endif

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

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Status</label>
                        <input type="text" class="form-control"
                               value="@if($item->status == 'disetujui') Disetujui
                                      @elseif($item->status == 'ditolak') Ditolak
                                      @elseif($item->status == 'selesai') Selesai
                                      @else Menunggu @endif" readonly>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Dibuat Pada</label>
                        <input type="text" class="form-control" value="{{ $item->created_at->format('d/m/Y H:i') }}" readonly>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Keterangan</label>
                    <textarea class="form-control" rows="3" readonly>{{ $item->keterangan }}</textarea>
                </div>
            </div>
            <div class="modal-footer">
                @if(auth()->user()->role === 'admin' && $item->status === 'menunggu')
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
                @endif
            </div>
        </div>
    </div>
</div>
@endforeach
@endsection
