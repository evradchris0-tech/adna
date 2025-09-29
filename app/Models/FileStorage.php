<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use SplFileObject;

class FileStorage extends Model
{
    use HasFactory;

    /**
     * @param String $directoriName
     * @param File $file
     *
     * @return Array
     */

    public static function storeFile(String $directoriName, $file, $disk = "public")
    {
        if (File::size($file) < 10000000) {

            $extensionArray = ["image/jpg", "image/jpeg", "image/png"];

            if (in_array(File::mimeType($file), $extensionArray)) {

                $path = Storage::disk($disk)->put($directoriName, $file);

                if ($path) {

                    return  [
                        'type' => "success",
                        'message' => "fichier enregistrer",
                        'path' => $path
                    ];
                } else {
                    return [
                        'type' => "error",
                        'message' => "une erreur est survenue lors de l'envoie de votre fichier"
                    ];
                }
            } else {
                return [
                    'type' => "error",
                    'message' => "l'extension du fichier n'es pas autorisé"
                ];
            }
        } else {
            return [
                'type' => "error",
                'message' => "votre fichier est trop grand"
            ];
        }
    }

    public static function csvToArray($filename, $delimiter = ',')
    {
        $header = null;
        $data = array();
        if (($handle = fopen($filename, 'r')) !== false)
        {
            while (($row = fgetcsv($handle, null, $delimiter)) !== false)
            {
                if (!$header)
                    $header = $row;
                else
                    $data[] = array_combine($header, $row);
            }
            fclose($handle);
        }

        return $data;
    }

    public static function removeFile(String $path,$disk = "public")
    {
        return Storage::disk($disk)->delete($path);
    }
}
