@extends('layouts.master')

@section('content')
<div class="container mt-5">
    <div class="card">
        <div class="card-header bg-warning text-dark">
            <h4>Edit Data Iuran</h4>
        </div>
        <div class="card-body">
            
            <!-- Pesan Error Validasi -->
            @if (count($errors) > 0)
                <ul class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            @endif

            <!-- Form Edit mengarah ke route update dengan ID spesifik -->
            <form action="{{ route('iuran.update', $iuran->id_iuran) }}" method="POST">
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
                    <!-- Menampilkan tanggal lama dan tetap memakai Datepicker -->
                    <input type="text" id="jatuh_tempo" name="jatuh_tempo" class="date form-control" value="{{ \Carbon\Carbon::parse($iuran->jatuh_tempo)->format('Y/m/d') }}">
                </div>

                <div class="mb-3">
                    <label>Status Pembayaran</label>
                    <select name="status" class="form-control">
                        <option value="Menunggu" {{ $iuran->status == 'Menunggu' ? 'selected' : '' }}>Menunggu</option>
                        <option value="Lunas" {{ $iuran->status == 'Lunas' ? 'selected' : '' }}>Lunas</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-success">Update Data</button>
                <a href="{{ route('iuran.index') }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
</div>
@endsection
