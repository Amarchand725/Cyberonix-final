<?php

namespace Database\Seeders;

use App\Models\InventoryCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InventoryCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table("inventory_categories")->truncate();
        $array = [
            ["name" => "Camera", "status" => 1],
            ["name" => "Laptop", "status" => 1],
            ["name" => "System", "status" => 1],
            ["name" => "Mouse", "status" => 1],
            ["name" => "Keyboard", "status" => 1],
            ["name" => "Headphones", "status" => 1],
            ["name" => "Storage", "status" => 1],
            ["name" => "LCD", "status" => 1],
            ["name" => "Attendance Machine", "status" => 1],
            ["name" => "Printer", "status" => 1],
            ["name" => "Counting Machine", "status" => 1],
        ];
        foreach ($array as $index => $value) {
            $create = InventoryCategory::create([
                "name" => $value["name"] ?? null,
                "status" => $value['status'],
            ]);
        }
    }
}
