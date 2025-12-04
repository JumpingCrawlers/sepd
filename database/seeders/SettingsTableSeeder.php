<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class SettingsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('settings')->insert(array (
            20 => 
            array (
                'id' => 22,
                'key' => 'site.frase_cabecera_publica',
                'display_name' => 'Frase Cabecera Pública',
                'value' => 'La excelencia no es un objetivo, es una actitud',
                'details' => NULL,
                'type' => 'text',
                'order' => 16,
                'group' => 'Site',
            ),
            21 => 
            array (
                'id' => 23,
                'key' => 'site.frase_cabecera_privada',
                'display_name' => 'Frase Cabecera Privada',
                'value' => 'Tu excelencia, Tu sociedad, Nuestro Compromiso',
                'details' => NULL,
                'type' => 'text',
                'order' => 17,
                'group' => 'Site',
            ),
        ));
        
        
    }
}