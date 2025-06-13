<?php 
namespace App\traits;

trait ManageImage{
    private static $uploadDir ="public/assets/images/";
    public static function uploadFile(array $file, ?string $uploadFolder =null , array $allowedExtensions=['jpg','jpeg','png']): ?string
    {
        if(!isset($file['error']) || $file['error'] !== UPLOAD_ERR_OK  || !isset($file['tmp_name']) || !is_uploaded_file($file['tmp_name']) ){
            return null;
        }
       
        $fileName = $file['name'];
        $extension = pathinfo($fileName, PATHINFO_EXTENSION);
        if(!in_array($extension ,$allowedExtensions)){
            return null;
        }
        $safeFileName = uniqid('file_', true) . ".". $extension;
        $realPath= realpath(__DIR__."../../../")."/".self::$uploadDir;

        if(isset($uploadFolder)){
            $fullPath= $realPath . $uploadFolder."/". $safeFileName;
        }else{
            $fullPath= $realPath . $safeFileName;
        }

        if($uploadFolder){
            $uploadFolder = trim($uploadFolder);
            $realPath .= $uploadFolder . '/';
        }

        //  Create Folder If Not Exists (used if bass folder in function)
        if(!is_dir($realPath)){
            mkdir($realPath, 0775, true); 
        }

        if(move_uploaded_file($file['tmp_name'] , $fullPath)){
             $relativePath = self::$uploadDir . ($uploadFolder ? $uploadFolder . '/' : '') . $safeFileName;
            return str_replace('\\', '/', $relativePath);
        }
        return null;
        
    }

}