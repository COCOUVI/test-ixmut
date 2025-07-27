<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class EmployeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
         DB::table('users')->updateOrinsert([
            'first_name' => 'Xandro',
            'name' => 'XandroTheDev',
            'email' => 'cocouvialexandro74@gmail.com',
            'password' => Hash::make('xandro'), // modifie le mot de passe !
            'role' => 'employe',
        ]);
    }
}
