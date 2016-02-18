<?php

namespace ErImageBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

class ErImageExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
      $config = array();
      // let resources override the previous set value
      foreach ($configs as $subConfig) {
          $config = array_merge($config, $subConfig);
      }
      
     foreach($config as $param => $value)
       $container->setParameter('er_image.'.$param, $value);
    }
}