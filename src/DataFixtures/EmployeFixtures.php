<?php

namespace App\DataFixtures;

use App\Entity\Employe;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class EmployeFixtures extends Fixture implements DependentFixtureInterface
{
    private $employes = [
        [
            'name' => 'Dupont',
            'firstName' => 'Jean',
            'job' => 'Développeur',
            'email' => 'jean@procost.fr',
            'cost' => 230,
            'hiredAt' => '2020-01-01',
            'imageURL' => '/img/ui-sherman.jpg',
        ],
        [
            'name' => 'Durand',
            'firstName' => 'Marie',
            'job' => 'Chef de projet',
            'email' => 'marie@procost.fr',
            'cost' => 250,
            'hiredAt' => '2020-01-01',
            'imageURL' => '/img/ui-elliot.jpg',
        ],
        [
            'name' => 'Martin',
            'firstName' => 'Pierre',
            'job' => 'Graphiste',
            'email' => 'pierre@procost.fr',
            'cost' => 200,
            'hiredAt' => '2020-01-01',
            'imageURL' => '/img/ui-jenny.jpg',
        ],
    ];


    public function load(ObjectManager $manager)
    {
        foreach ($this->employes as $employeData) {
            $employe = new Employe();
            $employe->setName($employeData['name']);
            $employe->setFirstName($employeData['firstName']);
            $employe->setJob($this->getReference('job-' . strtolower($employeData['job'])));
            $employe->setEmail($employeData['email']);
            $employe->setCost($employeData['cost']);
            $employe->setHiredAt(new \DateTimeImmutable($employeData['hiredAt']));
            $employe->setImageURL($employeData['imageURL']);
            $manager->persist($employe);
        }

        $manager->flush();
    }


    public function getDependencies()
    {
        return [
            JobFixtures::class,
        ];
    }

}
