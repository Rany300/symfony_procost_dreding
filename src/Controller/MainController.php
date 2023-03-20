<?php

namespace App\Controller;

use App\Form\EmployeType;
use App\Form\JobType;
use App\Form\ProjectType;
use App\Form\WorkUnitType;
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


#[Route('/employee/edit/{id}', name:'editEmployee')]
function editEmployee (int $id): Response
    {

    $employe = $this->employeRepository->find($id);

    $jobs = $this->jobRepository->findAll();

    $jobChoices = [];
    foreach ($jobs as $job) {
        $jobChoices[$job->getName()] = $job;
    }  

    $form = $this->createForm(EmployeType::class, $employe, [
        'job_choices' => $jobChoices,
    ]);

    $form->handleRequest(Request::createFromGlobals());

    if ($form->isSubmitted() && $form->isValid()) {

        $employe = $form->getData();

        $this->employeRepository->save($employe, true);

        $this->addFlash('success', 'Employée modifié avec succès');
    }

    return $this->render('UserInterface/forms/employeeForm.html.twig', [
        'form' => $form->createView(),
    ]);

}

#[Route('/employee/details/{id}/{page}', name:'employeeDetails')]
function employeeDetails (int $id, int $page = 1): Response
    {

        $employe = $this->employeRepository->find($id);


        $projectChoices = [];
        foreach ($this->projectRepository->findAll() as $project) {
            $projectChoices[$project->getName()] = $project;
        }

        $form = $this->createForm(WorkUnitType::class, null, [
            'project_choices' => $projectChoices,
        ]);

        $form->handleRequest(Request::createFromGlobals());

        if ($form->isSubmitted() && $form->isValid()) {
            $workUnit = $form->getData();

            $workUnit->setEmploye($employe);

            $this->workUnitRepository->save($workUnit, true);

            $this->addFlash('success', 'Unité de travail ajoutée avec succès');
        }


        $workUnits = $employe->getWorkUnits();
        $workUnitsCount = count($workUnits);

        $MAX_PAGES = 10;
        $maxPages = ceil($workUnitsCount / $MAX_PAGES);

        $workUnits = array_slice($workUnits->toArray(), ($page - 1) * $MAX_PAGES, $MAX_PAGES);
        // sort by date
        usort($workUnits, function ($a, $b) {
            return $a->getStartedAt() > $b->getStartedAt();
        });

        

    return $this->render('UserInterface/details/employeeDetails.html.twig', [
        'employe' => $employe,
        'work_units' => $workUnits,
        'max_pages' => $maxPages,
        'current_page' => $page,
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


#[Route('/project/add', name:'addProject')]
function addProject (): Response
    {

    $form = $this->createForm(ProjectType::class);

    $form->handleRequest(Request::createFromGlobals());

    if ($form->isSubmitted() && $form->isValid()) {

        $project = $form->getData();

        $this->projectRepository->save($project, true);

        $this->addFlash('success', 'Projet ajouté avec succès');
    }

    return $this->render('UserInterface/forms/projectForm.html.twig', [
        'form' => $form->createView(),
    ]);
}


#[Route('/project/edit/{id}', name:'editProject')]
function editProject (int $id): Response
    {

    $project = $this->projectRepository->find($id);

    $form = $this->createForm(ProjectType::class,$project);

    $form->handleRequest(Request::createFromGlobals());

    if ($form->isSubmitted() && $form->isValid()) {

        $project = $form->getData();

        $this->projectRepository->save($project, true);

        $this->addFlash('success', 'Projet modifié avec succès');
    }

    return $this->render('UserInterface/forms/projectForm.html.twig', [
        'form' => $form->createView(),
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

#[Route('/job/add', name:'addJob')]
function addJob (): Response
    {

    $form = $this->createForm(JobType::class);

    $form->handleRequest(Request::createFromGlobals());

    if ($form->isSubmitted() && $form->isValid()) {

        $job = $form->getData();

        $this->jobRepository->save($job, true);

        $this->addFlash('success', 'Métier ajouté avec succès');
    }

    return $this->render('UserInterface/forms/jobForm.html.twig', [
        'form' => $form->createView(),
    ]);

}

#[Route('/job/edit/{id}', name:'editJob')]
function editJob (int $id): Response {

    $job = $this->jobRepository->find($id);
    $form = $this->createForm(JobType::class,$job);

    $form->handleRequest(Request::createFromGlobals());

    if ($form->isSubmitted() && $form->isValid()) {
        $job = $form->getData();
        $this->jobRepository->save($job, true);
        $this->addFlash('success', 'Métier mis a jour avec succès');
    }

    return $this->render('UserInterface/forms/jobForm.html.twig', [
        'form' => $form->createView(),
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
