<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Task;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class TaskFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');

        for ($i = 1; $i <= 15; $i++) {
            $isDone = random_int(0, 1);
            $subTitle = $isDone ? ' réalisée' : ' à faire';

            $task = new Task();

            $task->setTitle('Tâche numéro ' . $i . $subTitle);
            $task->setContent($faker->sentence(5, true));
            $task->setCreatedAt(new \DateTime());
            $task->setUser($this->getReference(UserFixtures::ANONYME_USER_REFERENCE));

            $manager->persist($task);
        }

        $manager->flush();
    }

    /**
     * @return string[]
     */
    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
        ];
    }
}
