<?php

namespace Politeia\CoreBundle\Manager;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder;
use Politeia\CoreBundle\Repository\CitoyenRepository;
use Politeia\CoreBundle\Entity\Citoyen;

class UserManager
{
    /**
     * @var MessageDigestPasswordEncoder 
     */
    protected $passwordEncoder;
    
    /**
     * @var EntityManager 
     */
    private $em;
    
    /**
     * @var CitoyenRepository 
     */
    private $citoyenRepository;
    
    public function __construct(EntityManager $em, CitoyenRepository $citoyenRepository)
    {
        $this->passwordEncoder = new MessageDigestPasswordEncoder();
        $this->em = $em;
        $this->citoyenRepository = $citoyenRepository;
    }

    
    /**
     * 
     * @param string $username
     * @param string $password
     * @return Citoyen
     */
    public function loadUserByUsernamePassword($username, $password)
    {
        $user = $this->findUserByUsernameOrEmail($username);
        if($user !== null) {
            $encodedPassword = $user->getPassword();
            if(
                $encodedPassword === $this->passwordEncoder->encodePassword($password, $user->getSalt()) &&
                $user->getEnabled() && 
                !$user->getLocked()               
            ){
                return $user;
            }
        }
        return null;
    }
    
    /**
     * 
     * @param string $email
     * @return Citoyen
     */
    public function findUserByEmail($email)
    {
        return $this->citoyenRepository->findOneBy(array('email' => $this->canonicalize($email)));
    }
    
    /**
     * 
     * @param string $username
     * @return Citoyen
     */
    public function findUserByUsername($username)
    {
        return $this->citoyenRepository->findOneBy(array('username' => $this->canonicalize($username)));
    }
    
    /**
     * 
     * @param string $usernameOrEmail
     * @return Citoyen
     */
    public function findUserByUsernameOrEmail($usernameOrEmail)
    {
        if (filter_var($usernameOrEmail, FILTER_VALIDATE_EMAIL)) {
            return $this->findUserByEmail($usernameOrEmail);
        }

        return $this->findUserByUsername($usernameOrEmail);
    }
    
    /**
     * 
     * @param string $token
     * @return Citoyen
     */
    public function findUserByToken($token)
    {
        return $this->citoyenRepository->findOneBy(array('token' => $token));
    }
    
    /**
     * 
     * @param Citoyen $citoyen
     */
    public function deleteUser(Citoyen $citoyen)
    {
        $this->em->remove($citoyen);
        $this->em->flush();
    }

    /**
     * 
     * @param array $criteria
     * @return Citoyen
     */
    public function findUserBy(array $criteria)
    {
        return $this->citoyenRepository->findOneBy($criteria);
    }

    /**
     * 
     * @return array
     */
    public function findUsers()
    {
        return $this->citoyenRepository->findAll();
    }

    /**
     * 
     * @param Citoyen $citoyen
     */
    public function reloadUser(Citoyen $citoyen)
    {
        $this->em->refresh($citoyen);
    }

    /**
     * Updates a user.
     *
     * @param Citoyen $citoyen
     * @param Boolean $andFlush Whether to flush the changes (default true)
     */
    public function updateUser(Citoyen $citoyen, $andFlush = true)
    {        
        $this->updatePassword($citoyen);

        $this->em->persist($citoyen);
        if ($andFlush) {
            $this->em->flush();
        }
    }
    
    /**
     * 
     * @param Citoyen $citoyen
     */
    public function updatePassword(Citoyen $citoyen)
    {
        if (0 !== strlen($password = $citoyen->getPlainPassword())) {
            $citoyen->setPassword($this->passwordEncoder->encodePassword($password, $citoyen->getSalt()));
            $citoyen->eraseCredentials();
        }
    }
    
    /**
     * 
     * @param Citoyen $citoyen
     */
    public function confirm(Citoyen $citoyen)
    {
        $citoyen->setEnabled(true);
        $citoyen->setToken(null);
        
        $this->updateUser($citoyen);
    }

    /**
     * 
     * @return type
     */
    public function generateToken()
    {
        return rtrim(strtr(base64_encode($this->getRandomNumber()), '+/', '-_'), '=');
    }

    /**
     * 
     * @param type $string
     * @return type
     */
    public function canonicalize($string)
    {
        $encoding = mb_detect_encoding($string);
        $result = $encoding
            ? mb_convert_case($string, MB_CASE_LOWER, $encoding)
            : mb_convert_case($string, MB_CASE_LOWER);

        return $result;
    }
    
    /**
     * 
     * @return type
     */
    private function getRandomNumber()
    {
        return hash('sha256', uniqid(mt_rand(), true), true);
    }
}
