<?php

namespace Database\Factories;

use App\Models\Debtor;
use App\Models\Invoice;
use App\Models\Creditor;
use App\Models\Currency;
use Illuminate\Database\Eloquent\Factories\Factory;

class InvoiceFactory extends Factory
{
    protected $model = Invoice::class;

    public function definition(): array
    {
        return [
            "total_amount"  => $this->faker->randomFloat(1, 20, 30),
            "debtor_id"     => Debtor::factory()->create()->id,
            "creditor_id"   => Creditor::factory()->create()->id,
            "currency_id"   => Currency::USD,
            "due_date"      => $this->faker->dateTimeThisYear(),
            "status"        => Invoice::Initialize,
            "open_date"     => null,
            "paid_date"     => null,
        ];
    }

    
    /**
     * Indicate that the invoice is paid.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function paid()
    {
        return $this->state(function (array $attributes) {
            return [
                'status'      => Invoice::Paid,
                'paid_date'   => $this->faker->dateTimeBetween('-1 week', '+1 week'),
            ];
        });
    }
    
    /**
     * Indicate that the invoice is paid.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function open()
    {
        return $this->state(function (array $attributes) {
            return [
                'status'      => Invoice::Open,
                'open_date'   => $this->faker->dateTimeBetween('-1 week', '+1 week'),
            ];
        });
    }
}
