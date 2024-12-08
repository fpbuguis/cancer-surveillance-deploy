<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $this->call([
            RxTypesTableSeeder::class,
            SurgeriesTableSeeder::class,
            ChemoProtocolsTableSeeder::class,
            ChemotherapiesTableSeeder::class,
            ImmunotherapiesTableSeeder::class,
            HormonalsTableSeeder::class,
            RadDetailsTableSeeder::class,
            RadiotherapiesTableSeeder::class,
            TreatmentsTableSeeder::class,
        ]);

        // $this->call([
        //     ClearTablesSeeder::class,
        //     // Add other seeders here as needed
        // ]);
    }
}
