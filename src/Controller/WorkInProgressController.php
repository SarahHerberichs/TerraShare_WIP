<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WorkInProgressController extends AbstractController
{
    #[Route('/work-in-progress', name: 'work_in_progress')]
    public function index(): Response
    {
        return $this->render('work_in_progress/index.html.twig');
    }
}
