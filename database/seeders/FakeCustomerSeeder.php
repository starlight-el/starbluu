<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class FakeCustomerSeeder extends Seeder
{
    private int $jumlahPerRun = 1000;

    private array $namaPool = [
        'Ethan', 'Lucas', 'Marco', 'Logan', 'Aiden', 'Caleb', 'Owen', 'Yuandra', 'Julian', 'Levi',
        'Giano', 'Elio', 'Ezra', 'Miles', 'Theo', 'Rehan', 'Felix', 'Oscar', 'Jasper', 'Leo',
        'Nolan', 'Xavier', 'Adrian', 'Zevano', 'Karel', 'Noel', 'Jason', 'Asher', 'Kai', 'Conan',
        'James', 'Chandler', 'Weston', 'Albian', 'Tristan', 'Damien', 'Mahen', 'Evelyn', 'Fred', 'Grant',
        'Chris', 'Jude', 'Martin', 'Matias', 'Zayn', 'Roni', 'Caleb', 'Saka', 'Brian', 'Cole',
        'Fino', 'Grady', 'Adrian', 'Reona', 'Harley', 'Emmett', 'Graham', 'Harlan', 'Ivan', 'Jonas',
        'Kellan', 'Mila', 'Milo', 'Nasha', 'Orion', 'Pierce', 'Quinn', 'Reid', 'Tobias', 'Victor',
        'Jade', 'Brenda', 'Eren', 'Max', 'Nadia', 'Ella', 'Lily', 'Zoe', 'Nora', 'Ivy',
        'Chloe', 'Hazel', 'Violet', 'Luna', 'Stella', 'Willow', 'Hailey', 'Piper', 'Ruby', 'Lina',
        'Elena', 'Winaya', 'Everly', 'Harper', 'Aria', 'Nova', 'Elena', 'Vella', 'Gwen', 'Nindya',
    ];

    public function run(): void
    {
        $emailTerpakai = DB::table('users')
            ->pluck('email')
            ->map(fn ($email) => strtolower($email))
            ->flip()
            ->toArray();

        $passwordHash = Hash::make('12345678');
        $dataBaru = [];
        $dibuat = 0;

        while ($dibuat < $this->jumlahPerRun) {
            $nama = $this->namaPool[array_rand($this->namaPool)];
            $emailDasar = strtolower($nama);
            $email = $emailDasar . '@mail.com';

            $urutan = 1;
            while (isset($emailTerpakai[$email])) {
                $urutan++;
                $email = $emailDasar . $urutan . '@mail.com';
            }

            $emailTerpakai[$email] = true;

            $dataBaru[] = [
                'name' => $nama,
                'email' => $email,
                'password' => $passwordHash,
                'role' => 'user',
                'created_at' => now(),
                'updated_at' => now(),
            ];

            $dibuat++;
        }

        foreach (array_chunk($dataBaru, 500) as $batch) {
            DB::table('users')->insert($batch);
        }

        $this->command->info("$dibuat customer fake berhasil dibuat.");
    }
}