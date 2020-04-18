<?php

namespace Kreatys\CmsBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\ArrayInput;

class MajContainerCommand extends ContainerAwareCommand {

    /**
     * @var OutputInterface 
     */
    private $output;
    protected $newBlock = array(
        '1' => '4-8',
        '2' => '8-4',
        '3' => '6-6',
        '4' => '4-4-4',
        '5' => '9-3',
        '6' => '3-9',
        '7' => '12',
    );
    protected $oldBlock = array('1', '2', '3', '4', '5', '6', '7');

    protected function configure() {
        $this
                ->setName('kcms:maj:update-container')
                ->setDescription('Maj les container après passage en dev-master')
                ->setDefinition(array(
                    new InputArgument('serviceContainer', InputArgument::REQUIRED, 'Nom du service container'),
                    new InputArgument('env', InputArgument::REQUIRED, 'Environement'),
                ))
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        $this->output = $output;

        $em = $this->getContainer()->get('doctrine')->getEntityManager();
        $pages = $em->getRepository('KreatysCmsBundle:Page')->findAll();
        foreach ($pages as $page) {
            foreach ($page->getBlocks() as $block) {
                if ($block->getType() === $input->getArgument('serviceContainer')) {
                    if ($block->getSettings()) {
                        $settings = $block->getSettings();
                        unset($settings['padding_left']);
                        unset($settings['padding_right']);
                        if (in_array($settings['layout'], $this->oldBlock)) {
                            $settings['layout'] = $this->newBlock[$settings['layout']];
                            $settings['layout_offset'] = "";
                        }
                        $block->setSettings($settings);
                        $em->persist($block);
                    }
                }
            }
            $em->flush();
        }

        $cmsPageManager = $this->getContainer()->get('kreatys_cms.manager.cms_page');
        foreach ($pages as $page) {
            if ($page->getEnabled()) {
                $cmsPageManager->publish($page, true);
            }
        }
        $this->generateRoutes($input);

        $output->writeln('Fin');
    }

    private function generateRoutes(InputInterface $input) {
        $command = $this->getApplication()->find('cache:clear');
        $arguments = array(
            '--env' => $input->getArgument('env')
        );
        $arrayInput = new ArrayInput($arguments);
        $returnCode = $command->run($arrayInput, $this->output);
        if ($returnCode == 0) {
            
        }
    }

}
