<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class CountriesSeeder extends Seeder
{
    public function run(): void
    {
        $countries = [
            ['id' => Str::uuid(), 'code' => 'EC', 'name' => 'Ecuador', 'created_at' => now(), 'updated_at' => now()],
            ['id' => Str::uuid(), 'code' => 'CO', 'name' => 'Colombia', 'created_at' => now(), 'updated_at' => now()],
            ['id' => Str::uuid(), 'code' => 'VE', 'name' => 'Venezuela', 'created_at' => now(), 'updated_at' => now()],
            ['id' => Str::uuid(), 'code' => 'CL', 'name' => 'Chile', 'created_at' => now(), 'updated_at' => now()],
        ];

        DB::table('countries')->insert($countries);
    }
}
