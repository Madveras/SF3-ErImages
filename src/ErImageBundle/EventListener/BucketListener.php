<?php

namespace ErImageBundle\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use ErImageBundle\Entity\Bucket;

class BucketListener
{
    public function preRemove(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        // only act on some "Product" entity
        if (!$entity instanceof Bucket) {
            return;
        }

        #$entityManager = $args->getEntityManager();
        $folder = $entity->getFolder();
        $uploads_path = realpath("../web/uploads/"). DIRECTORY_SEPARATOR;
        
        try {
         unlink($uploads_path . $folder . '.zip');
        }catch (Exception $e){ }
        
        $files = new \Symfony\Component\Finder\Finder();
        $files->files()->in($uploads_path . $folder);
        
        foreach(iterator_to_array($files,true) as $file)
        {
          try {
           @unlink($file->getRealPath());
          }catch (Exception $e){ }
        }
        
        $folders = new \Symfony\Component\Finder\Finder();
        $folders->directories()->in($uploads_path . $folder);
        
        foreach(iterator_to_array($folders,true) as $f)
        {
          try {
            @rmdir($f->getRealPath());
          }catch (Exception $e){ }
        }
        
        try {
            @rmdir($uploads_path . $folder);
          }catch (Exception $e){ }
    }
}

