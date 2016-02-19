<?php

namespace ErImageBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\Session\Session;


class ImagesController extends Controller
{
    public function indexAction(Request $request)
    {              
        
        $path = realpath("../web/uploads/"). DIRECTORY_SEPARATOR;
                
        $uploads = new Finder();
        $uploads->directories()->in($path)->depth('== 0')->sortByName();
        return $this->render(
            'ErImageBundle:indexImage.html.twig',
            array('uploads' => $uploads)
        );
    }
    
    public function viewAction(Request $request)
    {
      $gallery = $request->get('gallery');
      $path = realpath("../web/uploads/"). DIRECTORY_SEPARATOR . $gallery . DIRECTORY_SEPARATOR . '34' . DIRECTORY_SEPARATOR ;
      
      $images = new Finder();
      $images->files()->in($path)->sortByName();
       
      return $this->render(
              'ErImageBundle:viewImage.html.twig',
              array('images' => $images,
                    'gallery' => $gallery
                  )
          );
      
    }
    
    public function uploadAction(Request $request)
    {              
           
        $session = $this->container->get('session');
        $download = $session->getFlashBag()->get('download',array());
        $session->getFlashBag()->clear();
        
        return $this->render(
            'ErImageBundle:uploadImage.html.twig',
             array('download'=>$download)
            );
    }
    
    public function downloadAction(Request $request)
    {
        $newpath = realpath("../src/ErImageBundle/Resources/uploads/"). DIRECTORY_SEPARATOR . $request->get('gallery') . DIRECTORY_SEPARATOR;
                
        $files = new Finder();
        $files->files()->in($newpath);
        
        $zip = new \ZipArchive();
        $zipName = $request->get('gallery').".zip";
        $zip->open('uploads/'. $zipName,  \ZipArchive::CREATE);
        
        foreach ($files as $f) {
            $zip->addFromString( $f->getBasename(),  file_get_contents($f)); 
        }
        $zip->close();

        $response = new Response();
        $response->headers->set('Content-Type', 'application/zip');
        $response->headers->set('Content-disposition','attachment; filename='.$zipName);
        $response->headers->set('Content-Length', filesize("../web/uploads/" . $zipName));
        
        // Send headers before outputting anything
        $response->sendHeaders();
        
        readfile("../web/uploads/" . $zipName);
        
        $console = realpath($this->container->getParameter('kernel.root_dir').'/../bin/'). '/console';
        $cmd = 'USER='.getenv('USER').' nohup php -d memory_limit=-1 ' . $console . ' er-image:process '. $request->get('gallery') . ' --clear &';
        shell_exec($cmd);
        
        exit;
    }
 
}
