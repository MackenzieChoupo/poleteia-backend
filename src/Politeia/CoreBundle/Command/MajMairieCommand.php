<?php

namespace Politeia\CoreBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\ORM\EntityManager;

class MajMairieCommand extends ContainerAwareCommand
{
    /**
     * @var EntityManager
     */
    private $em;
    
    protected function configure()
    {
        $this
            ->setName('politeia:mairie:majtheme')
            ->setDescription('Met à jour le type')            
        ;
    }
    
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Début');
        
        /*$this->em = $this->getContainer()->get('doctrine')->getManager();
        
        $sql = 'set foreign_key_checks=0;
                truncate table p_bai_theme;
                set foreign_key_checks=1;';

        $this->em->getConnection()->executeUpdate($sql);
        
        $themes = ['Éducation & Jeunesse', 'Social', 'Urbanisme & travaux', 'Animation'];
        
        $mairies = $this->em->getRepository('PoliteiaCoreBundle:Mairie')->findAll();
        foreach ($mairies as $mairie) {
            foreach($themes as $theme) {
                $baiTheme = new \Politeia\CoreBundle\Entity\BoiteAIdeeTheme();
                $baiTheme->setTitre($theme);
                $baiTheme->setOnline(true);
                $mairie->addBoiteAIdeeTheme($baiTheme);
                $this->em->persist($mairie);
            }
        }
        
        
        $this->em->flush();*/

        $output->writeln('Fin');
    }
    
    private function changeSpecialquote($str)
    {
       return preg_replace("#(&rsquo;)|(&lsquo;)|(&8216;)|(&8217;)|(’)|(‘)#", "'", $str);
    }
}
