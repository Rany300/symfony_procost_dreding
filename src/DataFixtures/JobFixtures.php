<?php

namespace App\DataFixtures;

use App\Entity\Job;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;


class JobFixtures extends Fixture
{
    private $jobs = [
        'Développeur',
        'Chef de projet',
        'Graphiste',
    ];

    public function load(ObjectManager $manager)
    {
        foreach ($this->jobs as $jobName) {
            $job = new Job();
            $job->setName($jobName);
            $manager->persist($job);
            $this->addReference('job-' . strtolower($jobName), $job);
        }

        $manager->flush();
    }
}
