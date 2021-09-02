<?php

namespace Database\Seeders;

use App\Models\Creditor;
use Illuminate\Database\Seeder;

class CreditorTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // dd(Creditor::factory()->make()->toJson());
        Creditor::factory()->count(20)->create();
    }
}
