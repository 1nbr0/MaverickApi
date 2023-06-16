<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\UrlHelper;

class FileUploader
{

    private $relativeUploadsDir;

    public function __construct(
        private $publicPath,
        private $uploadPath,
        private SluggerInterface $slugger,
        private UrlHelper $urlHelper,
    )
    {
        // get uploads directory relative to public path //  "/uploads/"
        $this->relativeUploadsDir = str_replace($this->publicPath, '', $this->uploadPath).'/';
    }

    public function upload(UploadedFile $file): string
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $fileName = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();

        try {
            $file->move($this->getuploadPath(), $fileName);
        } catch (FileException $e) {
            // ... handle exception if something happens during file upload
        }

        return $fileName;
    }

    public function getuploadPath()
    {
        return $this->uploadPath;
    }

    public function getUrl(?string $fileName, bool $absolute = true): ?string
    {
        if (empty($fileName)) return null;

        if ($absolute) {
            return $this->urlHelper->getAbsoluteUrl($this->relativeUploadsDir.$fileName);
        }

        return $this->urlHelper->getRelativePath($this->relativeUploadsDir.$fileName);
    }
}

