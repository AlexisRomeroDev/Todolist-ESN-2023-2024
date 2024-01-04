<?php

namespace App\DataFixtures;

use App\Entity\Priority;
use App\Entity\Todo;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

class AppFixtures extends Fixture implements DependentFixtureInterface
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
            $priority->setLevel($key + 1)
                ->setName($value);
            $manager->persist($priority);
            $this->addReference('Priority_'.$key, $priority);
        }

        for ($i = 1; $i <= 50; ++$i) {
            $rand_p = rand(0, 3);
            $rand_c = rand(0, 9);
            $todo = new Todo();
            $todo->setName($this->faker->sentence(4))
                ->setDescription($this->faker->paragraph)
                ->setDone(rand(0, 1) > 0.5)
                ->setPriority($this->getReference('Priority_'.$rand_p))
                ->setCategory($this->getReference('Category_'.$rand_c));
            $manager->persist($todo);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            CategoryFixtures::class,
        ];
    }
}
