<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use App\Entity\Profil;
use App\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encoder;
    public function  __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder=$encoder;
    }
    public function load(ObjectManager $manager)
    {
        $faker= Factory::create('fr_FR');
        $profilTab=['AdminSystem','Caissier','UserAgence','AdminAgence'];
        for ($p=0; $p<4; $p++){
            $profil= new Profil();
            $profil->setLibelle($profilTab[$p]);
            $manager->persist($profil);
            for ($u=0; $u<4; $u++) {
                $user = new User();
                $hash = $this->encoder->encodePassword($user, 'password');
                $user->setPrenom($faker->firstName())
                    ->setNom($faker->lastName)
                    ->setEmail($faker->email)
                    ->setCni('44444444')
                    ->setAdresse('Dakar')
                    ->setPhone('77777777')
                    ->setPassword($hash)
                    ->setProfil($profil);

                $manager->persist($user);
            }
        }
        $manager->flush();
    }
}
