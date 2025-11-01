<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = now();

        DB::table('roles')->upsert([
            ['role_id'=>1,'role_name'=>'admin','created_at'=>$now,'updated_at'=>$now],
            ['role_id'=>2,'role_name'=>'manager','created_at'=>$now,'updated_at'=>$now],
            ['role_id'=>3,'role_name'=>'user','created_at'=>$now,'updated_at'=>$now],
        ], ['role_id'], ['role_name','updated_at']);
    }
}
