<?php

namespace App\DataFixtures;

use App\Entity\City;
use App\Entity\Locality;
use App\Entity\Service;
use App\Entity\User;
use App\Entity\ZipCode;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encoder;
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        /** ADDRESS TYPES ********************************************************************************  */
        for ($i = 0; $i < 50; $i++) {
            $locality = new Locality();
            $locality->setLocality('locality'.$i);
            $localities[] = $locality;
            $manager->persist($locality);
        }
        $manager->flush();
        for ($i = 0; $i < 50; $i++) {
            $city = new City();
            $city->setCity('city'.$i);
            $cities[] = $city;
            $manager->persist($city);
        }
        $manager->flush();
        for ($i = 0; $i < 50; $i++) {
            $zipCode = new ZipCode();
            $zipCode->setZipCode('1456'.$i);
            $zipCodes[] = $zipCode;
            $manager->persist($zipCode);
        }
        $manager->flush();

        $user = new User();
        $user->setUsername('admin');
        $user->setPassword($this->encoder->encodePassword($user, '123456'));
        $user->setRoles(['ROLE_SUPER_ADMIN']);
        $user->setToken('admin');
        $user->setIsRegistered(true);
        $manager->persist($user);
        $manager->flush();

        for ($i = 0; $i < 5; $i++) {
            $service = new Service();
            $service->setName('service'.$i);
            $service->setDescription('service description'.$i);
            $service->setIsHighlighted(false);
            $service->setIsValid(0);
            $service->setSlug('service'.$i);


            $service->setUpdatedAt(new \DateTime());
            $this->addReference('service-' . $i, $service);
            $services[] = $service;
            $manager->persist($service);
        }
        $manager->flush();
    }
}
