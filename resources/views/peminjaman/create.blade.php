@extends('layouts.app')

@section('title', 'Ajukan Peminjaman')

@section('content')
<h4>Form Peminjaman Kelas</h4>

<form method="POST" action="{{ route('peminjaman.store') }}">
    @csrf

    <div class="mb-3">
        <label>Kelas</label>
        <select name="kelas_id" class="form-control" required>
            @foreach ($kelas as $k)
                <option value="{{ $k->id }}">{{ $k->nama_kelas }}</option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label>Tanggal</label>
        <input type="date" name="tanggal" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Jam Mulai</label>
        <input type="time" name="jam_mulai" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Jam Selesai</label>
        <input type="time" name="jam_selesai" class="form-control" required>
    </div>

    <button class="btn btn-primary">Ajukan</button>
</form>
@endsection
