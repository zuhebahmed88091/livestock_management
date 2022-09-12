<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;

class UploadData extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ini_set('max_execution_time', 600);
        $path = base_path().'/livestock.sql';
        $sql = file_get_contents($path);
        DB::unprepared($sql);
        
        Artisan::call('storage:link');

    }
}
