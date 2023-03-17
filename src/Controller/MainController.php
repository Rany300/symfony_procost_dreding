<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route('/', name: 'dashboard')]
    public function index(): Response
    {
        return $this->render('UserInterface/dashboard.html.twig', [
            'controller_name' => 'MainController',
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
