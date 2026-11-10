<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Iuran;
use Illuminate\Support\Facades\Storage; 

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
        $this->validate($request, [
            'id_warga'         => 'required|numeric',
            'nominal'          => 'required|numeric',
            'jatuh_tempo'      => 'required|date',
            'status'           => 'required|string',
            'bukti_pembayaran' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
        ], [
            'id_warga.required'    => 'Kolom ID Warga wajib diisi!',
            'id_warga.numeric'     => 'ID Warga harus berupa angka!',
            'nominal.required'     => 'Kolom nominal uang tidak boleh kosong!',
            'nominal.numeric'      => 'Nominal uang harus diisi dengan angka!',
            'jatuh_tempo.required' => 'Tanggal jatuh tempo wajib ditentukan!',
            'jatuh_tempo.date'     => 'Format tanggal jatuh tempo tidak valid!',
            'status.required'      => 'Status pembayaran harus dipilih!'
        ]);

        $buktiPath = null;
        if ($request->hasFile('bukti_pembayaran')) {
            $buktiPath = $request->file('bukti_pembayaran')->store('bukti_pembayaran', 'public');
        }
        
        $iuran = new \App\Models\Iuran();
        $iuran->id_warga         = $request->id_warga;
        $iuran->nominal          = $request->nominal;
        $iuran->jatuh_tempo      = $request->jatuh_tempo;
        $iuran->status           = $request->status;
        $iuran->bukti_pembayaran = $buktiPath; 
        $iuran->id_periode       = 1; 
        $iuran->save();
    
        return redirect()->route('iuran.index')->with('pesan', 'Data Iuran Berhasil Disimpan!');
    }

    public function edit($id)
    {
        $iuran = \App\Models\Iuran::find($id);
        return view('iuran.edit', compact('iuran'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'id_warga'         => 'required|numeric',
            'nominal'          => 'required|numeric',
            'jatuh_tempo'      => 'required|date',
            'status'           => 'required|string',
            'bukti_pembayaran' => 'nullable|image|mimes:jpg,png,jpeg|max:2048' 
        ]);

        $iuran = \App\Models\Iuran::find($id);
        $iuran->id_warga    = $request->id_warga;
        $iuran->nominal     = $request->nominal;
        $iuran->jatuh_tempo = $request->jatuh_tempo;
        $iuran->status      = $request->status;

        // Logika jika user mengupload bukti pembayaran baru saat edit
        if ($request->hasFile('bukti_pembayaran')) {
            if ($iuran->bukti_pembayaran && Storage::disk('public')->exists($iuran->bukti_pembayaran)) {
                Storage::disk('public')->delete($iuran->bukti_pembayaran);
            }
            $buktiPath = $request->file('bukti_pembayaran')->store('bukti_pembayaran', 'public');
            $iuran->bukti_pembayaran = $buktiPath;
        }

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
            if ($iuran->bukti_pembayaran && Storage::disk('public')->exists($iuran->bukti_pembayaran)) {
                Storage::disk('public')->delete($iuran->bukti_pembayaran);
            }
            
            $iuran->delete();
        }
        
        return redirect()->route('iuran.index')->with('pesan', 'Data Iuran berhasil dihapus!');
    }

    // Membuka form bayar khusus Warga
    public function bayar($id)
    {
        $iuran = \App\Models\Iuran::find($id);
        return view('iuran.bayar', compact('iuran'));
    }

    // Memproses upload foto dari Warga
    public function proses_bayar(Request $request, $id)
    {
        $this->validate($request, [
            'bukti_pembayaran' => 'required|image|mimes:jpg,png,jpeg|max:2048'
        ], [
            'bukti_pembayaran.required' => 'Anda belum memilih foto/gambar bukti pembayaran!',
            'bukti_pembayaran.image' => 'File harus berupa gambar (JPG/PNG)!',
            'bukti_pembayaran.max' => 'Ukuran gambar maksimal 2MB!'
        ]);

        $iuran = \App\Models\Iuran::find($id);

        if ($request->hasFile('bukti_pembayaran')) {
            if ($iuran->bukti_pembayaran && Storage::disk('public')->exists($iuran->bukti_pembayaran)) {
                Storage::disk('public')->delete($iuran->bukti_pembayaran);
            }
            $buktiPath = $request->file('bukti_pembayaran')->store('bukti_pembayaran', 'public');
            $iuran->bukti_pembayaran = $buktiPath;
        }

        $iuran->save();

        return redirect()->route('iuran.index')->with('pesan', 'Bukti pembayaran berhasil dikirim! Silakan tunggu verifikasi Admin.');
    }
    
    public function __construct()
    {
        $this->middleware('auth');
    }
}