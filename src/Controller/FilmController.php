<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FilmController extends AbstractController
{

    /**
     * @Route("/", name="app_homepage")
     */
    public function homepage()
    {
        return $this->render('home.html.twig');
    }
}