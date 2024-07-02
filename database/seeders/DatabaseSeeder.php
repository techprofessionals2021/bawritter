<?php

use Database\Seeders\OrderStatusesTableSeeder;
use Database\Seeders\RecruitmentSettingsSeeder;
use Database\Seeders\RolesTableSeeder;
use Database\Seeders\ServicesTableSeeder;
use Database\Seeders\SettingsTableSeeder;
use Database\Seeders\UrgenciesTableSeeder;
use Database\Seeders\WalletBalanceSeeder;
use Database\Seeders\WorkLevelsTableSeeder;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();

        $this->call([
            // RolesTableSeeder::class,
            // SettingsTableSeeder::class,
            // OrderStatusesTableSeeder::class,
            // ContentsTableSeeder::class,
            // RecruitmentSettingsSeeder::class,
            // WorkLevelsTableSeeder::class,
            // ServicesTableSeeder::class,
            // UrgenciesTableSeeder::class,
            WalletBalanceSeeder::class,
        ]);

        Schema::enableForeignKeyConstraints();
    }
}
