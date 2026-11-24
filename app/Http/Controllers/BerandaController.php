<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BerandaController extends Controller
{
    public function index()
    {
        $judul = 'Selamat Datang di Les Laravel';
        $hari  = 'Hari ke-1';
        $nama  = 'gading bintang fateha';
        $menu  = ['Beranda', 'Halo', 'Tentang', 'Kontak'];
        $kontak = [
            'email'   => 'latihan@example.com',
            'telepon' => '0812-3456-7890',
        ];

        return view('welcome', compact('judul', 'hari', 'nama', 'menu', 'kontak'));
    }
}
