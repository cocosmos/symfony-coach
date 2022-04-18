<?php

namespace App\DataFixtures;

use App\Entity\Priority;
use App\Entity\Status;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $priorityNames =["On occasion, come and give us a feedback", "Small request that does not rush too much", "We need help !", "It blocks us completely !"];
        $statusNames =["We think…","We arrive", "Figure it out", "Declined", "Solved"];


        foreach ( $priorityNames as $key => $priorityName){


            $priority = new Priority();
            $priority->setName($priorityName);
            $priority->setWeight($key+1);
            $manager->persist($priority);
        }
        foreach ($statusNames as $statusName){
            $status = new Status();
            $status->setName($statusName);
            if ($statusName  == "Declined" || $statusName =="Solved"){
                $status->setIsArchived(true);
            }else{
                $status->setIsArchived(false);
            }
            $manager->persist($status);

        }



        $manager->flush();
    }
}
