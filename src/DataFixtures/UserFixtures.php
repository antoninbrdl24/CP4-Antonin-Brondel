<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;
    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }
    public function load(ObjectManager $manager): void
    {
        $user1 = new User();
        $user1->setEmail('user1@guser.com');
        $user1->setRoles(['ROLE_USER']);
        $hashedPassword = $this->passwordHasher->hashPassword(
            $user1,
            'User1*'
        );
        $user1->setPassword($hashedPassword);
        $user1->setName('Laura');
        $user1->setLastName('Wolf');
        $manager->persist($user1);


        $user2 = new User();
        $user2->setEmail('antoninBg@monsite.com');
        $user2->setRoles(['ROLE_USER']);
        $hashedPassword = $this->passwordHasher->hashPassword(
            $user2,
            'antoninBg1*'
        );
        $user2->setPassword($hashedPassword);
        $user2->setName('antonin');
        $user2->setLastName('brodel');
        $manager->persist($user2);


        $admin = new User();
        $admin->setEmail('admin@monsite.com');
        $admin->setRoles(['ROLE_ADMIN']);
        $hashedPassword = $this->passwordHasher->hashPassword(
            $admin,
            'Adminpassword1*'
        );
        $admin->setPassword($hashedPassword);
        $admin->setName('admin');
        $admin->setLastName('admin');
        $manager->persist($admin);
        $manager->flush();
    }
}
