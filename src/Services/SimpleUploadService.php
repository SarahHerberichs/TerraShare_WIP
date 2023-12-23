<?php
namespace App\Services;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class SimpleUploadService{

    public function __construct(private ParameterBagInterface $params) {}

    public function uploadImage(UploadedFile $file){
        //Recup le nom initial du fichier client
        $original_file_name = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        //Ajout d'un randomnumber et de l'extension
        $new_file_name =$original_file_name.'-'.uniqid().'.'.$file->guessExtension();
        //(voir yaml)
        $path_destination = $this->params->get('photos_directory');
        //déplace et stocke
        $file->move(
            $path_destination,
            $new_file_name
        );
        return $new_file_name;
    }
}