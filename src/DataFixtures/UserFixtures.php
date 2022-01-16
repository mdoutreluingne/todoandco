<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public const ANONYME_USER_REFERENCE = 'anonyme-user';

    /**
     * @var UserPasswordHasherInterface
     */
    private $encoder;

    public function __construct(UserPasswordHasherInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $admin = new User();
        $admin->setEmail('admin@todoandco.com');
        $admin->setPassword($this->encoder->hashPassword($admin, 'adminadmin'));
        $admin->setRoles(['ROLE_USER', 'ROLE_ADMIN']);
        $admin->setUsername("admin");
        $manager->persist($admin);

        $userOne = new User();
        $userOne->setEmail('maxime@todoandco.com');
        $userOne->setPassword($this->encoder->hashPassword($userOne, 'testtest'));
        $userOne->setRoles(['ROLE_USER']);
        $userOne->setUsername("mdoutreluingne");
        $manager->persist($userOne);

        $anonyme = new User();
        $anonyme->setEmail('anonyme@todoandco.com');
        $anonyme->setPassword($this->encoder->hashPassword($anonyme, 'anonyme'));
        $anonyme->setRoles(['ROLE_USER']);
        $anonyme->setUsername("anonyme");
        $manager->persist($anonyme);
        $this->addReference(self::ANONYME_USER_REFERENCE, $anonyme);

        $manager->flush();
    }

    
}
