<?php

namespace ErImageBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use ErImageBundle\Entity\Banner;
use Symfony\Component\HttpFoundation\Response;
use ErImageBundle\Form\Type\BannerType;
use Symfony\Component\Validator\Constraints as Assert;

class BannersController extends Controller
{
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $banners = $em->getRepository('ErImageBundle:Banner')
                       ->findAllOrderedByName();
        
        return $this->render(
            'ErImageBundle:indexBanner.html.twig',
            array('banners'=>$banners)
        );
    }
    
    public function createAction(Request $request)
    {
        $resources_dir = realpath($this->container->getParameter('kernel.root_dir').'/../src/ErImageBundle/Resources');
        $bannerImg = $this->get('image.handling')->open('@ErImageBundle/Resources/images/robapagina-160x600.png');
        
        $banner = new Banner();
        
        $form = $this->createForm(BannerType::class, $banner);
        $form->handleRequest($request);
        
        $cansave = 'hidden';

        // control de formularios       
        if ($form->isSubmitted() ) {
          
          $tempfile = realpath($this->container->getParameter('kernel.root_dir').'/../web/').$form->get('file')->getData(); 
          
          $bannerutils = $this->get('banner.utils');
          $bannerutils->setForm($form);
          $bannerutils->setBase($bannerImg);
           
          $img1 = $bannerutils->thumbnail('file1');
          $img2 = $bannerutils->thumbnail('file2');
          $img3 = $bannerutils->thumbnail('file3');

          
          $cottageNameAdjust = $bannerutils->adjustText('cottage',$resources_dir.'/fonts/arialbd.ttf',144);
          $espaciado = count($cottageNameAdjust) * 20;
          
          
          foreach($cottageNameAdjust as $i => $line)
          {
            $lineSize = imagettfbbox(11, 0, $resources_dir.'/fonts/arialbd.ttf', $line);
            $bannerImg->write($resources_dir.'/fonts/arialbd.ttf', $line, 13, 159 + ($i * 20), 11, 0, $bannerutils->blue, 'left')
                      ->line(13,162 + ($i * 20),13 + ($lineSize[2] - $lineSize[0]),162 + ($i * 20),$bannerutils->blue); // subrayado simulando link
          }
          
          $textAdjust = $bannerutils->adjustText('text', $resources_dir.'/fonts/Arial.ttf',144);

          $text =  implode("\n", $textAdjust); 
          $bannerImg->write($resources_dir.'/fonts/arial.ttf', $text, 13, 161 + $espaciado, 11, 0, $bannerutils->grey, 'left');

          $bannerImg->merge($img1,20,27,121,111)
                    ->merge($img2,20,332,121,111)
                    ->merge($img3,20,468,121,111);

          $cansave = $form->isValid() ? '':'hidden';
          
          if($form->get('save')->isClicked())
          {
            $file = $this->get('image.handling')->open($tempfile)->save('bundles/erimg/images/banners/'.uniqid('banner_').'.jpg','jpg',85);
            unlink($tempfile);
            $banner->setFile($file);
            $em = $this->getDoctrine()->getManager();
            $em->persist($banner);
            $em->flush();
            
            return $this->redirectToRoute('show_banner',array('id'=>$banner->getId(),'download' => true));
          }
          unlink($tempfile);
        }
    
        return $this->render(
            'ErImageBundle:newBanner.html.twig',
            array('bannerImg' => $bannerImg->jpeg(95),"form" => $form->createView(),'cansave' => $cansave)
        );
        

    }
    
    public function showAction(Request $request,Banner $banner)
    {
        if (!$banner) {
            throw $this->createNotFoundException(
                'No banner found for id '.$id
            );
        }
        
        $this->banner = $banner;
        $this->download = $request->get('download',false);
        
        foreach ($this as $var => $val) {
           $template_vars[$var] = $val;
        }

        return $this->render(
            'ErImageBundle:showBanner.html.twig',
            $template_vars
        );
    }
    
    public function deleteAction(Request $request,Banner $banner)
    {
        if (!$banner) {
            throw $this->createNotFoundException(
                'No banner found for id '.$id
            );
        }
        
        @unlink(realpath($this->container->getParameter('kernel.root_dir').'/../web/') . DIRECTORY_SEPARATOR . $banner->getFile());
        $em = $this->getDoctrine()->getManager();
        $em->remove($banner);
        $em->flush();

        return $this->redirectToRoute('list_banner');
    }
    
    public function downloadAction(Request $request, Banner $banner)
    {
      $response = new Response();
      $web_dir = realpath($this->container->getParameter('kernel.root_dir').'/../web');
      
      $filename = $web_dir . DIRECTORY_SEPARATOR . $banner->getFile();
    
      // Set headers
      $response->headers->set('Cache-Control', 'private');
      $response->headers->set('Content-type', mime_content_type($filename));
      $response->headers->set('Content-Disposition', 'attachment; filename="' . basename($filename) . '";');
      $response->headers->set('Content-length', filesize($filename));

      // Send headers before outputting anything
      $response->sendHeaders();

      echo file_get_contents($filename);
      exit;

    }
}
