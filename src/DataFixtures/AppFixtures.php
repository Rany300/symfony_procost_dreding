<?php

namespace App\DataFixtures;

use App\Entity\Employe;
use App\Entity\Job;
use App\Entity\Project;
use App\Entity\WorkUnit;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {

        // Define the projects
        $project1 = new Project();
        $project1->setName('Procost');
        $project1->setDescription('Application de gestion de projet');
        $project1->setPrice(100000);
        $project1->setCreatedAt(new \DateTimeImmutable('2020-01-01'));
        $manager->persist($project1);

        $project2 = new Project();
        $project2->setName('Spotifly');
        $project2->setDescription('Application musicale');
        $project2->setPrice(200000);
        $project2->setCreatedAt(new \DateTimeImmutable('2020-02-01'));
        $project2->setDeliveredAt(new \DateTimeImmutable('2020-03-01'));
        $manager->persist($project2);

        $manager->flush();

        // Define the jobs
        $job1 = new Job();
        $job1->setName('Développeur');
        $manager->persist($job1);

        $job2 = new Job();
        $job2->setName('Chef de projet');
        $manager->persist($job2);

        $job3 = new Job();
        $job3->setName('Graphiste');
        $manager->persist($job3);

        $manager->flush();

        // Define the employes
        $employe1 = new Employe();
        $employe1->setName('Jean Dupont');
        $employe1->setJob($job1);
        $employe1->setEmail('jean@procost.fr');
        $employe1->setCost(230);
        $employe1->setHiredAt(new \DateTimeImmutable('2020-01-01'));
        $employe1->setImageURL('/img/ui-sherman.jpg');
        $manager->persist($employe1);

        $employe2 = new Employe();
        $employe2->setName('Marie Dupont');
        $employe2->setJob($job2);
        $employe2->setEmail('marie@procost.fr');
        $employe2->setCost(210);
        $employe2->setHiredAt(new \DateTimeImmutable('2020-02-01'));
        $employe2->setImageURL('/img/ui-divya.jpg');
        $manager->persist($employe2);

        $employe3 = new Employe();
        $employe3->setName('Pierre Noyer');
        $employe3->setJob($job3);
        $employe3->setEmail('pierre@procost.fr');
        $employe3->setCost(300);
        $employe3->setHiredAt(new \DateTimeImmutable('2017-03-01'));
        $employe3->setImageURL('/img/user.jpg');
        $manager->persist($employe3);

        $manager->flush();

        // Define the work units

        $workUnit1 = new WorkUnit();
        $workUnit1->setEmploye($employe1);
        $workUnit1->setProject($project1);
        $workUnit1->setStartedAt(new \DateTimeImmutable('2020-01-01'));
        $workUnit1->setDuration(1);
        $manager->persist($workUnit1);

        $workUnit2 = new WorkUnit();
        $workUnit2->setEmploye($employe1);
        $workUnit2->setProject($project1);
        $workUnit2->setStartedAt(new \DateTimeImmutable('2020-01-02'));
        $workUnit2->setDuration(2);
        $manager->persist($workUnit2);

        $workUnit3 = new WorkUnit();
        $workUnit3->setEmploye($employe1);
        $workUnit3->setProject($project1);
        $workUnit3->setStartedAt(new \DateTimeImmutable('2020-01-03'));
        $workUnit3->setDuration(3);
        $manager->persist($workUnit3);

        $workUnit4 = new WorkUnit();
        $workUnit4->setEmploye($employe1);
        $workUnit4->setProject($project2);
        $workUnit4->setStartedAt(new \DateTimeImmutable('2020-02-01'));
        $workUnit4->setDuration(4);
        $manager->persist($workUnit4);

        $manager->flush();

    }

}
