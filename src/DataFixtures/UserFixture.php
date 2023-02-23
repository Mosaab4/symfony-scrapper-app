<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixture extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $admin = new User();
        $admin->setEmail('admin@admin.com');
        $hashedPassword = $this->passwordHasher->hashPassword(
            $admin,
            "123456"
        );
        $admin->setPassword($hashedPassword);
        $admin->setRoles(['ROLE_ADMIN']);

        $manager->persist($admin);


        $moderator = new User();
        $moderator->setEmail('moderator@moderator.com');
        $hashedPassword = $this->passwordHasher->hashPassword(
            $moderator,
            "123456"
        );
        $moderator->setPassword($hashedPassword);
        $moderator->setRoles(['ROLE_MODERATOR']);

        $manager->persist($moderator);

        $manager->flush();
    }
}
