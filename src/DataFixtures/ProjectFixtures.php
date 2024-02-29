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

        for ($i = 0; $i < 10; $i++) {
            $project = new Project();
            $project->setName($faker->word());
            $project->setThumbnail("project.png");
            $project->setDescription($faker->sentence());
            $project->setLink($faker->url());
            $project->setStartAt($faker->dateTime());
            $project->setEndAt($faker->dateTime());
            $project->setCreatedAt($faker->dateTime());
            $project->setUpdatedAt($faker->dateTime());
            $project->setCategory($this->getReference("category-" . (string)rand(0, 3)));
            $manager->persist($project);
            $this->addReference("project-{$i}", $project);

            for ($j = 0; $j < 10; $j++) {
                $project = $this->getReference("project-{$i}");
                $numTechnologies = rand(1, 3);

                for ($k = 0; $k < $numTechnologies; $k++) {
                    $technology = $this->getReference("technology-" . (string)rand(0, 2));
                    $project->addTechnology($technology);
                }
            }
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
