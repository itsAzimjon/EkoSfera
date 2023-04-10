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
        factory(App\User::class, 50)->create();

    }
}
