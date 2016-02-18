<?php

namespace ErImageBundle\Uploader\Naming;

use Oneup\UploaderBundle\Uploader\File\FileInterface;
use Oneup\UploaderBundle\Uploader\Naming\NamerInterface;

class ImageNamer implements NamerInterface
{
    public function name(FileInterface $file)
    {   
      return $file->getClientOriginalName();
    }
}

