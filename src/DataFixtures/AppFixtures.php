<?php

namespace App\DataFixtures;

use App\Entity\Address;
use App\Entity\Housing;
use App\Entity\Image;
use App\Entity\Status;
use App\Entity\Type;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{

    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    private $faker;

    /**
     * AppFixtures constructor.
     */
    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->faker = Faker\Factory::create('fr_FR');
    }

    public function load(ObjectManager $manager)
    {
        // -*-*-*-*-*-*-*--*-*- Status -*-*-*-*-*-*-*--*-*-
        $statusALouer = new Status();
        $statusALouer->setName('A louer');
        $manager->persist($statusALouer);

        $statusAVendre = new Status();
        $statusAVendre->setName('A vendre');
        $manager->persist($statusAVendre);

        $status = array($statusALouer, $statusAVendre);

        // -*-*-*-*-*-*-*--*-*- Type -*-*-*-*-*-*-*--*-*-
        $typeMaison = new Type();
        $typeMaison->setName('Maison');
        $manager->persist($typeMaison);

        $typeAppartement = new Type();
        $typeAppartement->setName('Appartement');
        $manager->persist($typeAppartement);

        $types = array($typeMaison, $typeAppartement);

        // -*-*-*-*-*-*-*--*-*- Address -*-*-*-*-*-*-*--*-*-
        $rueSully = new Address();
        $rueSully
            ->setStreet('Rue Sully')
            ->setPostCode(21000)
            ->setCity('Dijon');
        $manager->persist($rueSully);

        $rueMirande = new Address();
        $rueMirande
            ->setStreet('Rue de Mirande')
            ->setPostCode(21000)
            ->setCity('Dijon');
        $manager->persist($rueMirande);

        $avGare = new Address();
        $avGare
            ->setStreet('Avenue de la Gare')
            ->setPostCode(21110)
            ->setCity('Genlis');
        $manager->persist($avGare);

        $bvPasteur = new Address();
        $bvPasteur
            ->setStreet('Boulevard Pasteur')
            ->setPostCode(21110)
            ->setCity('Genlis');
        $manager->persist($bvPasteur);

        $address = array($rueSully, $rueMirande, $avGare, $bvPasteur);

        // -*-*-*-*-*-*-*--*-*- Image -*-*-*-*-*-*-*--*-*-

        // -*-*-*-*-*-*-*--*-*- Housing -*-*-*-*-*-*-*--*-*-
        for($i = 0; $i < 100; $i++) {
            $housing = new Housing();
            $housing->setStatus($status[array_rand($status, 1)]);
            $housing->setType($types[array_rand($types, 1)]);
            $housing->setAddress($address[array_rand($address, 1)]);
            $housing->setFloorArea($this->faker->numberBetween(15, 150));
            $housing->setRoomCount($this->faker->numberBetween(1, 8));
            $housing->setBedroomCount($this->faker->numberBetween(1, $housing->getRoomCount()));

            if($housing->getType() ->getName()== 'A louer') {
                $housing->setPrice($this->faker->numberBetween(200, 1000));
            } else {
                $housing->setPrice($this->faker->numberBetween(50000, 500000));
            }

            $housing->setShortDescription(
                $housing->getType() . ' ' .
                $housing->getClassification() . ' ' .
                $housing->getFloorArea() . 'm² ' .
                $housing->getStatus() . ' - ' .
                $housing->getAddress()->getCity()
            );

            $housing->setDescription($this->faker->realText(1000));

            for($j = 0; $j < 5; $j++) {
                $img = new Image();
                $img->setUrl($this->faker->imageUrl(640, 450, 'city'));
                $img->setAlt($this->faker->realText(150));
                $housing->addImage($img);
            }

            $manager->persist($housing);
        }

        $manager->flush();
    }
}
