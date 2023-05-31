<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\User;
class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::table('users')->insert([
            'name' => 'Admin',
            'role_id' => '1',
            'email' => 'admin@admin.com',
            'password' => bcrypt('admin'),
        ]);
        // factory(App\User::class, 50)->create();
        
        $regions = [
            "Arnasoy tumani",
            "G'allaorol tumani",
            "Paxtakor tumani",
            "Mirzacho'l tumani",
            "Forish tumani",
            "Yangiobod tumani",
            "Baxmal tumani",
            "Zomin tumani",
            "Zafarobod tumani",
            "Do'stlik tumani",
            "Sh.Rashidov tumani",
            "Zarbdor tumani",
            "Jizzax shahar"
        ];

        foreach ($regions as $region) {
            DB::table('regions')->insert([
                'name' => $region
            ]); 
        }

    }
}
