@extends('layouts.app') 

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4 mb-4">Data Iuran Warga</h1>

    @if(Session::has('pesan'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ Session::get('pesan') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card bg-primary text-white mb-4 shadow-sm">
                <div class="card-body">
                    <strong>Total Transaksi</strong>
                    <h3 class="mb-0">{{ $statistik['total_data'] ?? 0 }} Data</h3>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-success text-white mb-4 shadow-sm">
                <div class="card-body">
                    <strong>Uang Terkumpul</strong>
                    <h3 class="mb-0">Rp {{ number_format($statistik['total_uang'] ?? 0, 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-warning text-white mb-4 shadow-sm">
                <div class="card-body">
                    <strong>Tagihan Tertinggi</strong>
                    <h3 class="mb-0">Rp {{ number_format($statistik['tertinggi'] ?? 0, 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-danger text-white mb-4 shadow-sm">
                <div class="card-body">
                    <strong>Tagihan Terendah</strong>
                    <h3 class="mb-0">Rp {{ number_format($statistik['terendah'] ?? 0, 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-4 shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <i class="fas fa-table me-1"></i>
                Daftar Iuran Warga
            </div>
            
            <!-- Proteksi Tombol Tambah Data: Hanya Admin -->
            @if(Auth::check() && Auth::user()->level == 'admin')
                <a href="{{ route('iuran.create') }}" class="btn btn-sm btn-primary">+ Tambah Data</a>
            @endif
        </div>

        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-4 ms-auto">
                    <form action="{{ route('iuran.search') }}" method="GET">
                        @csrf
                        <div class="input-group">
                            <input type="text" name="kata" class="form-control" placeholder="Cari data warga...">
                            <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i></button>
                        </div>
                    </form>
                </div>
            </div>
            
            @if(count($data_iuran) == 0)
                <div class="alert alert-warning">Data tidak ditemukan.</div>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover mt-3 align-middle">
                        <thead class="table-dark text-center">
                            <tr>
                                <th>No</th>
                                <th>Nama Warga</th>
                                <th>Nominal</th>
                                <th>Jatuh Tempo</th>
                                <th>Status</th>
                                <th>Bukti Pembayaran</th>
                                <th>Aksi</th> 
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data_iuran as $index => $iuran)
                            <tr>
                                <td class="text-center">{{ $data_iuran->firstItem() + $index }}</td>
                                <td>{{ $iuran->warga->nama ?? 'Tidak Diketahui' }}</td>
                                <td>Rp {{ number_format($iuran->nominal, 0, ',', '.') }}</td>
                                <td class="text-center">{{ \Carbon\Carbon::parse($iuran->jatuh_tempo)->format('d/m/Y') }}</td>
                                <td class="text-center">
                                    @if($iuran->status == 'Lunas')
                                        <span class="badge bg-success">Lunas</span>
                                    @else
                                        <span class="badge bg-warning text-dark">Menunggu</span>
                                    @endif
                                </td>
                                
                                <!-- Menampilkan Gambar Bukti -->
                                <td class="text-center">
                                    @if($iuran->bukti_pembayaran)
                                        <a href="{{ asset('storage/' . $iuran->bukti_pembayaran) }}" target="_blank">
                                            <img src="{{ asset('storage/' . $iuran->bukti_pembayaran) }}" alt="Bukti" class="img-thumbnail" style="max-height: 80px; max-width: 80px; object-fit: cover;">
                                        </a>
                                    @else
                                        <span class="text-muted small">Tidak ada</span>
                                    @endif
                                </td>
                                
                                <!-- Kolom Aksi Dinamis -->
                                <td class="text-center">
                                    @if(Auth::check() && Auth::user()->level == 'admin')
                                        <!-- Tampilan Jika Login Sebagai Admin -->
                                        <a href="{{ route('iuran.edit', $iuran->id_iuran) }}" class="btn btn-sm btn-warning mb-1"><i class="fas fa-edit"></i> Edit</a>
                                        <form action="{{ route('iuran.destroy', $iuran->id_iuran) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger mb-1" onclick="return confirm('Yakin ingin menghapus data ini?')"><i class="fas fa-trash"></i> Hapus</button>
                                        </form>
                                    @else
                                        <!-- Tampilan Jika Login Sebagai Warga -->
                                        @if($iuran->status == 'Menunggu')
                                            @if($iuran->bukti_pembayaran)
                                                <span class="badge bg-info text-dark">Menunggu Verifikasi Admin</span>
                                            @else
                                                <a href="{{ route('iuran.bayar', $iuran->id_iuran) }}" class="btn btn-sm btn-primary">
                                                    <i class="fas fa-upload"></i> Upload Bukti
                                                </a>
                                            @endif
                                        @else
                                            <span class="badge bg-success"><i class="fas fa-check"></i> Selesai</span>
                                        @endif
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="d-flex justify-content-end mt-3">
                    {{ $data_iuran->links('pagination::bootstrap-5') }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection