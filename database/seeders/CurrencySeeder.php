<?php

namespace Database\Seeders;

use App\Models\Currency;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table("currencies")->truncate();
        $array = [
            ["title" => "Pakistani Rupees (Rs)", "description" => "Pakistani Rupees (Rs)", "code" => "PKR", "symbol" => "Rs."],
            ["title" => "United States Dollar ($)", "description" => "United States Dollar ($)", "code" => "USD", "symbol" => "$"],
        ];
        foreach( $array as $value ){
            Currency::create([
                "title" => $value['title'] ?? null,
                "description" => $value['description'] ?? null,
                "code" => $value['code'] ?? null,
                "symbol" => $value['symbol'] ?? null,
            ]);
        }
    }
}
