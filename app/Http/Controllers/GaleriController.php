<?php

namespace App\Http\Controllers;

use App\Models\Galeri;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class GaleriController extends Controller
{
    public function index()
    {
        $galleries = Galeri::whereNotNull('foto')
            ->orderBy('created_at', 'desc')
            ->paginate(6);

        return view('galeri.index', compact('galleries'));
    }

    public function detail($seo)
    {
        $galeri = Galeri::where('galeri_seo', $seo)->firstOrFail();
        return view('galeri.detail', compact('galeri'));
    }

    public function create()
    {
        return view('galeri.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul'      => 'required|max:200',
            'keterangan' => 'required',
            'foto'       => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $path = null;
        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('galeri_17an', 'public');
        }

        Galeri::create([
            'judul'      => $request->judul,
            'galeri_seo' => Str::slug($request->judul) . '-' . time(), 
            'keterangan' => $request->keterangan,
            'foto'       => $path,
        ]);

        return redirect()->route('galeri.index')->with('pesan', 'Dokumentasi kegiatan berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $galeri = Galeri::findOrFail($id);
        return view('galeri.edit', compact('galeri'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'judul'      => 'required|max:200',
            'keterangan' => 'required',
            'foto'       => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $galeri = Galeri::findOrFail($id);

        if ($request->hasFile('foto')) {
            if ($galeri->foto && Storage::disk('public')->exists($galeri->foto)) {
                Storage::disk('public')->delete($galeri->foto);
            }
            $galeri->foto = $request->file('foto')->store('galeri_17an', 'public');
        }

        $galeri->judul      = $request->judul;
        $galeri->galeri_seo = Str::slug($request->judul) . '-' . $galeri->id;
        $galeri->keterangan = $request->keterangan;
        $galeri->save();

        return redirect()->route('galeri.index')->with('pesan', 'Data galeri berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $galeri = Galeri::findOrFail($id);

        if ($galeri->foto && Storage::disk('public')->exists($galeri->foto)) {
            Storage::disk('public')->delete($galeri->foto);
        }

        $galeri->delete();

        return redirect()->route('galeri.index')->with('pesan', 'Data galeri berhasil dihapus!');
    }
}