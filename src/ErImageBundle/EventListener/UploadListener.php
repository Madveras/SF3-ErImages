<?php

namespace ErImageBundle\EventListener;

use Doctrine\Common\Persistence\ObjectManager;
use Oneup\UploaderBundle\Event\PostUploadEvent;
use ErImageBundle\Services\MediasUtils;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Repository;
use Symfony\Component\DependencyInjection\Container;


class UploadListener
{
    /**
     * @var ObjectManager
     */
    private $om;

    public function __construct(ObjectManager $om, MediasUtils $mediasUtils, $tmpupload, Container $container, EntityManager $em)
    {

        $this->container = $container;
        $this->om = $om;
        $this->mediasUtils = $mediasUtils;
        $this->tmpupload = realpath($tmpupload);
        $this->em = $em;
    }

    public function onPostUpload(PostUploadEvent $event)
    {
      $response = $event->getResponse();
      
      $gallery = $event->getRequest()->get('gallery');
      
      // si no exiete el bucket de esta galeria lo creamos
      $repository = $this->em->getRepository('ErImageBundle:Bucket');
      $bucket = $repository->findOneByFolder($gallery);
      
      if(!$bucket)
      {
        $bucket = new \ErImageBundle\Entity\Bucket();
        $bucket->setName(date("d M Y H:i:s"));
        $bucket->setFolder($gallery);
        $this->em->persist($bucket);
        $this->em->flush();          
      }
      
      
      $file = $event->getFile();     
      $path = $file->getPath() . DIRECTORY_SEPARATOR;
      $movepath = $this->tmpupload . DIRECTORY_SEPARATOR . $gallery . DIRECTORY_SEPARATOR;
      
      $response = $this->mediasUtils->processUploadedFile($file,$movepath,$bucket);
      
      if($response['success'])
      {
        $session = $this->container->get('session');
        $session->getFlashBag()->add(
            'download',
            $gallery
        );
      }

      return;
    }
}