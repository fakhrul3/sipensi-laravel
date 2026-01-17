<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CreateAdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Cek struktur tabel users
        $columns = DB::getSchemaBuilder()->getColumnListing('users');
        $hasUsername = in_array('username', $columns);
        $hasEmail = in_array('email', $columns);
        $hasIsVerify = in_array('is_verify', $columns);
        $hasName = in_array('name', $columns);

        // Cek apakah user sudah ada
        $existingUser = null;
        if ($hasUsername) {
            $existingUser = DB::table('users')->where('username', 'coba')->first();
        } elseif ($hasEmail) {
            $existingUser = DB::table('users')->where('email', 'coba@example.com')->first();
        }

        if (!$existingUser) {
            $userData = [
                'password' => Hash::make('12345678'),
                'created_at' => now(),
                'updated_at' => now(),
            ];

            // Tambahkan kolom sesuai yang ada
            if ($hasUsername) {
                $userData['username'] = 'coba';
            }
            if ($hasEmail) {
                $userData['email'] = 'coba@example.com';
                $userData['email_verified_at'] = now();
            }
            if ($hasName) {
                $userData['name'] = 'Admin Coba';
            }
            if ($hasIsVerify) {
                $userData['is_verify'] = 1;
            }

            DB::table('users')->insert($userData);

            $this->command->info('User "coba" berhasil dibuat dengan password: 12345678');
        } else {
            // Update password jika user sudah ada
            $updateData = [
                'password' => Hash::make('12345678'),
                'updated_at' => now(),
            ];

            if ($hasIsVerify) {
                $updateData['is_verify'] = 1;
            }

            DB::table('users')
                ->where('id', $existingUser->id)
                ->update($updateData);

            $this->command->info('Password user "coba" berhasil diupdate ke: 12345678');
        }
    }
}
