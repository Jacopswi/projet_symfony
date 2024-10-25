<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class UserFixtures extends Fixture implements DependentFixtureInterface
{
    public const USER_REFERENCE = 'User';

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
                'roles' => ["ROLE_ADMIN", "ROLE_USER"],
                'password' => 'userpass1', 
            ],
            [
                'pseudo' => 'janedoe',
                'email' => 'janedoe@example.com',
                'firstName' => 'Jane',
                'lastName' => 'Doe',
                'roles' => ["ROLE_USER"],
                'password' => 'password123',
            ]
        ];

        foreach ($usersData as $key => $userData) {
            $user = new User();
            $user->setPseudo($userData['pseudo']);
            $user->setEmail($userData['email']);
            $user->setFirstName($userData['firstName']);
            $user->setLastName($userData['lastName']);
            $user->setRoles($userData['roles']);
            $user->setAddress($this->getReference(AddressFixtures::ADDRESS_REFERENCE . '_' . ($key)));


            $hashedPassword = $this->passwordHasher->hashPassword($user, $userData['password']);
            $user->setPassword($hashedPassword);

            $manager->persist($user);
            $this->addReference(self::USER_REFERENCE . '_' . $key, $user);

        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            AddressFixtures::class
        ];
    }
}
