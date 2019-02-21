<?php
/**
 * Created by PhpStorm.
 * User: Emilie Sappracone
 * Date: 20-02-19
 * Time: 11:01
 */

namespace App\Services;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UsersMaker
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    /**
     * UsersMaker constructor.
     * @param EntityManagerInterface $em
     * @param UserPasswordEncoderInterface $encoder
     */
    public function __construct(EntityManagerInterface $em, UserPasswordEncoderInterface $encoder)
    {
        $this->em = $em;
        $this->encoder = $encoder;
    }

    /**
     * Method used to persist/flush user in database with manager
     * @param $user
     * @param $userDatas
     * @param bool $return
     * @return mixed
     */
    public function makeUser($user, $userDatas, $return = false)
    {
        // Check if user email is already used or not
        $isExist = $this->checkMail($userDatas['basicEmail']);
        if (is_object($isExist) && !is_null($isExist) && $isExist->getIsRegistered()) {
            return false;
        } else {
            // set all properties in array
            foreach ($userDatas as $kdata => $data) {
                $setter = 'set' . ucfirst($kdata);
                if ($setter == 'setPassword') {
                    $user->$setter($this->encoder->encodePassword($user, $user->getPassword()));
                } else {
                    $user->$setter($data);
                }
            }
            // Persist/flush in database
            $this->em->persist($user);
            $this->em->flush();

            if ($return) {
                // Return wanted
                $getter = 'get' . ucfirst($return);
                return $user->$getter();
            }
        }
    }

    /**
     * Method used to check if user email already exists
     * @param $email
     * @return User|null|object
     */
    public function checkMail($email)
    {
        $isExists = $this->em->getRepository(User::class)->findOneBy(['basicEmail' => $email]);
        return $isExists;
    }
}