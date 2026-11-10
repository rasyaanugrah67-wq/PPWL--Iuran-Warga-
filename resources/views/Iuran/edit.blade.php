@extends('layouts.master')

@section('content')
<div class="container mt-5">
    <div class="card">
        <div class="card-header bg-warning text-dark">
            <h4>Edit Data Iuran</h4>
        </div>
        <div class="card-body">
            
            @if (count($errors) > 0)
                <ul class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            @endif

            <!-- PASTIKAN ADA enctype="multipart/form-data" -->
            <form action="{{ route('iuran.update', $iuran->id_iuran) }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="mb-3">
                    <label>ID Warga</label>
                    <input type="text" name="id_warga" class="form-control" value="{{ $iuran->id_warga }}">
                </div>
                
                <div class="mb-3">
                    <label>Nominal Tagihan (Rp)</label>
                    <input type="text" name="nominal" class="form-control" value="{{ $iuran->nominal }}">
                </div>

                <div class="mb-3">
                    <label>Tanggal Jatuh Tempo</label>
                    <input type="text" id="jatuh_tempo" name="jatuh_tempo" class="date form-control" value="{{ \Carbon\Carbon::parse($iuran->jatuh_tempo)->format('Y/m/d') }}">
                </div>

                <div class="mb-3">
                    <label>Status Pembayaran</label>
                    <select name="status" class="form-control">
                        <option value="Menunggu" {{ $iuran->status == 'Menunggu' ? 'selected' : '' }}>Menunggu</option>
                        <option value="Lunas" {{ $iuran->status == 'Lunas' ? 'selected' : '' }}>Lunas</option>
                    </select>
                </div>

                <!-- KOLOM BARU: PREVIEW & UPLOAD BUKTI PEMBAYARAN -->
                <div class="mb-4">
                    <label>Bukti Pembayaran Baru (Opsional)</label>
                    
                    <!-- Menampilkan gambar lama jika sudah ada di database -->
                    @if($iuran->bukti_pembayaran)
                        <div class="mb-3">
                            <img src="{{ asset('storage/' . $iuran->bukti_pembayaran) }}" alt="Bukti Pembayaran Lama" class="img-thumbnail" style="max-height: 150px; max-width: 100%;">
                            <div class="mt-1"><small class="text-muted">Gambar bukti pembayaran saat ini.</small></div>
                        </div>
                    @endif
                    
                    <input type="file" name="bukti_pembayaran" class="form-control" accept="image/*">
                    <small class="text-muted">Biarkan kosong jika tidak ingin mengubah gambar. Format: JPG, JPEG, PNG. Maksimal: 2MB.</small>
                </div>

                <button type="submit" class="btn btn-success">Update Data</button>
                <a href="{{ route('iuran.index') }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
</div>
@endsection