<?php

namespace Politeia\CoreBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Politeia\CoreBundle\Entity\Mairie;

class BoiteAIdeeQuestionRepository extends EntityRepository
{
    
    public function getPublieByMairieTheme(Mairie $mairie, $theme)
    {
        $now = new \DateTime();
        return $this->createQueryBuilder('q')
                ->select('q')               
                ->where('q.mairie = :mairie')
                ->andWhere('q.theme = :theme')
                ->andWhere('q.online = :online')
                ->andWhere('q.datePublicationDebut <= :now AND q.datePublicationFin >= :now')
                ->setParameters([
                    'mairie' => $mairie,
                    'theme' => $theme,
                    'online' => true,
                    'now' => $now
                ])
                ->orderBy('q.position')
                ->getQuery()
                ->getResult();
    }   
    
    
    /*public function getPublieByTheme(BoiteAIdeeTheme $theme)
    {        
        $now = new \DateTime();
        return $this->createQueryBuilder('q')
                ->select('q')               
                ->where('q.theme = :mairie')
                ->andWhere('q.online = :online')
                ->andWhere('q.datePublicationDebut <= :now AND q.datePublicationFin >= :now')
                ->setParameters([
                    'mairie' => $theme,
                    'online' => true,
                    'now' => $now
                ])
                ->orderBy('q.position')
                ->getQuery()
                ->getResult();
    }*/
}
