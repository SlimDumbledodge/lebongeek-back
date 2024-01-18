<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;

class UploaderService
{

    private $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * Upload image
     *
     * @param [type] $content = saisie
     * @param [type] $directory = dossier de destination
     * @param [type] $pictureFile = nom du fichier
     * @return string
     */
    public function upload($content, $directory, $pictureFile): string
    {
        // je sérialise la saisie
        $data = $this->serializer->serialize($content, 'json');
        // je décode la saisie
        $folderPath = $directory;
        // je récupère selement la partie base64 de la saisie
        $image_parts = explode(";base64,", $data);
        // je récupère le type de l'image
        $image_type_aux = explode("image\/", $image_parts[0]);
        // je récupère l'extension de l'image
        $image_type = $image_type_aux[1];
        // je décode l'image
        $image_base64 = base64_decode($image_parts[1]);
        // je crée un nom unique pour l'image
        $file = $folderPath . uniqid() . '.' . $image_type;
        // je récupère le nom de l'image
        $pictureName = explode($pictureFile, $file);

        // Créez une image à partir d'un base64
        $image = imagecreatefromstring($image_base64);
        // je redimensionne l'image
        $newWidth = 500;
        $newHeight = 500;

        // Récupérer les dimensions de l'image
        $imageWidth = imagesx($image);
        $imageHeight = imagesy($image);

        // Calculez les proportions pour redimensionner l'image tout en préservant l'aspect ratio
        $ratio = max($newWidth / $imageWidth, $newHeight / $imageHeight);
        // Calculez les dimensions de la nouvelle image
        $resizedWidth = $imageWidth * $ratio;
        $resizedHeight = $imageHeight * $ratio;

        // Calculez les coordonnées du coin supérieur gauche du rectangle pour que le rectangle soit centré
        $x = ($resizedWidth - $newWidth) / 2;
        $y = ($resizedHeight - $newHeight) / 2;

        // Créez une nouvelle image redimensionnée
        $resizedImage = imagecreatetruecolor($resizedWidth, $resizedHeight);
        imagecopyresampled($resizedImage, $image, 0, 0, 0, 0, $resizedWidth, $resizedHeight, $imageWidth, $imageHeight);

        // Créez une nouvelle image rognée pour éliminer les bandes noires
        $croppedImage = imagecrop($resizedImage, ['x' => $x, 'y' => $y, 'width' => $newWidth, 'height' => $newHeight]);

        // j'écris l'image dans le dossier
        imagejpeg($croppedImage, $file, 100);

        // je retourne le nom de l'image
        return $pictureName[1];
    }

    /**
     * delete image
     *
     * @param [type] $directory = dossier de destination
     * @param [type] $picture = nom du fichier
     * @return void
     */
    public function deletePicture($directory, $picture): JsonResponse
    {
        $file = $directory . $picture;
        // Vérifier si le fichier existe
        if (file_exists($file)) {
            // Supprimer le fichier
            unlink($file);
            // Retourner un message de succès
            return new JsonResponse(["message" => "File deleted successfully"], Response::HTTP_OK);
        }
        // Retourner un message d'erreur
        return new JsonResponse(["message" => "File not found"], Response::HTTP_NOT_FOUND);
    }
}
