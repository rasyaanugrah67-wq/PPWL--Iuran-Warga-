@extends('layouts.master')

@section('content')
<div class="container mt-5">
    <div class="card shadow-sm">
        <div class="card-header bg-info text-white">
            <h4><i class="fas fa-upload"></i> Upload Bukti Pembayaran</h4>
        </div>
        <div class="card-body">
            
            @if (count($errors) > 0)
                <ul class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            @endif

            <div class="alert alert-secondary">
                <strong>Nama Warga:</strong> {{ $iuran->warga->nama ?? 'Data Tidak Ditemukan' }} <br>
                <strong>Nominal Tagihan:</strong> Rp {{ number_format($iuran->nominal, 0, ',', '.') }} <br>
                <strong>Jatuh Tempo:</strong> {{ \Carbon\Carbon::parse($iuran->jatuh_tempo)->format('d/m/Y') }}
            </div>

            <!-- Form Upload -->
            <form action="{{ route('iuran.proses_bayar', $iuran->id_iuran) }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="mb-4">
                    <label class="form-label fw-bold">Pilih Foto Struk / Bukti Transfer</label>
                    <input type="file" name="bukti_pembayaran" class="form-control" accept="image/*" required>
                    <small class="text-muted">Format yang diizinkan: JPG, JPEG, PNG. Maksimal ukuran: 2MB.</small>
                </div>

                <button type="submit" class="btn btn-primary"><i class="fas fa-paper-plane"></i> Kirim Bukti</button>
                <a href="{{ route('iuran.index') }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
</div>
@endsection