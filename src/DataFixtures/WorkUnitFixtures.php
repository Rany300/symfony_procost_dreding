<?php

namespace App\DataFixtures;

use App\Entity\Employe;
use App\Entity\Project;
use App\Entity\WorkUnit;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;


class WorkUnitFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $employees = $manager->getRepository(Employe::class)->findAll();
        $projects = $manager->getRepository(Project::class)->findAll();

        foreach ($employees as $employee) {
            foreach ($projects as $project) {
                for ($i = 1; $i <= 4; $i++) {
                    $workUnit = new WorkUnit();
                    $workUnit->setEmploye($employee);
                    $workUnit->setProject($project);
                    $workUnit->setStartedAt(new \DateTimeImmutable('2022-03-01'));
                    $workUnit->setDuration($i);
                    $manager->persist($workUnit);
                    $project->addWorkUnit($workUnit); // add the work unit to the project
                }
                $manager->persist($project); // persist the project with the new work units
            }
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            EmployeFixtures::class,
            ProjectFixtures::class,
        ];
    }
}
