<?php

namespace App\Traits;

// destino de la imagen
use Illuminate\Support\Facades\Storage;
// manipulación de la imagen
use Intervention\Image\Facades\Image;
use Intervention\Image\Constraint;

trait Upload
{

    // Subir una imagen desde un form. Modificado desde VogayerController@upload
    // @param file // fichero imagen
    // @param destino // nombre del fichero de destino completo
    // @param width // ancho máximo de la imagen
    // @return filename
    public function upload($file, $destino = '', $width = 1800)
    {

        $resizeWidth = $width;
        $resizeHeight = null;
        $extension = null;

        // Si hay destino, el nombre final será ese
        if ($destino != '') {
            $fullPath = $destino;
            $extension = substr(basename($destino), strlen(explode('.', basename($destino))[0]) + 1);
        } else {
            // Path por defecto Voyager incluye siempre el MesAnyo en el path
            $path = 'varios/'.date('F').date('Y').'/';
            $filename = basename($file->getClientOriginalName(), '.'.$file->getClientOriginalExtension());
            $filename_counter = 1;

            // Make sure the filename does not exist, if it does make sure to add a number to the end 1, 2, 3, etc...
            while (Storage::disk(config('voyager.storage.disk'))->exists($path.$filename.'.'.$file->getClientOriginalExtension())) {
                $filename = basename($file->getClientOriginalName(), '.'.$file->getClientOriginalExtension()).(string) ($filename_counter++);
            }

            $fullPath = $path.$filename.'.'.$file->getClientOriginalExtension();
        }

        $ext = $file->guessClientExtension();

        if (in_array($ext, ['jpeg', 'jpg', 'png', 'gif'])) {
            $image = Image::make($file)
                ->resize($resizeWidth, $resizeHeight, function (Constraint $constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                })
                ->encode(($extension) ?: $file->getClientOriginalExtension(), 75);

//            // move uploaded file from temp to uploads directory
            if (Storage::disk(config('voyager.storage.disk'))->put($fullPath, (string) $image, 'public')) {
                $status = __('voyager::media.success_uploading');
            } else {
                $status = __('voyager::media.error_uploading');
            }
        } else {
            $status = __('voyager::media.uploading_wrong_type');
        }
        // Devolver el nombre del fichero
        return $fullPath;
    }
    
}
