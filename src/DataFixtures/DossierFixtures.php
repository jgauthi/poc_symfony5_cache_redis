<?php
namespace App\DataFixtures;

use App\Entity\Dossier;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory as FakerFactory;

class DossierFixtures extends Fixture implements DependentFixtureInterface
{
    public const NB_FIXTURE = 20;
    private \Faker\Generator $faker;

    public function __construct()
    {
        $this->faker = FakerFactory::create();
    }

    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < self::NB_FIXTURE; ++$i) {
            $randomClient = rand(0, ClientFixtures::NB_FIXTURE - 1);

            $dossier = new Dossier;
            $dossier->setTitle(ucfirst(implode(' ', (array) $this->faker->unique()->words(rand(2, 4)))))
                ->setContent($this->faker->text)
                ->setActive($this->faker->boolean(70))
                ->setClient($this->getReference("client_{$randomClient}")); // @phpstan-ignore-line

            $randomUsername = array_rand(UserFixtures::USERS, 1);
            $dossier->setAuthor($this->getReference("user_{$randomUsername}")); // @phpstan-ignore-line

            $manager->persist($dossier);
            $this->setReference("dossier_$i", $dossier);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            ClientFixtures::class,
            UserFixtures::class,
        ];
    }
}
