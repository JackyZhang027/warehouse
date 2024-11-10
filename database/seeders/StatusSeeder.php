<?php

namespace Database\Seeders;

use App\Models\Status;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Status::firstOrCreate(
            ['status' => 'new'],
            ['class' => 'badge-primary']
        );

        Status::firstOrCreate(
            ['status' => 'in progress'],
            ['class' => 'badge-warning']
        );
        
        Status::firstOrCreate(
            ['status' => 'partially completed'],
            ['class' => 'badge-success']
        );
        
        Status::firstOrCreate(
            ['status' => 'completed'],
            ['class' => 'badge-dark']
        );
        
        Status::firstOrCreate(
            ['status' => 'cancelled'],
            ['class' => 'badge-danger']
        );
        
    }
}
