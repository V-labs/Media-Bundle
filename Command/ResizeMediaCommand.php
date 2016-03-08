<?php

namespace Vlabs\MediaBundle\Command;

use Vlabs\MediaBundle\Manager\MediaManagerInterface;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ResizeMediaCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('vlabs:media_resize')
            ->setDescription('Resize image')
            ->addArgument('mediaId',InputArgument::REQUIRED)
            ->addArgument('thumb',InputArgument::REQUIRED)
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var EntityManager $em */
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $media = $em->find('VlabsMediaBundle:Media', $input->getArgument('mediaId'));

        /** @var MediaManagerInterface $mediaManager */
        $mediaManager = $this->getContainer()->get('vlabs_media.manager');
        $mediaManager->doResize($media, $input->getArgument('thumb'));
    }
} 