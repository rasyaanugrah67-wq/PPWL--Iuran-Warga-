<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Iuran;

class IuranController extends Controller
{
    public function index(Request $request)
    {
        
        $cari = $request->kata; 
        $status = $request->status;
        $batas = 5; 

        $query = Iuran::with('warga');

        if ($cari) {
            $query->whereHas('warga', function($q) use ($cari) {
                $q->where('nama', 'like', "%".$cari."%");
            });
        }

        if ($status && $status != 'Semua Status') {
            $query->where('status', $status);
        }

        $statistik = [
            'total_data' => $query->count(),
            'total_uang' => $query->sum('nominal'),
            'tertinggi'  => $query->max('nominal'),
            'terendah'   => $query->min('nominal'),
        ];
        
        $data_iuran = $query->orderBy('id_iuran', 'desc')->paginate($batas);

        return view('iuran.index', compact('data_iuran', 'cari', 'status', 'statistik'));
    }

    public function create()
{
    return view('iuran.create');
}

public function store(Request $request)
    {
        // Validasi Input Bahasa Indonesia
        $this->validate($request, [
            'id_warga'    => 'required|numeric',
            'nominal'     => 'required|numeric',
            'jatuh_tempo' => 'required|date',
            'status'      => 'required|string'
        ], [
    
            'id_warga.required'    => 'Kolom ID Warga wajib diisi!',
            'id_warga.numeric'     => 'ID Warga harus berupa angka!',
            'nominal.required'     => 'Kolom nominal uang tidak boleh kosong!',
            'nominal.numeric'      => 'Nominal uang harus diisi dengan angka!',
            'jatuh_tempo.required' => 'Tanggal jatuh tempo wajib ditentukan!',
            'jatuh_tempo.date'     => 'Format tanggal jatuh tempo tidak valid!',
            'status.required'      => 'Status pembayaran harus dipilih!'
        ]);

        // Simpan Data ke Database
        $iuran = new \App\Models\Iuran();
        $iuran->id_warga    = $request->id_warga;
        $iuran->nominal     = $request->nominal;
        $iuran->jatuh_tempo = $request->jatuh_tempo;
        $iuran->status      = $request->status;
        $iuran->id_periode  = 1; // Default periode
        $iuran->save();

        // Kembali ke halaman index dengan Flash Message
        return redirect()->route('iuran.index')->with('pesan', 'Data Iuran Berhasil Disimpan!');
    }

    public function edit($id)
    {
        $iuran = \App\Models\Iuran::find($id);
        // Kirim data tersebut ke halaman form edit
        return view('iuran.edit', compact('iuran'));
    }

    public function update(Request $request, $id)
    {
        //Validasi Input
        $this->validate($request, [
            'id_warga'    => 'required|numeric',
            'nominal'     => 'required|numeric',
            'jatuh_tempo' => 'required|date',
            'status'      => 'required|string'
        ]);

        // Cari data lama dan timpa dengan data baru
        $iuran = \App\Models\Iuran::find($id);
        $iuran->id_warga    = $request->id_warga;
        $iuran->nominal     = $request->nominal;
        $iuran->jatuh_tempo = $request->jatuh_tempo;
        $iuran->status      = $request->status;
        $iuran->save();

        return redirect()->route('iuran.index')->with('pesan', 'Data Iuran Berhasil Diperbarui!');
    }

    public function search(Request $request)
    {
        $batas = 5;
        $cari = $request->kata;
            
        $data_iuran = Iuran::with('warga')
            ->where('status', 'like', "%".$cari."%")
            ->orWhere('nominal', 'like', "%".$cari."%")
            ->orWhereHas('warga', function($q) use ($cari) {
                $q->where('nama', 'like', "%".$cari."%");
            })
            ->paginate($batas);
            
        $jumlah_iuran = $data_iuran->count();
        $no = $batas * ($data_iuran->currentPage() - 1);
        
        return view('iuran.search', compact('jumlah_iuran', 'data_iuran', 'no', 'cari'));
    }
    
    public function destroy($id)
    {
        $iuran = \App\Models\Iuran::find($id);
        if($iuran){
            $iuran->delete();
        }
        
        // Flash Message setelah dihapus
        return redirect()->route('iuran.index')->with('pesan', 'Data Iuran berhasil dihapus!');
    }
}