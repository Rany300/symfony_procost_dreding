<?php

namespace App\Controller;

use App\Form\EmployeType;
use App\Repository\EmployeRepository;
use App\Repository\JobRepository;
use App\Repository\ProjectRepository;
use App\Repository\WorkUnitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class MainController extends AbstractController
{

    public function __construct(private ProjectRepository $projectRepository, private EmployeRepository $employeRepository, private WorkUnitRepository $workUnitRepository, private JobRepository $jobRepository)
    {
    }

    #[Route('/', name:'dashboard')]
function index(): Response
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

#[Route('/employees/{page}', name:'employees')]
function employees(int $page = 1): Response
    {
    $employeCount = $this->employeRepository->count([]);
    $employesPerPage = 10;
    $maxPages = ceil($employeCount / $employesPerPage);
    $employes = $this->employeRepository->findAllWithPagination($page, $employesPerPage);

    return $this->render('UserInterface/lists/employeeList.html.twig', [
        'employes' => $employes,
        'max_pages' => $maxPages,
        'current_page' => $page,
    ]);
}

#[Route('/employee/add', name:'addEmployee')]
function addEmployee (): Response
    {

    $jobs = $this->jobRepository->findAll();

    $jobChoices = [];
    foreach ($jobs as $job) {
        $jobChoices[$job->getName()] = $job;
    }  

    $form = $this->createForm(EmployeType::class, null, [
        'job_choices' => $jobChoices,
    ]);

    $form->handleRequest(Request::createFromGlobals());





    if ($form->isSubmitted() && $form->isValid()) {

        $employe = $form->getData();

        $this->employeRepository->save($employe, true);

        $this->addFlash('success', 'Employée ajouté avec succès');
    }

    return $this->render('UserInterface/forms/employeeForm.html.twig', [
        'form' => $form->createView(),
    ]);

}


#[Route('/projects/{page}', name:'projects')]
function projects(int $page = 1)
    {
    $projectCount = $this->projectRepository->count([]);
    $projectsPerPage = 10;
    $maxPages = ceil($projectCount / $projectsPerPage);
    $projects = $this->projectRepository->findAllWithPagination($page, $projectsPerPage);

    return $this->render('UserInterface/lists/projectList.html.twig', [
        'projects' => $projects,
        'max_pages' => $maxPages,
        'current_page' => $page,
    ]);
}

#[Route('/jobs/{page}', name:'jobs')]
function jobs(int $page = 1): Response
    {
    $jobCount = $this->jobRepository->count([]);
    $jobsPerPage = 10;
    $maxPages = ceil($jobCount / $jobsPerPage);
    $jobs = $this->jobRepository->findAllWithPagination($page, $jobsPerPage);

    return $this->render('UserInterface/lists/jobList.html.twig', [
        'jobs' => $jobs,
        'max_pages' => $maxPages,
        'current_page' => $page,
    ]);
}

#[Route('/detail', name:'detail')]
function detail(): Response
    {
    return $this->render('UserInterface/detail.html.twig', [
        'controller_name' => 'MainController',
    ]);
}

#[Route('/form', name:'form')]
function form(): Response
    {
    return $this->render('UserInterface/form.html.twig', [
        'controller_name' => 'MainController',
    ]);
}

}
