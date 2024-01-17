<?php

namespace App\DataFixtures;

use App\Entity\Type;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
;

class TypeFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {

        $types = ['terrain', 'materiel', 'personnel'];
        foreach($types as $typeName) {
            $type = new Type();
            $type -> setName($typeName);
            $manager->persist($type);
            
        }
        $manager->flush();
    }
}
