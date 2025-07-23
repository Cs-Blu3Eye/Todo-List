<?php

namespace Database\Seeders;

use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TaskSeeder extends Seeder
{
    /**
     * Jalankan seed database.
     */
    public function run(): void
    {
        // Buat user dummy jika belum ada
        $user1 = User::firstOrCreate(
            ['email' => 'user1@example.com'],
            [
                'name' => 'User Satu',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        $user2 = User::firstOrCreate(
            ['email' => 'user2@example.com'],
            [
                'name' => 'User Dua',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        // Hapus task lama untuk menghindari duplikasi saat seeding berulang
        Task::whereIn('user_id', [$user1->id, $user2->id])->delete();

        // Buat task untuk User Satu
        $user1->tasks()->createMany([
            [
                'title' => 'Belajar Laravel',
                'description' => 'Mempelajari dasar-dasar Laravel, routing, controller, dan model.',
                'due_date' => now()->addDays(5),
                'status' => 'pending',
            ],
            [
                'title' => 'Membuat To-Do List App',
                'description' => 'Mengimplementasikan fitur CRUD untuk aplikasi to-do list.',
                'due_date' => now()->addDays(2),
                'status' => 'in_progress',
            ],
            [
                'title' => 'Membeli Kopi',
                'description' => 'Membeli biji kopi arabika dari toko favorit.',
                'due_date' => now()->subDays(1),
                'status' => 'done',
            ],
            [
                'title' => 'Rapat Tim Mingguan',
                'description' => 'Persiapan materi untuk rapat tim mingguan.',
                'due_date' => now()->addDays(1),
                'status' => 'pending',
            ],
        ]);

        // Buat task untuk User Dua
        $user2->tasks()->createMany([
            [
                'title' => 'Desain Landing Page',
                'description' => 'Membuat wireframe dan mockup untuk landing page baru.',
                'due_date' => now()->addDays(7),
                'status' => 'pending',
            ],
            [
                'title' => 'Review Code',
                'description' => 'Mengecek pull request dari anggota tim.',
                'due_date' => now()->addDays(3),
                'status' => 'in_progress',
            ],
            [
                'title' => 'Olahraga Pagi',
                'description' => 'Lari pagi selama 30 menit.',
                'due_date' => now()->subDays(2),
                'status' => 'done',
            ],
        ]);
    }
}

