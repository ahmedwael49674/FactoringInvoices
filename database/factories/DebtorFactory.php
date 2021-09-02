<?php

namespace Database\Factories;

use App\Models\debtor;
use Illuminate\Database\Eloquent\Factories\Factory;

class DebtorFactory extends Factory
{
    protected $model = debtor::class;

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
