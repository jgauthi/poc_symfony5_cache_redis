<?php
namespace App\DataFixtures;

use App\Entity\Client;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory as FakerFactory;

class ClientFixtures extends Fixture
{
    public const NB_FIXTURE = 10;
    private \Faker\Generator $faker;

    public function __construct()
    {
        $this->faker = FakerFactory::create();
    }

    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < self::NB_FIXTURE; ++$i) {
            $client = new Client;
            $client->setName($this->faker->unique()->company)
                ->setAddress($this->faker->unique()->optional(0.8)->address)
                ->setCity($this->faker->city)
                ->setCountry($this->faker->country);

            $manager->persist($client);
            $this->setReference("client_$i", $client);
        }

        $manager->flush();
    }
}
