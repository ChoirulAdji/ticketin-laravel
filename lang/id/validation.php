<?php

return [
    'accepted'             => ':attribute harus diterima.',
    'email'                => ':attribute harus berupa alamat email yang valid.',
    'max'                  => [
        'string' => ':attribute tidak boleh lebih dari :max karakter.',
        'file'   => ':attribute tidak boleh lebih dari :max kilobyte.',
    ],
    'min'                  => [
        'string' => ':attribute minimal harus :min karakter.',
    ],
    'required'             => 'Kolom :attribute wajib diisi.',
    'unique'               => ':attribute sudah digunakan.',
    'confirmed'            => 'Konfirmasi :attribute tidak cocok.',
    'string'               => ':attribute harus berupa teks.',
    'in'                   => ':attribute yang dipilih tidak valid.',
    'image'                => ':attribute harus berupa file gambar.',
    'mimes'                => ':attribute harus berformat: :values.',
    'date'                 => ':attribute harus berupa tanggal yang valid.',
    'lowercase'            => ':attribute harus huruf kecil semua.',

    'attributes' => [
        'nama_lengkap'  => 'nama lengkap',
        'email'         => 'email',
        'password'      => 'password',
        'judul'         => 'judul event',
        'kategori'      => 'kategori',
        'lokasi_kota'   => 'kota',
        'venue'         => 'venue',
        'tanggal'       => 'tanggal',
        'waktu'         => 'waktu',
        'gambar_cover'  => 'gambar cover',
        'metode_bayar'  => 'metode pembayaran',
    ],
];
