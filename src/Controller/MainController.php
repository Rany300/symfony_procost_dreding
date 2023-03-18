<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use DateTimeImmutable;
use App\Repository\ProjectRepository;
use App\Repository\EmployeRepository;
use App\Repository\WorkUnitRepository;
use App\Entity\Employe;
use App\Entity\Project;
use Doctrine\DBAL\Types\VarDateTimeImmutableType;

class MainController extends AbstractController
{

    public function __construct(private ProjectRepository $projectRepository, private EmployeRepository $employeRepository, private WorkUnitRepository $workUnitRepository)
    {
    }
    



    #[Route('/', name: 'dashboard')]
    public function index(): Response
    {
        $unfinishedProjectsCount = $this->projectRepository->countActiveProjects();
        $finishedProjectsCount = $this->projectRepository->countFinishedProjects();

        $allEmployes = $this->employeRepository->findAll();
        $totalWorkTime = $this->workUnitRepository->findTotalWorkTime();

        
        $latestProjects = $this->projectRepository->findLatestProjects();
        $latesWorkUnits = $this->workUnitRepository->findLatestWorkUnits();
        
        $bestEmploye = $this->employeRepository->findBestEmployes(1);

        $profitableProjectsCount = $this->projectRepository->countProfitableProjects();

        $profitablePercentage = $profitableProjectsCount / ($finishedProjectsCount + $unfinishedProjectsCount) * 100;

        $deliveryPercentage = $finishedProjectsCount / ($finishedProjectsCount + $unfinishedProjectsCount) * 100;

        return $this->render('UserInterface/dashboard.html.twig', [
            'unfinished_projects' => $unfinishedProjectsCount,
            'finished_projects' => $finishedProjectsCount,
            'all_employes' => count($allEmployes),
            'total_work_time' => $totalWorkTime,
            'latest_projects' => $latestProjects,
            'latest_work_units' => $latesWorkUnits,
            'best_employe' => $bestEmploye[0] ?? null,
            'profitable_percentage' => $profitablePercentage,
            'delivery_percentage' => $deliveryPercentage,
        ]);
    }

    #[Route('/employees', name: 'employees')]
    public function employees(): Response
    {
        return $this->render('UserInterface/list.html.twig', [
            'controller_name' => 'MainController',
        ]);
    }


    #[Route('/detail', name: 'detail')]
    public function detail(): Response
    {
        return $this->render('UserInterface/detail.html.twig', [
            'controller_name' => 'MainController',
        ]);
    }

    #[Route('/form', name: 'form')]
    public function form(): Response
    {
        return $this->render('UserInterface/form.html.twig', [
            'controller_name' => 'MainController',
        ]);
    }




}
