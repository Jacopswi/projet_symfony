<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
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
        $usersData = [
            [
                'pseudo' => 'CinocheEnjoyer',
                'email' => 'cinoche@enjoyer.com',
                'firstName' => 'Marc',
                'lastName' => 'Carm',
                'roles' => "admin",
                'password' => 'userpass1', 
            ],
            [
                'pseudo' => 'janedoe',
                'email' => 'janedoe@example.com',
                'firstName' => 'Jane',
                'lastName' => 'Doe',
                'roles' => "user",
                'password' => 'password123',
            ]
        ];

        foreach ($usersData as $userData) {
            $user = new User();
            $user->setPseudo($userData['pseudo']);
            $user->setEmail($userData['email']);
            $user->setFirstName($userData['firstName']);
            $user->setLastName($userData['lastName']);
            $user->setRoles($userData['roles']);

            $hashedPassword = $this->passwordHasher->hashPassword($user, $userData['password']);
            dd($hashedPassword);
            $user->setPassword($hashedPassword);

            $manager->persist($user);
        }

        $manager->flush();
    }
}
