<?php
namespace ErImageBundle\Services;

class BannerUtils {
  
  var $black = "#000000";
  var $white = "#ffffff";
  var $grey = "#353535";
  var $blue = "#000099";
  
  public function __construct($requestStack, $imagehandling = null) {
    $this->ih = $imagehandling;
    $this->request = $requestStack->getCurrentRequest();
  }
  
  public function setForm($form)
  {
    $this->form = $form;
  }
  
  public function setBase($bannerImg)
  {
    $this->bannerImg = $bannerImg;
  }
  
  public function thumbnail($image, $width = 121, $height = 113)
  {
      if($this->get($image))
      {
        $pathname = $this->get($image)->getPathname();
        
        return $this->ih->open( $pathname)->zoomCrop(121,113,$this->white,'center','center');        
      }
      else
      {
        return $this->ih->create(121, 113)->fill($this->white);
      }
  }
  
  public function adjustText($text,$font, $width, $fontSize = 11)
  {
    
    $text = $this->get($text) ?: $text;
    
    $words = explode(' ', $text);
    $lines = array($words[0]);
    $currentLine = 0;
    
    for($i = 1; $i < count($words); $i++)
    {
      $lineSize = imagettfbbox($fontSize, 0, $font, $lines[$currentLine] . ' ' . $words[$i]);
      if($lineSize[2] - $lineSize[0] < $width)
      {
        $lines[$currentLine] .= ' ' . $words[$i];
      }
      else
      {
        $currentLine++;
        $lines[$currentLine] = $words[$i];
      }
    }
    
    return $lines;
  }
  
  private function get($field)
  {
    $data = false;
    if ($this->form)
    {
      $data = $this->form->get($field)->getData();
    }
    return $data;
  }
  
}

