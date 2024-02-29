<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Technology;

class TechnologyFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $technologiesData = [
            0 => "PHP",
            1 => "JS",
            2 => "Python",
        ];

        for ($i = 0; $i < 3; $i++) {
            $technology = new Technology();
            $technology->setName($technologiesData[$i]);
            $manager->persist($technology);
            $this->addReference("technology-{$i}", $technology);
        }

        $manager->flush();
    }
}
