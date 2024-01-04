<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\String\Slugger\SluggerInterface;

class PdfUploader
{
    public function __construct(
        private string $targetDirectory,
        private SluggerInterface $slugger,
    ){
    }

    public function upload($pdfFile){
        $originalFilename = pathinfo($pdfFile->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $newFilename = $safeFilename.'-'.uniqid().'.'.$pdfFile->guessExtension();

        try {
            $pdfFile->move(
                $this->targetDirectory,
                $newFilename
            );
        } catch (FileException $e) {
            // die($e->getMessage());
            // ... handle exception if something happens during file upload
        }

        return $newFilename;
    }

}