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
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

/**
 * Class AdminStatsBlockService.
 *
 * @author  Thomas Rabaix <thomas.rabaix@sonata-project.org>
 */
class AdminStatsBlockService extends BaseBlockService
{
    protected $pool;
    
    /**
     * @var TokenStorage 
     */
    protected $securityTokenStorage;
    /**
     * @param string                                                     $name
     * @param \Symfony\Bundle\FrameworkBundle\Templating\EngineInterface $templating
     * @param \Sonata\AdminBundle\Admin\Pool                             $pool
     */
    public function __construct($name, EngineInterface $templating, Pool $pool, TokenStorage $securityTokenStorage)
    {
        parent::__construct($name, $templating);
        $this->pool = $pool;
        $this->securityTokenStorage = $securityTokenStorage;
    }
    /**
     * {@inheritdoc}
     */
    public function execute(BlockContextInterface $blockContext, Response $response = null)
    {
        
        if($blockContext->getSetting('code') === 'citoyen') {
            $mairie = $this->getUser()->getProfil()->getMairie();
            
            $nb = $mairie->getCitoyenAbonnes()->count();            
            
            return $this->renderPrivateResponse('KreatysAdminBundle:Block:block_stats_nb_citoyen.html.twig', array(
                'block'      => $blockContext->getBlock(),
                'settings'   => $blockContext->getSettings(),
                'admin_pool' => null,
                'admin'      => null,
                'pager'      => null,
                'datagrid'   => null,
                'nb'         => $nb
            ), $response);
            
        } else {
        
            $admin = $this->pool->getAdminByAdminCode($blockContext->getSetting('code'));
            $datagrid = $admin->getDatagrid();        
            $filters = $blockContext->getSetting('filters');        
            if (!isset($filters['_per_page'])) {
                $filters['_per_page'] = array('value' => $blockContext->getSetting('limit'));
            }
            foreach ($filters as $name => $data) {
                $datagrid->setValue($name, isset($data['type']) ? $data['type'] : null, $data['value']);
            }
            $datagrid->buildPager();
        
            return $this->renderPrivateResponse($blockContext->getTemplate(), array(
                'block'      => $blockContext->getBlock(),
                'settings'   => $blockContext->getSettings(),
                'admin_pool' => $this->pool,
                'admin'      => $admin,
                'pager'      => $datagrid->getPager(),
                'datagrid'   => $datagrid,
            ), $response);
        }
    }
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'Admin Stats';
    }
    /**
     * {@inheritdoc}
     */
    public function configureSettings(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'icon'     => 'fa-line-chart',
            'text'     => 'Statistics',
            'color'    => 'bg-aqua',
            'code'     => false,
            'filters'  => array(),
            'limit'    => 1000,
            'template' => 'KreatysAdminBundle:Block:block_stats.html.twig',
            'role' => null
        ));
    }
    
    public function setDefaultSettings(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'icon'     => 'fa-line-chart',
            'text'     => 'Statistics',
            'color'    => 'bg-aqua',
            'code'     => false,
            'filters'  => array(),
            'limit'    => 1000,
            'template' => 'KreatysAdminBundle:Block:block_stats.html.twig',
            'role' => null
        ));
    }

    /**
     * 
     * @return \Kreatys\UserBundle\Entity\User
     */
    public function getUser()
    {
        if (null === $token = $this->securityTokenStorage->getToken()) {
            return;
        }

        if (!is_object($user = $token->getUser())) {
            // e.g. anonymous authentication
            return;
        }

        return $user;
    }
}