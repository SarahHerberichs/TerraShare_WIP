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

    //     //Recup l'ensemble du path de l'image
    //     $image = $this->params->get('photos_directory').$imageName;
    //     //Recup l'extension
    //     $explodedImage = explode(".", $imageName);
    //     //définition de la taille voulue
    //     // $imageLayer = imagecreate(500,500);
    //     //Crée les images au format GD à la place du format original
    //     if ($explodedImage[1] === "gif") {

    //         $imageLayer = imagecreatefromgif($image);
    //     } elseif ($explodedImage[1] === "jpeg" || ($explodedImage[1] === "jpg")) {

    //         $imageLayer = imagecreatefromjpeg($image);
    //     } elseif (($explodedImage[1] === "png")) {

    //         $imageLayer = imagecreatefrompng($image);
    //     }
    //     //à partir de l'image au format GD (imageLayer), sauvegarde l'image au format jpeg sous un nom "compress_..."
    //     imagejpeg($imageLayer,$this->params->get('photos_directory') . 'compress_' . $imageName, $outputQuality);
    //     //Supprime l'image d'origine
    //     if (file_exists($this->params->get('photos_directory').$imageName)) {
    //         unlink($this->params->get('photos_directory').$imageName);
    //     }
    // }
    $imagePath = $this->params->get('photos_directory') . $imageName;
 
    if (!file_exists($imagePath)) {
        return false;
    }

    $outputQuality = $this->getOutputQuality($imagePath);

    $outputPath = $this->params->get('photos_directory') . 'compress_' . $imageName;
    $this->resizeAndCompressImage($imagePath, $outputPath, $outputQuality,$imageName);

    unlink($imagePath);

    return true;
}

private function getOutputQuality($imagePath)
{
    // Determine la taille de l'image
    $imageSize = filesize($imagePath); // en octets


    // Determine la qualité de compression en fonction de la taille de l'image
    if ($imageSize > 5 * 1024 * 1024) { // Si l'image est supérieure à 5 Mo
    
        return 50; // qualité de compression de 50 (sur 100)
        
    } elseif ($imageSize > 2 * 1024 * 1024) { // Si l'image est supérieure à 2 Mo
        return 70; // qualité de compression de 70
        
    } else {
        return 80; // qualité de compression de 80 pour les autres cas
    }
}


private function resizeAndCompressImage($sourcePath, $outputPath, $outputQuality,$imageName)
{
  
    $explodedImage = explode(".", $imageName);
    if ($explodedImage[1] === "gif") {
        $image = imagecreatefromgif($sourcePath);

            } elseif ($explodedImage[1] === "jpeg" || ($explodedImage[1] === "jpg")) {
    
         $image = imagecreatefromjpeg($sourcePath);
          } elseif (($explodedImage[1] === "png")) {
    
            $image= imagecreatefrompng($sourcePath);
       }
    // $image = imagecreatefromjpeg($sourcePath);

    // Resize l'image si nécessaire
    // Vous pouvez ajouter une logique de redimensionnement ici si nécessaire

    // Enregistre l'image compressée
    imagejpeg($image, $outputPath, $outputQuality);

    imagedestroy($image);

}
}
// $compress = new ImageWithGd();
// $compress->handle($argv);
