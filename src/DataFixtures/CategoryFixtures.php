<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Category;
use Faker;

class CategoryFixtures extends Fixture
{
    public function __construct()
    {
    }

    public function load(ObjectManager $manager): void
    {
        $categoriesData = [
            0 => "Projet personnel",
            1 => "E-Commerce",
            2 => "Application mobile",
            3 => "Veille technique",
        ];

        for ($i = 0; $i < 4; $i++) {
            $category = new Category();
            $category->setName($categoriesData[$i]);
            $manager->persist($category);
            $this->addReference("category-{$i}", $category);
        }

        $manager->flush();
    }
}
