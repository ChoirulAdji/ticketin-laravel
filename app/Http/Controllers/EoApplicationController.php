<?php

namespace App\Http\Controllers;

use App\Models\EoApplication;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class EoApplicationController extends Controller
{
    // Form daftar jadi EO
    public function create(): View|RedirectResponse
    {
        $user = Auth::user();

        // Sudah EO
        if ($user->isPengelola()) {
            return redirect()->route('pengelola.dashboard');
        }

        // Sudah apply, cek statusnya
        $app = $user->eoApplication;
        if ($app) {
            return view('eo.status', compact('app'));
        }

        return view('eo.daftar');
    }

    // Simpan pengajuan
    public function store(Request $request): RedirectResponse
    {
        $user = Auth::user();

        if ($user->isPengelola() || $user->eoApplication) {
            return redirect()->route('eo.daftar');
        }

        $request->validate([
            'nama_organisasi'   => ['required', 'string', 'max:255'],
            'jenis_entitas'     => ['required', 'string'],
            'skala_event'       => ['required', 'string'],
            'alamat_organisasi' => ['required', 'string'],
            'no_hp_bisnis'      => ['required', 'string', 'max:20'],
            'bank'              => ['required', 'string'],
            'nomor_rekening'    => ['required', 'string', 'max:30'],
            'nama_rekening'     => ['required', 'string', 'max:255'],
            'dokumen_legalitas' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
        ]);

        $dokumenPath = null;
        if ($request->hasFile('dokumen_legalitas')) {
            $dokumenPath = $request->file('dokumen_legalitas')->store('eo-dokumen', 'public');
        }

        EoApplication::create([
            'user_id'           => $user->id,
            'nama_organisasi'   => $request->nama_organisasi,
            'jenis_entitas'     => $request->jenis_entitas,
            'skala_event'       => $request->skala_event,
            'alamat_organisasi' => $request->alamat_organisasi,
            'website'           => $request->website,
            'npwp'              => $request->npwp,
            'no_hp_bisnis'      => $request->no_hp_bisnis,
            'bank'              => $request->bank,
            'nomor_rekening'    => $request->nomor_rekening,
            'nama_rekening'     => $request->nama_rekening,
            'dokumen_legalitas' => $dokumenPath,
            'status'            => 'pending',
        ]);

        return redirect()->route('eo.status')->with('success', 'Pengajuan EO berhasil dikirim! Kami akan memproses dalam 1-3 hari kerja.');
    }

    // Halaman status pengajuan
    public function status(): View|RedirectResponse
    {
        $user = Auth::user();

        if ($user->isPengelola()) {
            return redirect()->route('pengelola.dashboard');
        }

        $app = $user->eoApplication;
        if (!$app) {
            return redirect()->route('eo.daftar');
        }

        return view('eo.status', compact('app'));
    }
}
