@extends('layouts.master')

@section('content')
<div class="container mt-5">
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h4>Tambah Data Iuran</h4>
        </div>
        <div class="card-body">
            
            @if (count($errors) > 0)
                <ul class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            @endif

            <form action="{{ route('iuran.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="mb-3">
                    <label>ID Warga</label>
                    <input type="text" name="id_warga" class="form-control" placeholder="Masukkan ID Warga (misal: 1, 2, 3)">
                </div>
                
                <div class="mb-3">
                    <label>Nominal Tagihan (Rp)</label>
                    <input type="text" name="nominal" class="form-control" placeholder="Contoh: 50000">
                </div>

                <div class="mb-3">
                    <label>Tanggal Jatuh Tempo</label>
                    <input type="text" id="jatuh_tempo" name="jatuh_tempo" class="date form-control" placeholder="yyyy/mm/dd">
                </div>

                <div class="mb-3">
                    <label>Status Pembayaran</label>
                    <select name="status" class="form-control">
                        <option value="Menunggu">Menunggu</option>
                        <option value="Lunas">Lunas</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label>Bukti Pembayaran (Opsional)</label>
                    <input type="file" name="bukti_pembayaran" class="form-control" accept="image/*">
                    <small class="text-muted">Format: JPG, JPEG, PNG. Maksimal: 2MB.</small>
                </div>

                <button type="submit" class="btn btn-success">Simpan Data</button>
                <a href="{{ route('iuran.index') }}" class="btn btn-secondary">Kembali</a>
            </form>
        </div>
    </div>
</div>
@endsection