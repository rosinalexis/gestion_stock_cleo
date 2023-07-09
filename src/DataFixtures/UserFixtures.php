<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use Faker\Generator;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{

    const NUMBER_OF_FAKE_ELEMENT = 20;
    private Generator $faker;
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->faker = Factory::create('fr_FR');
        $this->passwordHasher = $passwordHasher;
    }
    public function load(ObjectManager $manager): void
    {
        $this->loadSimpleUsers($manager);
    }

    public function loadSimpleUsers(ObjectManager $manager): void
    {
        for ($i = 1; $i < self::NUMBER_OF_FAKE_ELEMENT; $i++) {
            $user = new User();
            $user->setEmail($this->faker->email())
                ->setFirstname($this->faker->firstName())
                ->setLastname($this->faker->lastName())
                ->setRoles($this->faker->randomElement([User::ROLE_ADMIN, User::ROLE_USER]))
                ->setPassword($this->passwordHasher->hashPassword($user, 'qwsxdc'));
            $manager->persist($user);
            $manager->flush();
        }
    }
}