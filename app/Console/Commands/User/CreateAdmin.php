<?php

namespace App\Console\Commands\User;

use App\Models\Company\Employee;
use Hash;
use Illuminate\Console\Command;
use Validator;

class CreateAdmin extends Command
{
    protected $signature = 'user:create-admin';

    protected $description = 'Создать пользователя администратора';

    public function handle(): void
    {
        $first_name = $this->ask('Введите имя пользователя');
        $last_name = $this->ask('Введите фамилию пользователя');
        $email = $this->ask('Введите email пользователя');
        $password = $this->secret('Введите пароль пользователя');

        // Валидация входных данных
        $validator = Validator::make([
            'first_name' => $first_name,
            'last_name' => $last_name,
            'email' => $email,
            'password' => $password,
        ], [
            'first_name' => 'required|string|max:64',
            'last_name' => 'required|string|max:64',
            'email' => 'required|string|email|max:255|unique:App\Models\Company\Employee,email',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            $this->error('Ошибка валидации:');

            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }

            return;
        }

        // Создание пользователя
        $user = Employee::create([
            'first_name' => $first_name,
            'last_name' => $last_name,
            'email' => $email,
            'password' => Hash::make($password),
        ]);

        if ($user) {
            $user->is_admin = true;
            $user->save();

            $this->info('Пользователь успешно создан!');
        } else {
            $this->error('Не удалось создать пользователя.');
        }
    }
}
