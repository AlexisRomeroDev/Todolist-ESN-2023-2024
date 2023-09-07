<?php

namespace App\DataFixtures;

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

        for ($i = 1; $i <= 50; $i++) {
            $article = new Todo();
            $article->setName($this->faker->sentence(4))
                ->setDescription($this->faker->paragraph)
                ->setDone(rand(0, 1) > 0.5);
            $manager->persist($article);
        }
        $manager->flush();
    }
}
