<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public const TEST_USER = 'user@test.com';

    public const TEST_USER_ADMIN = 'admin@test.com';

    public const PASSWORD = '235689';

    public function __construct(private UserPasswordHasherInterface $passwordHasher)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $user = (new User())->setFirstname('Test')
            ->setLastname('User')
            ->setEmail(self::TEST_USER)
            ->setPassword('$2y$13$BdnJroo8H/Rsa8UawqZllubZ.HEuMBj0hOHAQmIIFLo1mT.lBKm7e')
            ->setRoles(['ROLE_USER'])
        ;

        $adminUser = (new User())->setFirstname('Test')
            ->setLastname('User')
            ->setEmail(self::TEST_USER_ADMIN)
            ->setPassword('$2y$13$BdnJroo8H/Rsa8UawqZllubZ.HEuMBj0hOHAQmIIFLo1mT.lBKm7e')
            ->setRoles(['ROLE_ADMIN'])
        ;

        $manager->persist($user);
        $manager->persist($adminUser);

        $this->setReference(User::class.'_user@test.com', $user);
        $this->setReference(User::class.'_admin@test.com', $adminUser);

        for ($i = 0; $i < 9; ++$i) {
            $user = new User();
            $user->setEmail(sprintf('userdemo%s@example.com', uniqid()))
                ->setPassword(
                    $this->passwordHasher->hashPassword(
                        $user,
                        'userdemo'
                    )
                )->setFirstname('Foo')
                ->setLastname('User')
            ;

            $manager->persist($user);
            $this->setReference(User::class.'_'.$user->getEmail(), $user);
        }

        $manager->flush();
    }
}
