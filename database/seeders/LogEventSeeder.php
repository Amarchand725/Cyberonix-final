<?php

namespace Database\Seeders;

use App\Models\LogEvent;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LogEventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('log_events')->truncate();

        $array = [
            ['model_name' => '\App\Models\User', 'event_name' => 'Employee Create', 'slug' => 'employee_edit', 'status' => 1],
            ['model_name' => '\App\Models\User', 'event_name' => 'Employee Edit', 'slug' => 'employee_edit', 'status' => 1],
            ['model_name' => '\App\Models\User', 'event_name' => 'Active', 'slug' => 'status_update', 'status' => 1],
            ['model_name' => '\App\Models\User', 'event_name' => 'De Active', 'slug' => 'status_update', 'status' => 1],
            ['model_name' => '\App\Models\User', 'event_name' => 'Shift Add', 'slug' => 'shift_add', 'status' => 1],
            ['model_name' => '\App\Models\User', 'event_name' => 'Permanent', 'slug' => 'permanent', 'status' => 1],
            ['model_name' => '\App\Models\User', 'event_name' => 'Promotion', 'slug' => 'promotion', 'status' => 1],
            ['model_name' => '\App\Models\User', 'event_name' => 'Terminate', 'slug' => 'terminate', 'status' => 1],
            ['model_name' => '\App\Models\User', 'event_name' => 'Remove From List', 'slug' => 'status_update', 'status' => 1],
            ['model_name' => '\App\Models\User', 'event_name' => 'Employee Delete', 'slug' => 'employee_delete', 'status' => 1],
            ['model_name' => '\App\Models\User', 'event_name' => 'Employee Restore', 'slug' => 'employee_restore', 'status' => 1],
            ['model_name' => '\App\Models\User', 'event_name' => 'Absent Report', 'slug' => 'absent_report', 'status' => 1],
        ];

        foreach ($array as $key => $value) {
            $create = LogEvent::create([
                'model_name' => $value['model_name'] ?? null,
                'event_name' => $value['event_name'] ?? null,
                'slug' => $value['slug'] ?? null,
                'status' => $value['status'] ?? null,
            ]);
        }
    }
}
