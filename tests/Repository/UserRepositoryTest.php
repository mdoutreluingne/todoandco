<?php

namespace App\Tests\Repository;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;

class UserRepositoryTest extends WebTestCase
{
    public function testUpgradePassword()
    {
        self::bootKernel();
        $userRepository = static::getContainer()->get(UserRepository::class);
        // retrieve the test users
        $user = $userRepository->findOneBy(['username' => 'anonyme']);

        $userRepository->upgradePassword($user, 'testtest');

        $this->assertEquals($user->getPassword(), $userRepository->findOneBy(['username' => 'anonyme'])->getPassword());

        $mockUserInterface = $this->createMock(UserInterface::class);

        $this->expectException(UnsupportedUserException::class);
        $userRepository->upgradePassword($mockUserInterface, 'FakePassword');
    }
}
