<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Database\Factories\CompanyUuid;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {



        return [

            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'email' => Str::uuid() . rand(1, 100) . '@' . $this->faker->freeEmailDomain,
            'phone_number' => fake()->phoneNumber,
           // 'email_verified_at' => now(),
            'password' => 'password',



        ];


    }


}
