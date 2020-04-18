<?php

namespace Politeia\CoreBundle\Repository;

use Doctrine\ORM\EntityRepository;

class CitoyenRepository extends EntityRepository
{
    public function subscribedToMairie($mairieId)
    {
        $qb = $this->createQueryBuilder('c');
        $qb->select('c')
            ->leftJoin('c.abonnementMairies', 'ab')
            ->addSelect('ab')
            ->leftJoin('ab.mairie', 'm')
            ->addSelect('m')
            ->andWhere('m.id = :id')
            ->setParameter('id', $mairieId)->select('c.deviceToken');
        
        return $qb->getQuery()->getResult();
    }
}
