<?php

namespace Kreatys\UserBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Kreatys\UserBundle\Entity\User;

class UserRepository extends EntityRepository
{
    public function emailExist($email, $excludeUserId = null)
    {
        $params = [
            'email' => $email
        ];
        $qb = $this->createQueryBuilder('u');        
        $qb->select('COUNT(u.id)')                
            ->where('u.email = :email');
        if($excludeUserId !== null) {
            $qb->andWhere('u.id != :id');
            $params['id'] = $excludeUserId;
        }        
        $qb->setParameters($params);
                
        return $qb->getQuery()->getSingleScalarResult() == 1;  
    }
}
