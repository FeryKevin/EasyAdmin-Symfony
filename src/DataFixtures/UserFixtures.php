<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Entity\User;
use Faker;

class UserFixtures extends Fixture
{
    private UserPasswordHasherInterface $encoder;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->encoder = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr_FR');

        for ($i = 0; $i < 5; $i++) {
            $user = new User();
            $user->setEmail($faker->email());
            $user->setRoles(['ROLE_ADMIN']);
            $user->setPassword($this->encoder->hashPassword($user, $faker->password()));
            $manager->persist($user);
        }

        $user = new User();
        $user->setEmail("user@user.com");
        $user->setRoles(['ROLE_USER']);
        $user->setPassword($this->encoder->hashPassword($user, "user"));
        $manager->persist($user);

        $user = new User();
        $user->setEmail("admin@admin.com");
        $user->setRoles(['ROLE_ADMIN']);
        $user->setPassword($this->encoder->hashPassword($user, "admin"));
        $manager->persist($user);

        $manager->flush();
    }
}
