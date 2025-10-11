<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Notification;

class NontificationFactory extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $this->command->info('Membuat notifikasi dengan Factory...');

        $users = User::all();

        if ($users->isEmpty()) {
            $this->command->warn('Tidak ada user. Jalankan UserSeeder terlebih dahulu.');
            return;
        }

        foreach ($users as $user) {
            // Buat 10-20 notifikasi random per user
            $count = rand(10, 20);

            Notification::factory()
                ->count($count)
                ->create(['user_id' => $user->id]);

            $this->command->info("✓ Dibuat $count notifikasi untuk: {$user->username}");
        }

        // Buat beberapa notifikasi urgent untuk random users
        $urgentCount = 5;
        for ($i = 0; $i < $urgentCount; $i++) {
            $randomUser = $users->random();

            Notification::factory()
                ->urgent()
                ->unread()
                ->create(['user_id' => $randomUser->id]);
        }

        $total = Notification::count();
        $this->command->info("✅ Total $total notifikasi berhasil dibuat!");
    }
}
