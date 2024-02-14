<?php
namespace App\Services;

use GdImage;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

use function PHPUnit\Framework\fileExists;

ini_set('memory_limit', -1);

class ImageCompressionService {

    public function __construct(private ParameterBagInterface $params) {}


    function compressImage( $outputQuality, $imageName)
    {
        //Recup l'ensemble du path de l'image
        $image = $this->params->get('photos_directory').$imageName;
        //Recup l'extension
        $explodedImage = explode(".", $imageName);
        //définition de la taille voulue
        $imageLayer = imagecreate(500,500);
        //Crée les images au format GD à la place du format original
        if ($explodedImage[1] === "gif") {

            $imageLayer = imagecreatefromgif($image);
        } elseif ($explodedImage[1] === "jpeg" || ($explodedImage[1] === "jpg")) {

            $imageLayer = imagecreatefromjpeg($image);
        } elseif (($explodedImage[1] === "png")) {

            $imageLayer = imagecreatefrompng($image);
        }
        //à partir de l'image au format GD (imageLayer), sauvegarde l'image au format jpeg sous un nom "compress_..."
        imagejpeg($imageLayer,$this->params->get('photos_directory') . 'compress_' . $imageName, $outputQuality);
        //Supprime l'image d'origine
        if (file_exists($this->params->get('photos_directory').$imageName)) {
            unlink($this->params->get('photos_directory').$imageName);
        }
    }
}

// $compress = new ImageWithGd();
// $compress->handle($argv);
