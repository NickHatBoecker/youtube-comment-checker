<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class VueController extends AbstractController
{
    /**
     * @Route("/{vueRouting}", name="vue")
     */
    public function index(): Response
    {
        return $this->render('vue/index.html.twig', []);
    }
}
