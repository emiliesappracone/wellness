<?php
/**
 * Created by PhpStorm.
 * User: Emilie Sappracone
 * Date: 17-02-19
 * Time: 17:43
 */

namespace App\Services;


use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserChecker implements UserCheckerInterface
{
    protected $em;
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }
    public function checkPreAuth(UserInterface $user)
    {
        /**
         * 1- Check if user is Ban
         * return Exception
         */
        if($user->getIsBanned() == true){
            throw new CustomUserMessageAuthenticationException('Compte banni');
        }
        /**
         * 2- Check if tries unsuccessful user is less than 4
         * return Exception
         */
        if($user->getTryToConnect() >= 4){
            throw new CustomUserMessageAuthenticationException('Compte bloqué');
        }
    }
    public function checkPostAuth(UserInterface $user)
    {
        /**
         * 1- Reset number of tries unsuccessful
         */
        // update than persist/flush in db
        $user->setTryToConnect(0);
        $this->em->persist($user);
        $this->em->flush();
    }
}