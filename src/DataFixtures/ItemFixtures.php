<?php

namespace App\DataFixtures;

use App\Entity\Item;
use App\Services\QrCodeService;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;


class ItemFixtures extends Fixture
{


    const NUMBER_OF_FAKE_ELEMENT = 20;
    private Generator $faker;
    private QrCodeService $qrCodeService;

    public function __construct(QrCodeService $qrCodeService)
    {
        $this->faker = Factory::create('fr_FR');
        $this->qrCodeService = $qrCodeService;
    }
    public function load(ObjectManager $manager): void
    {
        $this->loadSimpleItems($manager);
    }

    public function loadSimpleItems(ObjectManager $manager, )
    {
        for ($i = 1; $i < self::NUMBER_OF_FAKE_ELEMENT; $i++) {
            $item = new Item();

            $item->setName($this->faker->word())
                ->setItemRef($this->faker->uuid())
                ->setQuantity($this->faker->numberBetween(1, 100))
                ->setQuantityR($this->faker->numberBetween(1, $item->getQuantity()))
                ->setQrCode($this->qrCodeService->qrCode($item->getItemRef()));
            ;
            $manager->persist($item);


            $manager->flush();
        }
    }
}