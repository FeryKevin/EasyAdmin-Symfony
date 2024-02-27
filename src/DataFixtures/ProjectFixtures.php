<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use App\Entity\Project;
use App\Repository\CategoryRepository;
use Faker;

class ProjectFixtures extends Fixture implements DependentFixtureInterface
{
    private $categoryRepository;

    public  function __construct(
        CategoryRepository $categoryRepository
    ) {
        $this->categoryRepository = $categoryRepository;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr_FR');

        for ($i = 0; $i < 9; $i++) {
            $project = new Project();
            $project->setName($faker->word());
            $project->setThumbnail("project.png");
            $project->setDescription($faker->sentence());
            $project->setLink($faker->url());
            $project->setStartAt($faker->dateTime());
            $project->setEndAt($faker->dateTime());
            $project->setCreatedAt($faker->dateTime());
            $project->setUpdatedAt($faker->dateTime());
            $project->setCategory($this->getReference("category-" . (string)rand(1, 2)));
            $this->addReference("project-{$i}", $project);
            $manager->persist($project);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            CategoryFixtures::class,
        );
    }
}
