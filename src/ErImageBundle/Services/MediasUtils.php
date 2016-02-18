<?php
namespace ErImageBundle\Services;

class MediasUtils {
  
  var $black = "#000000";
  var $white = "#ffffff";
  var $grey = "#353535";
  var $blue = "#000099";
  
  public function __construct($imagehandling, $config) {
    $this->ih = $imagehandling;
    $this->path = $config['path'];
    $this->config = $config;
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
  
  public function processUploadedFile($file, $movepath)
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
      
      $file->move($movepath);
      $response['success'] = true;
      
      return $response;  
  }
}

