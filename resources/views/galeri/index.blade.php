@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mt-4 mb-3">
        <h1>Galeri Bukti Pembayaran Iuran 17-an</h1>
        @if(Auth::check() && Auth::user()->level == 'admin')
            <a href="{{ route('galeri.create') }}" class="btn btn-primary">+ Tambah Bukti Pembayaran</a>
        @endif
    </div>

    @if(session('pesan'))
        <div class="alert alert-success">{{ session('pesan') }}</div>
    @endif

    <div class="card mb-4">
        <div class="card-header"><i class="fas fa-images me-1"></i> Arsip Galeri Struk & Bukti Transfer Warga</div>
        <div class="card-body">
            <div class="row">
                @forelse ($galleries as $data)
                    <div class="col-md-4 mb-4">
                        <div class="card h-100 shadow-sm">
                            <a href="{{ asset('storage/' . $data->foto) }}" 
                               data-lightbox="galeri-iuran" 
                               data-title="{{ $data->judul }} - {{ $data->keterangan }}">
                                <img src="{{ asset('storage/' . $data->foto) }}" 
                                     class="card-img-top" 
                                     style="height: 200px; object-fit: cover;" 
                                     alt="{{ $data->judul }}">
                            </a>
                            <div class="card-body">
                                <h5 class="card-title">
                                    <a href="{{ route('galeri.detail', $data->galeri_seo) }}" class="text-decoration-none">
                                        {{ $data->judul }}
                                    </a>
                                </h5>
                                <p class="card-text text-muted small">{{ Str::limit($data->keterangan, 80) }}</p>
                            </div>
                            <div class="card-footer d-flex justify-content-between">
                                <a href="{{ route('galeri.detail', $data->galeri_seo) }}" class="btn btn-sm btn-info text-white">Detail (SEO)</a>
                                @if(Auth::check() && Auth::user()->level == 'admin')
                                    <div>
                                        <a href="{{ route('galeri.edit', $data->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                        <form action="{{ route('galeri.destroy', $data->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus bukti pembayaran ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-danger">Hapus</button>
                                        </form>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-4">
                        <h5 class="text-muted">Belum ada data galeri bukti pembayaran.</h5>
                    </div>
                @endforelse
            </div>

            <div class="d-flex justify-content-center mt-3">
                {{ $galleries->links() }}
            </div>
        </div>
    </div>
</div>
@endsection