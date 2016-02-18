<?php

namespace ErImageBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Finder\Finder;


class ImagesController extends Controller
{
    public function indexAction(Request $request)
    {              
        $download = $request->get('download') ?: false;
        
        return $this->render(
            'ErImageBundle:indexImage.html.twig',
            array("download" => $download)
        );
    }
    
    public function downloadAction(Request $request)
    {
        $newpath = realpath("../src/ErImageBundle/Resources/uploads/"). DIRECTORY_SEPARATOR . $request->get('gallery') . DIRECTORY_SEPARATOR;
                
        $files = new Finder();
        $files->files()->in($newpath);
        
        $zip = new \ZipArchive();
        $zipName = 'fotos-'.date("YmdHis").".zip";
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
