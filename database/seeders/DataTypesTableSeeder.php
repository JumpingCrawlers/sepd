<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DataTypesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('data_types')->delete();
        
        \DB::table('data_types')->insert(array (
            0 => 
            array (
                'id' => 3,
                'name' => 'users',
                'slug' => 'users',
                'display_name_singular' => 'User',
                'display_name_plural' => 'Users',
                'icon' => 'voyager-person',
                'model_name' => 'TCG\\Voyager\\Models\\User',
                'policy_name' => 'TCG\\Voyager\\Policies\\UserPolicy',
                'controller' => '',
                'description' => '',
                'generate_permissions' => 1,
                'server_side' => 0,
                'details' => NULL,
                'created_at' => '2018-03-05 12:01:39',
                'updated_at' => '2018-03-05 12:01:39',
            ),
            1 => 
            array (
                'id' => 5,
                'name' => 'menus',
                'slug' => 'menus',
                'display_name_singular' => 'Menu',
                'display_name_plural' => 'Menus',
                'icon' => 'voyager-list',
                'model_name' => 'TCG\\Voyager\\Models\\Menu',
                'policy_name' => NULL,
                'controller' => NULL,
                'description' => NULL,
                'generate_permissions' => 1,
                'server_side' => 0,
                'details' => NULL,
                'created_at' => '2018-03-05 12:01:39',
                'updated_at' => '2018-04-02 11:19:03',
            ),
            2 => 
            array (
                'id' => 6,
                'name' => 'roles',
                'slug' => 'roles',
                'display_name_singular' => 'Role',
                'display_name_plural' => 'Roles',
                'icon' => 'voyager-lock',
                'model_name' => 'TCG\\Voyager\\Models\\Role',
                'policy_name' => NULL,
                'controller' => NULL,
                'description' => NULL,
                'generate_permissions' => 1,
                'server_side' => 0,
                'details' => NULL,
                'created_at' => '2018-03-05 12:01:39',
                'updated_at' => '2018-03-13 08:37:11',
            ),
            3 => 
            array (
                'id' => 8,
                'name' => 'paginas',
                'slug' => 'paginas',
                'display_name_singular' => 'Página',
                'display_name_plural' => 'Páginas',
                'icon' => 'voyager-file-text',
                'model_name' => 'App\\Pagina',
                'policy_name' => NULL,
                'controller' => NULL,
                'description' => 'Páginas de auto-gestión por parte de SEPD',
                'generate_permissions' => 1,
                'server_side' => 1,
                'details' => '{"order_column":null,"order_display_column":null}',
                'created_at' => '2018-03-27 06:50:11',
                'updated_at' => '2018-05-23 13:02:41',
            ),
            4 => 
            array (
                'id' => 10,
                'name' => 'partesgraficas',
                'slug' => 'partesgraficas',
                'display_name_singular' => 'Parte gráfica',
                'display_name_plural' => 'Partes gráficas',
                'icon' => 'voyager-photos',
                'model_name' => 'App\\Partesgrafica',
                'policy_name' => NULL,
                'controller' => NULL,
                'description' => 'Partes gráficas utilizadas en cualquier parte del sitio, ya sea imágenes, fotos, imagen para un botón...',
                'generate_permissions' => 1,
                'server_side' => 0,
                'details' => '{"order_column":null,"order_display_column":null}',
                'created_at' => '2018-04-10 06:12:14',
                'updated_at' => '2018-06-15 11:37:37',
            ),
            5 => 
            array (
                'id' => 12,
                'name' => 'pastillas',
                'slug' => 'pastillas',
                'display_name_singular' => 'Pastilla',
                'display_name_plural' => 'Pastillas',
                'icon' => 'voyager-pie-chart',
                'model_name' => 'App\\Pastilla',
                'policy_name' => NULL,
                'controller' => NULL,
                'description' => 'Elementos destacados, bloques con información dentro de una página.',
                'generate_permissions' => 1,
                'server_side' => 0,
                'details' => '{"order_column":null,"order_display_column":null}',
                'created_at' => '2018-04-11 14:23:18',
                'updated_at' => '2018-05-28 12:14:08',
            ),
            6 => 
            array (
                'id' => 13,
                'name' => 'sliders',
                'slug' => 'sliders',
                'display_name_singular' => 'Slider',
                'display_name_plural' => 'Sliders',
                'icon' => 'voyager-dot-3',
                'model_name' => 'App\\Slider',
                'policy_name' => NULL,
                'controller' => NULL,
                'description' => 'Sliders con varias diapositivas en las diferentes páginas del sitio web.',
                'generate_permissions' => 1,
                'server_side' => 0,
                'details' => '{"order_column":null,"order_display_column":null}',
                'created_at' => '2018-04-11 15:46:47',
                'updated_at' => '2018-05-31 12:02:33',
            ),
            7 => 
            array (
                'id' => 15,
                'name' => 'pagina_pastilla',
                'slug' => 'pagina-pastilla',
                'display_name_singular' => 'Pagina Pastilla',
                'display_name_plural' => 'Pagina Pastillas',
                'icon' => NULL,
                'model_name' => 'App\\PaginaPastilla',
                'policy_name' => NULL,
                'controller' => NULL,
                'description' => NULL,
                'generate_permissions' => 0,
                'server_side' => 0,
                'details' => '{"order_column":null,"order_display_column":null}',
                'created_at' => '2018-06-26 11:53:26',
                'updated_at' => '2018-06-26 11:53:26',
            ),
            8 => 
            array (
                'id' => 17,
                'name' => 'menu_item_pagina',
                'slug' => 'menu-item-pagina',
                'display_name_singular' => 'Menu Item Pagina',
                'display_name_plural' => 'Menu Item Paginas',
                'icon' => NULL,
                'model_name' => 'App\\MenuItemPagina',
                'policy_name' => NULL,
                'controller' => NULL,
                'description' => NULL,
                'generate_permissions' => 0,
                'server_side' => 0,
                'details' => '{"order_column":null,"order_display_column":null}',
                'created_at' => '2018-06-26 13:57:41',
                'updated_at' => '2018-06-26 13:57:41',
            ),
            9 => 
            array (
                'id' => 19,
                'name' => 'partesgrafica_slider',
                'slug' => 'partesgrafica-slider',
                'display_name_singular' => 'Partesgrafica Slider',
                'display_name_plural' => 'Partesgrafica Sliders',
                'icon' => NULL,
                'model_name' => 'App\\PartesgraficaSlider',
                'policy_name' => NULL,
                'controller' => NULL,
                'description' => NULL,
                'generate_permissions' => 0,
                'server_side' => 0,
                'details' => '{"order_column":null,"order_display_column":null}',
                'created_at' => '2018-06-26 14:04:51',
                'updated_at' => '2018-06-26 14:04:51',
            ),
            10 => 
            array (
                'id' => 20,
                'name' => 'partesgrafica_pastilla',
                'slug' => 'partesgrafica-pastilla',
                'display_name_singular' => 'Partesgrafica Pastilla',
                'display_name_plural' => 'Partesgrafica Pastillas',
                'icon' => NULL,
                'model_name' => 'App\\PartesgraficaPastilla',
                'policy_name' => NULL,
                'controller' => NULL,
                'description' => NULL,
                'generate_permissions' => 0,
                'server_side' => 0,
                'details' => '{"order_column":null,"order_display_column":null}',
                'created_at' => '2018-06-26 16:10:48',
                'updated_at' => '2018-06-26 16:10:48',
            ),
        ));
        
        
    }
}