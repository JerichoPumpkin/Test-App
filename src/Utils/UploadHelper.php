<?php

namespace App\Utils;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Gedmo\Sluggable\Util\Urlizer;

class UploadHelper
{
    const IMAGE_PATH = 'images/uploads';
    const IMAGE_UPLOAD_BASE = 'public/';
    private $uploadsPath;
    public function __construct(string $uploadsPath)
    {
        $this->uploadsPath = $uploadsPath;
    }

    public function uploadImage(UploadedFile $uploadedFile): string
    {
        $destination = $this->uploadsPath.'/'.self::IMAGE_UPLOAD_BASE.self::IMAGE_PATH;
                
        $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
        $newFilename = Urlizer::urlize($originalFilename).'-'.uniqid().'.'.$uploadedFile->guessExtension();
        try{
            $uploadedFile->move(
                $destination,
                $newFilename
            );
            chmod($destination . "/" .  $newFilename , 0777);
        }catch(Exception $e){

        }

        return $newFilename;
    }
}
?>