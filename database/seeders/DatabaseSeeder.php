<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ////////////////////////////////////////////////////////////////
        ////////////////////////////////////////////////////////////////
        // ATENCION
        ////////////////////////////////////////////////////////////////
        ////////////////////////////////////////////////////////////////
        
        ////////////////////////////////////////////////////////////////
        ////////////////////////////////////////////////////////////////
        // Comentar o no el borrado de tablas para cada versión
        ////////////////////////////////////////////////////////////////
        ////////////////////////////////////////////////////////////////
//        \DB::table('data_rows')->delete();
//        \DB::table('data_types')->delete();
//        \DB::table('permission_role')->delete();
//        \DB::table('permissions')->delete();
        
        #iseed_start
//        $this->call(DataTypesTableSeeder::class);
//        $this->call(DataRowsTableSeeder::class);
//        $this->call(PermissionsTableSeeder::class);
//        $this->call(PermissionRoleTableSeeder::class);
//        $this->call(SettingsTableSeeder::class);
//        $this->call(SitioTableSeeder::class);
        #iseed_end
    }
}
