<?php


use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use MongoDB\BSON\UTCDateTime;

class UsersTableSeeder  extends Seeder
{
    public function run()
    {

        DB::collection('users')->insert([

            [
                'name' => "John",
                'surname' => "Doe",
                'email' => 'test@test.com',
                'password' => bcrypt('demo'),
                'remember_token' => '',
                'email_verified_at' => new UTCDateTime(new DateTime('now')),
                'created_at' => new UTCDateTime(new DateTime('now')),
                'updated_at' => new UTCDateTime(new DateTime('now')),
            ]
            ]);
    }
}
