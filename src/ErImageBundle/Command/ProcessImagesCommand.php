<?php
namespace ErImageBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Console\Helper\ProgressBar;
use Psr\Log\LoggerInterface;

class ProcessImagesCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('er-images:process')
            ->setDescription('')
            ->addArgument(
                'folder',
                InputArgument::REQUIRED,
                'Carpeta dond eestan las imagenes a procesar, e sun timestamp'
            )
            ->addOption(
                'clear',
                null,
                InputOption::VALUE_NONE,
                'eliminar el origen'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
      /** @var $logger LoggerInterface */
      $logger = $this->getContainer()->get('logger');  
     
      $folder = $input->getArgument('folder');
      $mediasUtils = $this->getContainer()->get('medias.utils');
        
      $clear = $input->getOption('clear');
      $rootdir = $this->getContainer()->getParameter('kernel.root_dir') . DIRECTORY_SEPARATOR;
      $newpath = realpath($rootdir."../src/ErImageBundle/Resources/uploads/"). DIRECTORY_SEPARATOR . $folder;
        
      $config = $this->getContainer()->getParameter('er_image.medias');
      $this->path = $config['path'];
  
      $files = new Finder();
      $files->files()->in($newpath);
        
      $output->writeln('<info> Procesing '.count($files).' files</>');
      $logger->info('Starting task: Process '.count($files).' files'); 
        
      $progress = new ProgressBar($output, count($files) * (count($config['square_thumb'])+count($config['wide_thumb'])+count($config['box_thumb'])+count($config['fixed_thumb']) + 1));

      $this->ih = $this->getContainer()->get('image.handling');
      
      $processed = array();
      
      foreach($files as $file)
      {
        foreach($config['square_thumb'] as $size)
        {
          $mediasUtils->fixedThumb($file, $size."x".$size, $folder);
          $progress->advance();
        }
        foreach($config['wide_thumb'] as $size)
        {
          $mediasUtils->wideThumb($file, $size,$folder);
          $progress->advance();
        }
        foreach($config['box_thumb'] as $size)
        {
          $mediasUtils->fixedThumb($file, $size, $folder);
          $progress->advance();
        }
        foreach($config['fixed_thumb'] as $size)
        {
          $mediasUtils->fixedThumb($file, $size, $folder);
          $progress->advance();
        }
        
        $mediasUtils->original($file, $folder);
        
        $processed[] = $file->getPathname();
        
        if($clear)
        {
          unlink($file);
        }
      }
      
      if($clear)
      {
          rmdir($newpath);
      }
      
      $progress->finish();
      
      $logger->info('End task', array('files' => $processed)); 
      
      $output->writeln("\n<comment>Done!</>");
    }
    
    

}
