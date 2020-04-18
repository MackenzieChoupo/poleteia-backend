<?php

namespace Politeia\CoreBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Politeia\CoreBundle\Entity\Mairie;
use Politeia\CoreBundle\Entity\Sondage;

class SondageRepository extends EntityRepository
{
    public function checkSondageExist(Mairie $mairie, \DateTime $debut, \DateTime $fin, Sondage $exclude = null)
    {        
        $params = [
            'mairie' => $mairie,
            'd' => $debut,
            'f' => $fin
        ];        
 
        $qb = $this->createQueryBuilder('s')
                ->select('count(s)')               
                ->where('s.mairie = :mairie')
                ->andWhere(''
                        . '(s.datePublicationDebut >= :d AND s.datePublicationDebut <= :f AND s.datePublicationFin >= :d AND s.datePublicationFin >= :f)'
                        . ' OR (s.datePublicationDebut <= :d AND s.datePublicationDebut <= :f AND s.datePublicationFin >= :d AND s.datePublicationFin >= :f)'
                        . ' OR (s.datePublicationDebut <= :d AND s.datePublicationDebut <= :f AND s.datePublicationFin >= :d AND s.datePublicationFin <= :f)'
                        . ' OR (s.datePublicationDebut >= :d AND s.datePublicationDebut <= :f AND s.datePublicationFin >= :d AND s.datePublicationFin <= :f)');
                
        if($exclude !== null) {
            $qb->andWhere('s != :exclude');
            $params['exclude'] = $exclude;
        }
        
        return $qb   
                ->setParameters($params)
                ->getQuery()
                ->getSingleScalarResult() > 0;
        
    }
}
