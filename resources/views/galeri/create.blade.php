@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Tambah Bukti Pembayaran</h1>
    <div class="card mb-4 mt-3">
        <div class="card-body">
            <form action="{{ route('galeri.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label class="form-label"> Nama Warga Pembayar</label>
                    <input type="text" name="judul" class="form-control" placeholder="Contoh: Bukti Iuran Bapak Budi - RT 05" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Keterangan Transaksi</label>
                    <textarea name="keterangan" class="form-control" rows="4" placeholder="Contoh: Pembayaran lunas Rp 50.000 via Transfer BCA" required></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Foto Bukti Pembayaran</label>
                    <input type="file" name="foto" class="form-control" accept="image/*" required>
                </div>
                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="{{ route('galeri.index') }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
</div>
@endsection