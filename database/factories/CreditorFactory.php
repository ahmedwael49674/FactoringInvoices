<?php

namespace Database\Factories;

use App\Models\Creditor;
use Illuminate\Database\Eloquent\Factories\Factory;

class CreditorFactory extends Factory
{
    protected $model = Creditor::class;

    public function definition(): array
    {
        return [
            'name'           => $this->faker->company(),
            'email'          => $this->faker->unique()->email,
            'phone'          => $this->faker->phoneNumber(),
            'contact_info'   => [
                "address" => $this->faker->address(),
                "cityPrefix" => $this->faker->cityPrefix(),
            ],
        ];
    }
}
