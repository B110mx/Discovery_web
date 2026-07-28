<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class EnsurePrimarySuperAdmin extends Command
{
    protected $signature = 'admin:ensure-primary
                            {--name= : Nombre que se usará solamente si la cuenta no existe}
                            {--reset-password : Restablece la contraseña después de una confirmación explícita}';

    protected $description = 'Comprueba o recupera el superadmin principal sin cambiar sus credenciales por defecto';

    public function handle(): int
    {
        $user = User::query()
            ->where('email', User::PRIMARY_SUPER_ADMIN_EMAIL)
            ->first();

        if ($user && ! $this->option('reset-password')) {
            $this->info("El superadmin principal ya existe: {$user->email}");
            $this->line('La contraseña actual no fue modificada.');

            return self::SUCCESS;
        }

        if (! $this->input->isInteractive()) {
            $this->error('La creación o recuperación de contraseña debe ejecutarse en una terminal interactiva.');

            return self::FAILURE;
        }

        if (
            $user
            && ! $this->confirm(
                "¿Confirmas que deseas restablecer la contraseña de {$user->email}?",
                false,
            )
        ) {
            $this->warn('Operación cancelada. La contraseña actual no fue modificada.');

            return self::SUCCESS;
        }

        $password = $this->requestPassword();

        if ($password === null) {
            return self::FAILURE;
        }

        if ($user) {
            $user->update([
                'password' => $password,
                'role' => 'super_admin',
            ]);
            $this->info("Contraseña restablecida para {$user->email}.");

            return self::SUCCESS;
        }

        $name = trim((string) ($this->option('name') ?: $this->ask(
            'Nombre del superadmin principal',
            'Superadmin principal',
        )));

        User::query()->create([
            'name' => $name ?: 'Superadmin principal',
            'email' => User::PRIMARY_SUPER_ADMIN_EMAIL,
            'password' => $password,
            'role' => 'super_admin',
            'email_verified_at' => now(),
        ]);

        $this->info('Superadmin principal creado correctamente.');

        return self::SUCCESS;
    }

    private function requestPassword(): ?string
    {
        $password = $this->secret(
            'Nueva contraseña (mínimo 12 caracteres, mayúscula, minúscula, número y símbolo)',
        );
        $confirmation = $this->secret('Confirma la nueva contraseña');

        $validator = Validator::make(
            [
                'password' => $password,
                'password_confirmation' => $confirmation,
            ],
            [
                'password' => [
                    'required',
                    'string',
                    'confirmed',
                    Password::min(12)->mixedCase()->numbers()->symbols(),
                ],
            ],
        );

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }

            return null;
        }

        return $password;
    }
}
