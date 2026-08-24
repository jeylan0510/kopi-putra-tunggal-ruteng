<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Bus;
use App\Models\Route;
use App\Models\Schedule;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 0. Seed Users (Admin & Customer)
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@bustiket.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'John Doe',
            'email' => 'user@bustiket.com',
            'password' => Hash::make('password123'),
            'role' => 'user',
        ]);

        // 1. Seed Buses
        $bus1 = Bus::create([
            'nama_bus' => 'Manggala Trans',
            'nomor_polisi' => 'DD 1234 MT',
            'kapasitas' => 32,
            'kelas' => 'Executive',
            'status' => 'Aktif',
        ]);

        $bus2 = Bus::create([
            'nama_bus' => 'Bintang Timur',
            'nomor_polisi' => 'DP 9876 BT',
            'kapasitas' => 40,
            'kelas' => 'Bisnis',
            'status' => 'Aktif',
        ]);

        // 2. Seed Routes
        $route1 = Route::create([
            'asal' => 'Makassar',
            'tujuan' => 'Toraja',
            'jarak' => 315.5,
            'estimasi' => '8 Jam 30 Menit',
        ]);

        $route2 = Route::create([
            'asal' => 'Makassar',
            'tujuan' => 'Palopo',
            'jarak' => 370.2,
            'estimasi' => '9 Jam 15 Menit',
        ]);

        $route3 = Route::create([
            'asal' => 'Toraja',
            'tujuan' => 'Makassar',
            'jarak' => 315.5,
            'estimasi' => '8 Jam 30 Menit',
        ]);

        // 3. Seed Schedules (Tomorrow's date to ensure it's not expired)
        $tomorrow = date('Y-m-d', strtotime('+1 day'));

        Schedule::create([
            'bus_id' => $bus1->id,
            'route_id' => $route1->id,
            'tanggal' => $tomorrow,
            'jam_berangkat' => '19:00:00',
            'jam_tiba' => '03:30:00',
            'harga' => 250000,
            'kursi_tersedia' => 32,
        ]);

        Schedule::create([
            'bus_id' => $bus2->id,
            'route_id' => $route2->id,
            'tanggal' => $tomorrow,
            'jam_berangkat' => '20:00:00',
            'jam_tiba' => '05:15:00',
            'harga' => 180000,
            'kursi_tersedia' => 40,
        ]);

        Schedule::create([
            'bus_id' => $bus1->id,
            'route_id' => $route3->id,
            'tanggal' => $tomorrow,
            'jam_berangkat' => '21:00:00',
            'jam_tiba' => '05:30:00',
            'harga' => 250000,
            'kursi_tersedia' => 32,
        ]);

        // Tambah jadwal Bintang Timur ke rute Makassar -> Toraja agar muncul 2 bus
        Schedule::create([
            'bus_id' => $bus2->id,
            'route_id' => $route1->id,
            'tanggal' => $tomorrow,
            'jam_berangkat' => '20:30:00',
            'jam_tiba' => '05:30:00',
            'harga' => 200000,
            'kursi_tersedia' => 40,
        ]);
    }
}
