<?php

namespace App\Traits;

trait Provincia
{
    /**
     * Función para guardar provincia
     * 
     * @param array $data (request)
     * 
     * @return void
     */
    public function guardaProvincia($data) {
        if ($data['pais'] == '724'):
            if ($data['provincia'] == '000'):
                $this->direcciones->provincia_id = 1;
            else:
                $explode_provincia = explode('_', $data['provincia']);
                $this->direcciones->provincia_id = (int) $explode_provincia[0];
            endif;

            $this->direcciones->provincia_otros = null;
        else:
            if ($data['provincia'] == '000'):
                $this->direcciones->provincia_otros = $data['provincia_otros'];
            else:
                $this->direcciones->provincia_otros = 'Provincia desconocida';
            endif;

            $this->direcciones->provincia_id = null;
        endif;
    }
}
