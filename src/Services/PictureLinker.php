<?php
/**
 * Created by PhpStorm.
 * User: Emilie Sappracone
 * Date: 20-02-19
 * Time: 16:41
 */

namespace App\Services;


use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class PictureLinker
{
    /**
     * @var string
     */
    private $td;
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * PictureLinker constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager, $brochures_directory)
    {
        $this->em = $entityManager;
        $this->td = $brochures_directory;
    }

    /**
     * @param $request
     * @param $type
     * @return mixed
     */
    public function getUploadedFile($request, $type, $picture)
    {
        // Get bag of file
        $bag = $request->files->all();
        // Get first element in array
        $uploadedFile = reset($bag);
        // Get the UploadedFile object
        $currentFile = $uploadedFile[$type]['name'];
        $picture->setName($currentFile->getClientOriginalName());
        $this->upload($currentFile, $picture);
    }

    /**
     * @param UploadedFile $file
     * @return string
     */
    public function upload(UploadedFile $file, $picture)
    {
        $fileName = md5(uniqid()).'.'.$file->guessExtension();
        $picture->setPath($fileName);
        try {
            // cloudinary here
            $file->move($this->getTargetDirectory(), $fileName);
        } catch (FileException $e) {
            // ... handle exception if something happens during file upload
        }
        return $fileName;
    }

    /**
     * @return string
     */
    public function getTargetDirectory()
    {
        return $this->td;
    }

    /**
     * @param $file
     */
    public function removeFile($file){
        $name = $file->getName();
        unlink($this->targetDirectory . $name);
    }

    public function checkBag($request, $type, $picture){
        // Get bag of file
        $bag = $request->files->all();
        // Get first element in array
        $uploadedFile = reset($bag);
        // Get the UploadedFile object
        $currentFile = $uploadedFile[$type]['name'];
        $request->files->all()[$type]['name'] = $picture;
//        dd($currentFile);
    }
}