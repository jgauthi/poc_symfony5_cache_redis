<?php
namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory as FakerFactory;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class CategoryFixtures extends Fixture implements DependentFixtureInterface
{
    public const NB_FIXTURE = 7;
    private \Faker\Generator $faker;

    public function __construct()
    {
        $this->faker = FakerFactory::create();
    }

    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < self::NB_FIXTURE; ++$i) {
            $dir = realpath(__DIR__.'/../../public/images').'/';
            $original = $this->faker->randomElement(['imageFixture1.jpg', 'imageFixture2.jpg', 'imageFixture3.jpg', 'imageFixture4.jpg', 'imageFixture5.jpg']);
            $imagePath = $dir.'category/'.rand().'.jpg';
            copy($dir.$original, $imagePath);

            $file = new UploadedFile(
                $imagePath,
                basename($imagePath),
                'image/jpg',
                null,
                true //  Set test mode true !!! " Local files are used in test mode hence the code should not enforce HTTP uploads."
            );

            $category = new Category;
            $category->setTitle($this->faker->unique()->streetName)
                ->setImageFile($file);

            $alreadyUse = [];
            for ($j = 0; $j < rand(1, 5); ++$j) {
                $randomDossier = rand(0, DossierFixtures::NB_FIXTURE - 1);
                if (\in_array($randomDossier, $alreadyUse, true)) {
                    continue;
                }

                $alreadyUse[] = $randomDossier;
                $category->addDossier($this->getReference("dossier_{$randomDossier}")); // @phpstan-ignore-line
            }

            $manager->persist($category);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            DossierFixtures::class,
        ];
    }
}
