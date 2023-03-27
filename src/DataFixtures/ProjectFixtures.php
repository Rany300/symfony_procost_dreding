<?php

namespace App\DataFixtures;

use App\Entity\Project;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;


class ProjectFixtures extends Fixture
{
    private $projects = [
        [
            'name' => 'Procost',
            'description' => 'Application de gestion de projet',
            'price' => 100000,
            'createdAt' => '2020-01-01',
            'deliveredAt' => null,
        ],
        [
            'name' => 'Spotifly',
            'description' => 'Application musicale',
            'price' => 200000,
            'createdAt' => '2020-02-01',
            'deliveredAt' => '2020-03-01',
        ],
    ];

    public function load(ObjectManager $manager)
    {
        foreach ($this->projects as $projectData) {
            $project = new Project();
            $project->setName($projectData['name']);
            $project->setDescription($projectData['description']);
            $project->setPrice($projectData['price']);
            $project->setCreatedAt(new \DateTimeImmutable($projectData['createdAt']));
            if ($projectData['deliveredAt']) {
                $project->setDeliveredAt(new \DateTimeImmutable($projectData['deliveredAt']));
            }
            $manager->persist($project);
        }

        $manager->flush();
    }
}
