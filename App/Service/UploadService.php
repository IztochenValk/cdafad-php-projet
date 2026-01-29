<?php

namespace App\Service;

use App\Utils\Tools;
use App\Service\Exception\UploadException;

class UploadService
{
    /**
     * Constante du Service
     */
    private const string UPLOAD_DIRECTORY = "assets/img/";
    private const int UPLOAD_SIZE_MAX = 2097152;//2Mo soit 1024 * 1024 *2
    private const array UPLOAD_FORMAT_WHITE_LIST = ["png", "jpeg", "jpg", "webp"];

    /**
     * Méthode pour uploader un fichier
     * @param array $files (super globale Files)
     * @param string $name (nom du fichier)
     * @return string Nom de l'image uploadée
     * @throws UploadException
     */
    public function uploadFile(?array $files): string
    {
        if (!$this->isFileUploadCorrectly($files)) {
            return "https://www.anigaido.com/media/zoo_animaux/101-200/113/gnou-bleu-gnou-a-queue-noire-connochaetes-taurinus3-page.jpg";
        }

        if ($files["size"] > self::UPLOAD_SIZE_MAX) {
            throw new UploadException("La taille du fichier est trop importante");
        }

        $ext = Tools::getFileExtension($files["name"]);

        if (!in_array($ext, self::UPLOAD_FORMAT_WHITE_LIST)) {
            throw new UploadException("Format invalide");
        }

        $newName = uniqid() . "." . $ext;
        move_uploaded_file($files["tmp_name"], self::UPLOAD_DIRECTORY . $newName);

        return $newName;
    }
    /**
     * Méthode pour tester si l'image à bien été uploadée
     * @param array $files (données du fichier)
     * @return bool Vrai si le fichier a été uploadé correctement, faux sinon
     */
    private function isFileUploadCorrectly(array $files): bool
    {
        return !isset($files["tmp_name"]) || empty($files["tmp_name"]);
    }

    /**
     * Méthode pour valider la taille de l'upload
     * @param array $files (données du fichier)
     * @return bool Vrai si la taille est trop importante, faux sinon
     */
    private function validateUploadSize(array $files): bool
    {
        return $files["size"] > self::UPLOAD_SIZE_MAX;
    }

    /**
     * Méthode pour valider le format de l'upload
     * @param string $ext (extension du fichier)
     * @return bool Vrai si le format est valide, faux sinon
     */
    private function validateUploadFormat(string $ext): bool
    {
        return in_array($ext, self::UPLOAD_FORMAT_WHITE_LIST);
    }

    /**
     * Méthode pour renommer le fichier
     * @param string $ext (extension du fichier)
     * @return string Nouveau nom du fichier
     */
    private function renameFile(string $ext): string
    {
        return uniqid() . "." . $ext;
    }


}
