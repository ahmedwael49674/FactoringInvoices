<?php

namespace Database\Seeders;

use App\Models\Debtor;
use Illuminate\Database\Seeder;

class DebtorTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Debtor::factory()->count(20)->create();
    }
}
