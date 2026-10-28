@extends('layouts.master') 

@section('content')
<div class="container mt-5">
    <h2 class="mb-4">Pencarian Data Iuran Warga</h2>

    <!-- NOTIFIKASI SUKSES PENCARIAN -->
    @if(count($data_iuran))
        <div class="alert alert-success">
            Ditemukan <strong>{{ count($data_iuran) }}</strong> data dengan kata kunci: <strong>{{ $cari }}</strong>
        </div>
    @endif

    <!-- FORM PENCARIAN -->
    <form action="{{ route('iuran.search') }}" method="GET" class="mb-4">
        @csrf
        <input type="text" name="kata" class="form-control" placeholder="Cari..." value="{{ $cari ?? '' }}" style="width: 30%; display: inline; margin-top: 10px; margin-bottom: 10px;">
        <button type="submit" class="btn btn-primary btn-sm">Cari</button>
    </form>

    <!-- PERINGATAN KOSONG -->
    @if(count($data_iuran) == 0)
        <!-- NOTIFIKASI GAGAL / TIDAK DITEMUKAN -->
        <div class="alert alert-warning">
            <h4>Data {{ $cari }} tidak ditemukan</h4>
            <a href="{{ route('iuran.index') }}" class="btn btn-warning mt-2">Kembali</a>
        </div>
    @else
        <table class="table table-bordered table-striped">
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
                    <td>{{ $no + $index + 1 }}</td>
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
                        <a href="{{ route('iuran.edit', $iuran->id_iuran) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('iuran.destroy', $iuran->id_iuran) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus data ini?')">Hapus</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="d-flex justify-content-end">
            {{ $data_iuran->links('pagination::bootstrap-5') }}
        </div>
    @endif

</div>
@endsection