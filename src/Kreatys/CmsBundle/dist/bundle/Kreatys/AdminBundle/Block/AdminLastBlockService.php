<?php

/*
 * This file is part of the Sonata Project package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Kreatys\AdminBundle\Block;

use Sonata\AdminBundle\Admin\Pool;
use Sonata\BlockBundle\Block\BaseBlockService;
use Sonata\BlockBundle\Block\BlockContextInterface;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class AdminStatsBlockService.
 *
 * @author  Thomas Rabaix <thomas.rabaix@sonata-project.org>
 */
class AdminLastBlockService extends BaseBlockService {

    protected $pool;

    /**
     * @param string                                                     $name
     * @param \Symfony\Bundle\FrameworkBundle\Templating\EngineInterface $templating
     * @param \Sonata\AdminBundle\Admin\Pool                             $pool
     */
    public function __construct($name, EngineInterface $templating, Pool $pool) {
        parent::__construct($name, $templating);
        $this->pool = $pool;
    }

    /**
     * {@inheritdoc}
     */
    public function execute(BlockContextInterface $blockContext, Response $response = null) {
        $admin = $this->pool->getAdminByAdminCode($blockContext->getSetting('code'));
        $datagrid = $admin->getDatagrid();

        $filters = $blockContext->getSetting('filters');
        if (!isset($filters['_per_page'])) {
            $filters['_per_page'] = array('value' => $blockContext->getSetting('limit'));
        }
        
        if(empty($blockContext->getSetting('title'))) {
            $blockContext->setSetting('title', $blockContext->getSetting('text'));
        }

        foreach ($filters as $name => $data) {
            $datagrid->setValue($name, isset($data['type']) ? $data['type'] : null, $data['value']);
        }

        $datagrid->buildPager();

        return $this->renderPrivateResponse($blockContext->getTemplate(), array(
                    'block' => $blockContext->getBlock(),
                    'settings' => $blockContext->getSettings(),
                    'admin_pool' => $this->pool,
                    'admin' => $admin,
                    'pager' => $datagrid->getPager(),
                    'datagrid' => $datagrid,
                        ), $response);
    }

    /**
     * {@inheritdoc}
     */
    public function getName() {
        return 'Admin Last';
    }

    /**
     * {@inheritdoc}
     */
    public function configureSettings(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'icon' => 'fa-line-chart',
            'text' => 'Statistics',
            'title' => '',
            'emptyText' => 'Aucun',
            'filters' => array(),
            'limit' => 1000,
            'template' => 'KreatysAdminBundle:Block:block_last.html.twig',
        ));
    }

    public function setDefaultSettings(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'icon' => 'fa-line-chart',
            'text' => 'Statistics',
            'title' => '',
            'emptyText' => 'Aucun',
            'code' => false,
            'filters' => array(),
            'limit' => 1000,
            'template' => 'KreatysAdminBundle:Block:block_last.html.twig',
        ));
    }

}
