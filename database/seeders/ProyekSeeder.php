<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Proyek;

class ProyekSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['nama_proyek' => 'Pembuatan Jendela Alma', 'tanggal_mulai' => '2022-01-08', 'tanggal_selesai' => '2022-01-27', 'status' => 'Selesai'],
            ['nama_proyek' => 'Pembuatan Gate Dan Instalasi Listrik', 'tanggal_mulai' => '2022-02-02', 'tanggal_selesai' => '2022-02-28', 'status' => 'Selesai'],
            ['nama_proyek' => 'Pembuatan Huruf Dan Papan Nama', 'tanggal_mulai' => '2022-04-23', 'tanggal_selesai' => '2022-04-29', 'status' => 'Selesai'],
            ['nama_proyek' => 'Pembuatan Jendela Pintu Aluminium Dan Kanopi Kaca Temper', 'tanggal_mulai' => '2022-06-19', 'tanggal_selesai' => '2022-06-30', 'status' => 'Selesai'],
            ['nama_proyek' => 'Pembuatan Gazebo', 'tanggal_mulai' => '2022-10-13', 'tanggal_selesai' => '2022-10-31', 'status' => 'Selesai'],
            ['nama_proyek' => 'Pembuatan pagar dan pintu besi', 'tanggal_mulai' => '2022-11-25', 'tanggal_selesai' => '2022-12-01', 'status' => 'Selesai'],
            ['nama_proyek' => 'Pembuatan tralis pintu dan jendela', 'tanggal_mulai' => '2022-12-16', 'tanggal_selesai' => '2022-12-20', 'status' => 'Selesai'],
            ['nama_proyek' => 'Pembuatan Penutup GRILL', 'tanggal_mulai' => '2023-01-01', 'tanggal_selesai' => '2023-01-03', 'status' => 'Selesai'],
            ['nama_proyek' => 'Pembuatan rak baju', 'tanggal_mulai' => '2023-01-20', 'tanggal_selesai' => '2023-02-04', 'status' => 'Selesai'],
            ['nama_proyek' => 'Pembuatan Kuseng dan jendela', 'tanggal_mulai' => '2023-02-05', 'tanggal_selesai' => '2023-02-05', 'status' => 'Selesai'],
            ['nama_proyek' => 'Pembuatan Pintu dan sekat WC Aluminium', 'tanggal_mulai' => '2023-02-22', 'tanggal_selesai' => '2023-02-26', 'status' => 'Selesai'],
            ['nama_proyek' => 'Proyek Ornamen Tempa', 'tanggal_mulai' => '2023-02-28', 'tanggal_selesai' => '2023-03-07', 'status' => 'Selesai'],
            ['nama_proyek' => 'Pembuatan Ralling tangga Balkon Kanopi dan Dico', 'tanggal_mulai' => '2023-03-08', 'tanggal_selesai' => '2023-03-20', 'status' => 'Selesai'],
            ['nama_proyek' => 'Pembuatan Prasasti ACP', 'tanggal_mulai' => '2023-04-12', 'tanggal_selesai' => '2023-04-20', 'status' => 'Selesai'],
            ['nama_proyek' => 'Pembuatan Atap Dapur', 'tanggal_mulai' => '2023-06-03', 'tanggal_selesai' => '2023-06-10', 'status' => 'Selesai'],
        ];

        Proyek::insert($data);
    }
}
