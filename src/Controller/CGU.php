<?php

namespace App\Controller;

use App\Repository\AdsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CGU extends AbstractController {
    #[Route('/CGU', name: 'cgu')]
    public function cgu(
     
    ): Response
    {
       
        return $this->render('cgu.html.twig');
    }
}
