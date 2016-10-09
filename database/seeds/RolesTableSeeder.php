<?php

use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('roles')->delete();
        
        \DB::table('roles')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'Admin',
                'label' => 'User Admin',
                'created_at' => '2016-10-09 15:56:45',
                'updated_at' => '2016-10-09 15:56:45',
            ),
        ));
        
        
    }
}
