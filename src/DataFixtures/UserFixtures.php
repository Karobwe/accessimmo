<?php

namespace App\DataFixtures;

use Faker;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{

    private $NB_USERS = 20;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }
    
    public function load(ObjectManager $manager)
    {    
                // récupération de l'objet Faker pour faire des "jolies" fausses données
                $faker = Faker\Factory::create('fr_FR');

                // création d'un compte Admin
                $admin = new User();
               
                $admin
                    ->setFirstname('admin')
                    ->setLastName('admin')
                    ->setEmail('admin@admin.fr')
                    ->setPassword($this->encoder->encodePassword($admin, 'azertyuiop'))
                    ->setRoles(array('ROLE_ADMIN'));
                
                $manager->persist($admin);
        
                // création d'un compte User pour test
                $user = new User();
                
                $user
                    ->setFirstname('user')
                    ->setLastName('user')
                    ->setEmail('user@user.fr')
                    ->setPassword($this->encoder->encodePassword($user, '12345678'));
                
                $manager->persist($user);
        
                // création d'utilisateurs pour remplir la BDD
                for ($i=1; $i <= $this->NB_USERS; $i++) {
                    $user = new User();
                   
                    $user
                        ->setFirstname($faker->firstName)
                        ->setLastName($faker->lastName)
                        ->setEmail($faker->email)
                        ->setPassword($this->encoder->encodePassword($user, 'user'))
                        ->setAddress($faker->streetAddress)
                        ->setCity($faker->city)
                        ->setZipCode(str_replace(' ', '', $faker->postcode))
                        ->setPhone('0' . $faker->randomNumber(9))
                    ;
                    
                    $manager->persist($user);
                    
                    $users[] = $user;
                }

        
                $manager->flush();
    }

}
