<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;


class EncryptUserPasswords extends Command
{
    protected $signature = 'passwords:encrypt-all';
    protected $description = 'Encripta todas las contraseñas de usuarios con bcrypt';

    public function handle()
    {
        if (!$this->confirm('¿Estás seguro de que quieres encriptar TODAS las contraseñas? Esto no se puede deshacer.')) {
            return;
        }
        $this->info('Iniciando el proceso de encriptación...');

        User::chunk(200, function ($users) {
            foreach ($users as $user) {
                // Solo actualiza si no está encriptado
                if (!preg_match('/^\$2[ayb]\$.{56}$/', $user->password)) {
                    DB::table('users')
                        ->where('id', $user->id)
                        ->update(['password' => Hash::make($user->password)]);
                }
            }
        });
        $this->info('¡Proceso completado! Todas las contraseñas han sido encriptadas.');
    }
}
