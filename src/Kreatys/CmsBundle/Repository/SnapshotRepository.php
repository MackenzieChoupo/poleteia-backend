<?php

namespace Kreatys\CmsBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Gedmo\Translatable\TranslatableListener;
use Kreatys\CmsBundle\Entity\Page;

class SnapshotRepository extends EntityRepository
{
    
    /**
     * @param Page $page
     * @return \Kreatys\CmsBundle\Entity\Snapshot
     */
    public function getByPage(Page $page)
    {
		return $this->createQueryBuilder('s')
			->select('s')
			->where('s.page = :page')
			->setParameter('page', $page)
			->getQuery()
			->getOneOrNullResult();	
    }
    
    /**
     * 
     * @param string $slug
     * @return \Kreatys\CmsBundle\Entity\Snapshot
     */
    public function getBySlug($slug)
    {
        return $this->createQueryBuilder('s')
			->select('s')
			->where('s.slug = :slug')
			->setParameter('slug', $slug)
			->getQuery()
			->getOneOrNullResult();
    }
    
    /**
     * 
     * @param string $url
     * @return \Kreatys\CmsBundle\Entity\Snapshot
     */
    public function getByUrl($url)
    {
        return $this->createQueryBuilder('s')
			->select('s')
			->where('s.url = :url')
			->setParameter('url', $url)
			->getQuery()
			->getOneOrNullResult();
    }
    
    /**
     * 
     * @param string $url
     * @return \Kreatys\CmsBundle\Entity\Snapshot
     */
    public function getByRouteName($routeName)
    {
        $routeName = preg_replace('#\.[a-z]{2}$#', '', $routeName);        
        
        return $this->createQueryBuilder('s')
			->select('s')
			->where('s.route_name = :routeName')
			->setParameter('routeName', $routeName)
			->getQuery()
			->getOneOrNullResult();
    }
    
    
    public function findAllByLocale($locale)
    {
        $qb = $this->createQueryBuilder('s')
			->select('s')			
			->getQuery();
			
        $qb->setHint(
            Query::HINT_CUSTOM_OUTPUT_WALKER,
            'Gedmo\\Translatable\\Query\\TreeWalker\\TranslationWalker'
        );

        $qb->setHint(TranslatableListener::HINT_TRANSLATABLE_LOCALE, $locale);
        
        return $qb->getResult();
    }

}
