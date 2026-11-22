@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Edit Galeri Bukti Pembayaran</h1>
    <div class="card mb-4 mt-3">
        <div class="card-body">
            <form action="{{ route('galeri.update', $galeri->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label class="form-label">Nama Warga Pembayar</label>
                    <input type="text" name="judul" class="form-control" value="{{ $galeri->judul }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Keterangan Transaksi</label>
                    <textarea name="keterangan" class="form-control" rows="4" required>{{ $galeri->keterangan }}</textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Foto Bukti Saat Ini</label><br>
                    <img src="{{ asset('storage/' . $galeri->foto) }}" class="img-thumbnail mb-2" style="max-height: 150px;">
                    <input type="file" name="foto" class="form-control" accept="image/*">
                    <small class="text-muted">Biarkan kosong jika tidak ingin mengganti foto.</small>
                </div>
                <button type="submit" class="btn btn-warning">Update Data</button>
                <a href="{{ route('galeri.index') }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
</div>
@endsection