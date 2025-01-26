<?php

namespace App\DataFixtures;

use App\Entity\Report;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $generator = Factory::create(); 

        for ($i = 0; $i < 200; $i++) {
            $report = new Report();
            $report->setName('raport '.$i);
            $report->setDateTime(new DateTime($generator->dateTime()->format('Y-m-d H:i')));
            $report->setUserName($generator->userName());
            $report->setRoom('pokoj '.$generator->randomDigitNotNull());
            $manager->persist($report);
        }
        $manager->flush();
    }
}
