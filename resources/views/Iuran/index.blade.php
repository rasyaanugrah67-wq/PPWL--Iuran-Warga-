@extends('layouts.app') 

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4 mb-4">Data Iuran Warga</h1>

    <!-- Menangkap Flash Message -->
    @if(Session::has('pesan'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ Session::get('pesan') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- KOTAK STATISTIK (Dipindah ke atas agar lebih rapi seperti dashboard) -->
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

    <!-- CARD UTAMA UNTUK TABEL -->
    <div class="card mb-4 shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <i class="fas fa-table me-1"></i>
                Daftar Iuran Warga
            </div>
            <!-- Tombol Tambah Data -->
            <a href="{{ route('iuran.create') }}" class="btn btn-sm btn-primary">+ Tambah Data</a>
        </div>
        <div class="card-body">
            
            <!-- Form Pencarian -->
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

            <!-- TABEL -->
            @if(count($data_iuran) == 0)
                <div class="alert alert-warning">Data tidak ditemukan.</div>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover mt-3">
                        <thead class="table-dark">
                            <tr>
                                <th>No</th>
                                <th>Nama Warga</th>
                                <th>Nominal</th>
                                <th>Jatuh Tempo</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data_iuran as $index => $iuran)
                            <tr>
                                <td>{{ $data_iuran->firstItem() + $index }}</td>
                                <td>{{ $iuran->warga->nama ?? 'Tidak Diketahui' }}</td>
                                <td>Rp {{ number_format($iuran->nominal, 0, ',', '.') }}</td>
                                <td>{{ \Carbon\Carbon::parse($iuran->jatuh_tempo)->format('d/m/Y') }}</td>
                                <td>
                                    @if($iuran->status == 'Lunas')
                                        <span class="badge bg-success">Lunas</span>
                                    @else
                                        <span class="badge bg-warning text-dark">Menunggu</span>
                                    @endif
                                </td>
                                <td>
                                    <!-- Tombol Edit -->
                                    <a href="{{ route('iuran.edit', $iuran->id_iuran) }}" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i> Edit</a>

                                    <!-- Tombol Hapus -->
                                    <form action="{{ route('iuran.destroy', $iuran->id_iuran) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus data ini?')"><i class="fas fa-trash"></i> Hapus</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Menampilkan tombol Pagination -->
                <div class="d-flex justify-content-end mt-3">
                    {{ $data_iuran->links('pagination::bootstrap-5') }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection