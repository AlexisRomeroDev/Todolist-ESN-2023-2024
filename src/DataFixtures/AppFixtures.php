<?php

namespace App\DataFixtures;

use App\Entity\Priority;
use App\Entity\Todo;
use Faker\Factory;
use Faker\Generator;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    private Generator $faker;

    public function __construct()
    {
        $this->faker = Factory::create('fr_FR');
    }

    public function load(ObjectManager $manager): void
    {
        $priorities = [
            'Critical', 'High', 'Medium', 'Low',
        ];

        foreach ($priorities as $key => $value) {
            $priority = new Priority();
            $priority->setLevel($key +1)
                ->setName($value);
            $manager->persist($priority);
            $this->addReference('Priority_' . $key, $priority);
        }


        for ($i = 1; $i <= 50; $i++) {
            $rand = rand(0,3);
            $todo = new Todo();
            $todo->setName($this->faker->sentence(4))
                ->setDescription($this->faker->paragraph)
                ->setDone(rand(0, 1) > 0.5)
                ->setPriority($this->getReference('Priority_'.$rand));
             $manager->persist($todo);
        }
        $manager->flush();
    }
}
