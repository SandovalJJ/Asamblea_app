<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'email_verified_at' => now(),
            'password' => Hash::make('password'), // o cualquier contraseña predeterminada
            'remember_token' => Str::random(10),
            'rol' => $this->faker->randomElement(['DELEGADO', 'SUPLENTE']), // Asumiendo que tienes roles 'admin' y 'user'
            'cedula' => $this->faker->numerify('#######'),
            'cuenta' => $this->faker->numerify('#######'),
            'agencia' => $this->faker->word,
            'telefono' => $this->faker->phoneNumber,
        ];
    }
}
