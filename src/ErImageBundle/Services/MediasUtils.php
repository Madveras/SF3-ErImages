<?php
namespace ErImageBundle\Services;

use Symfony\Component\HttpFoundation\File\Exception\UploadException;
use Doctrine\ORM\EntityManager;

class MediasUtils {
  
  var $black = "#000000";
  var $white = "#ffffff";
  var $grey = "#353535";
  var $blue = "#000099";
  
  public function __construct($imagehandling, $config, EntityManager $em) {
    $this->ih = $imagehandling;
    $this->path = $config['path'];
    $this->config = $config;
    $this->em = $em;
    
  }
  
  public function wideThumb($file,$size, $folder)
  {
      $image = $this->ih->open($file->getPathname());
      $image->scaleResize($size)->save($this->path. DIRECTORY_SEPARATOR . $folder . DIRECTORY_SEPARATOR . $size . DIRECTORY_SEPARATOR . $file->getBasename());
  }
    
  public function fixedThumb($file,$size, $folder)
  {
      $image = $this->ih->open($file->getPathname());
      $size = explode('x',$size);
      $image->zoomCrop($size[0],$size[1],"#ffffff",'center','center')->save($this->path. DIRECTORY_SEPARATOR . $folder . DIRECTORY_SEPARATOR . $size[0] . DIRECTORY_SEPARATOR . $file->getBasename());
  }
  
  public function original($file,$folder)
  {
    $image = $this->ih->open($file->getPathname());
    $image->save($this->path. DIRECTORY_SEPARATOR . $folder . DIRECTORY_SEPARATOR . 'original' . DIRECTORY_SEPARATOR . $file->getBasename()); 
  }
  
  public function deleteExpiredBuckets()
  {
    $buckets = $this->em->getRepository('ErImageBundle:Bucket')->findExpired();
    foreach($buckets as $bucket)
    {
      $this->em->remove($bucket);
    }
    $this->em->flush();
  }
  
  public function processUploadedFile($file, $movepath,$bucket)
  {
    
    if(!strstr($file->getMimeType(), "image/"))
      {       
        unlink($file);
        $response['success'] = false;
        $response['error'] ="Invalid file type";
        throw new UploadException('Invalid file type');
        return $response;
      }
      
      $image = $this->ih->open($file->getPathname());

      if($image->width() < $this->config['min_width'])
      {
        $image->scaleResize(720);
      }
      
      $image->save($file->getPathname());
      
      $identifier = explode('.',$file->getBasename());
      $identifier = $identifier[0];
      
      $media = new \ErImageBundle\Entity\Media();
      $media->setIdentifier($identifier);
      $media->setBucket($bucket);
      
      $file->move($movepath);
      $response['success'] = true;
      
      $this->em->persist($media);
      $this->em->flush();

      return $response;  
  }
}