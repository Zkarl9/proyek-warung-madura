<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;

class CameraControlController extends Controller
{
    // IP Raspberry Pi — sesuaikan dengan jaringan kamu
    protected string $raspiUrl = 'http://192.168.0.193:5000';

    public function start()
    {
        try {
            Http::timeout(5)->post("{$this->raspiUrl}/start");
            return back()->with('status', 'Deteksi AI berhasil dijalankan.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghubungi Raspberry Pi: ' . $e->getMessage());
        }
    }

    public function stop()
    {
        try {
            Http::timeout(5)->post("{$this->raspiUrl}/stop");
            return back()->with('status', 'Deteksi AI berhasil dihentikan.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghubungi Raspberry Pi: ' . $e->getMessage());
        }
    }
}