<?php

namespace Database\Seeders;

use App\Models\Todo;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        if (Todo::count() === 0) {
            Todo::create([
                'title' => 'Mendesain Wireframe & Prototipe Web',
                'description' => 'Membuat wireframe antarmuka untuk fitur dashboard dan halaman kelola tugas.',
                'priority' => 'high',
                'due_date' => Carbon::today(),
                'is_completed' => false,
            ]);

            Todo::create([
                'title' => 'Presentasi Proyek ke Stakeholder',
                'description' => 'Menyiapkan slide demo dan memaparkan hasil sprint minggu ini.',
                'priority' => 'high',
                'due_date' => Carbon::tomorrow(),
                'is_completed' => false,
            ]);

            Todo::create([
                'title' => 'Review Code & Pull Request Tim Backend',
                'description' => 'Memeriksa implementasi endpoint API todolist dan validasi form.',
                'priority' => 'medium',
                'due_date' => Carbon::yesterday(),
                'is_completed' => false,
            ]);

            Todo::create([
                'title' => 'Instalasi Framework Laravel & Konfigurasi Database',
                'description' => 'Melakukan inisialisasi project Laravel 13 dan setup migrasi sqlite.',
                'priority' => 'medium',
                'due_date' => Carbon::now()->subDays(3),
                'is_completed' => true,
                'completed_at' => Carbon::now()->subDays(2),
            ]);

            Todo::create([
                'title' => 'Update Dokumentasi & Panduan Penggunaan',
                'description' => 'Menuliskan cara menjalankan aplikasi, konfigurasi port, dan fitur-fitur utama di README.md.',
                'priority' => 'low',
                'due_date' => Carbon::now()->addDays(5),
                'is_completed' => false,
            ]);

            Todo::create([
                'title' => 'Merapikan Workspace & Backlog Task',
                'description' => 'Kategori ulang backlog berdasarkan prioritas Q4.',
                'priority' => 'low',
                'due_date' => null,
                'is_completed' => false,
            ]);
        }
    }
}
