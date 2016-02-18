<?php

namespace ErImageBundle\EventListener;

use Doctrine\Common\Persistence\ObjectManager;
use Oneup\UploaderBundle\Event\PostUploadEvent;
use ErImageBundle\Services\MediasUtils;


class UploadListener
{
    /**
     * @var ObjectManager
     */
    private $om;

    public function __construct(ObjectManager $om, MediasUtils $mediasUtils, $tmpupload, \Symfony\Component\DependencyInjection\Container $container)
    {

        $this->container = $container;
        $this->om = $om;
        $this->mediasUtils = $mediasUtils;
        $this->tmpupload = realpath($tmpupload);
    }

    public function onPostUpload(PostUploadEvent $event)
    {
      $response = $event->getResponse();
      
      $gallery = $event->getRequest()->get('gallery');
      $file = $event->getFile();     
      $path = $file->getPath() . DIRECTORY_SEPARATOR;
      $movepath = $this->tmpupload . DIRECTORY_SEPARATOR . $gallery . DIRECTORY_SEPARATOR;
      
      $response = $this->mediasUtils->processUploadedFile($file,$movepath);
      
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