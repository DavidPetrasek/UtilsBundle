<?php
namespace Psys\UtilsBundle\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Filesystem\Filesystem;


class FileUploader
{    
    public function __construct
    (
        private SluggerInterface $slugger,
        private Filesystem $filesystem,
    )
    {}
    
    /**
     * @param string $targetDir - absolute path where to store the file
     */
    public function saveFile(UploadedFile $file, string $targetDir, string $customFileSystemName = '')
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $mimeType = $file->getMimeType();
        $extension = $file->guessExtension();
        
        if (empty($customFileSystemName))
        {
            $absPath = $this->filesystem->tempnam($targetDir, '', '.'.$extension);            
            $nameFileSystem = basename($absPath);  
        }
        else
        {
            $nameFileSystem = $customFileSystemName;
        }
  
        $file->move($targetDir, $nameFileSystem);
        
        return 
        [
            'nameFileSystem' => $nameFileSystem,
            'nameDisplay' => $safeFilename.'.'.$extension,
            'mimeType' => $mimeType,
        ];
    }
}


?>