@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">{{ $galeri->judul }}</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('galeri.index') }}">Galeri Bukti Bayar</a></li>
        <li class="breadcrumb-item active">{{ $galeri->galeri_seo }}</li>
    </ol>

    <div class="card mb-4">
        <div class="card-body text-center">
            <a href="{{ asset('storage/' . $galeri->foto) }}" data-lightbox="detail-bukti" data-title="{{ $galeri->judul }}">
                <img src="{{ asset('storage/' . $galeri->foto) }}" class="img-fluid rounded shadow-sm mb-3" style="max-height: 450px;" alt="{{ $galeri->judul }}">
            </a>
            <h4>{{ $galeri->judul }}</h4>
            <p class="text-muted">{{ $galeri->keterangan }}</p>
            <a href="{{ route('galeri.index') }}" class="btn btn-secondary mt-2">Kembali ke Galeri</a>
        </div>
    </div>
</div>
@endsection