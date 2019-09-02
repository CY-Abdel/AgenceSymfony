<?php
// src/Controller/AdvertController.php

namespace App\Controller;

use App\Repository\MaisonsRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Twig\Environment;

class HomeController
{

    /**
     * @var Environment 
     */
    private $twig;

    public function __construct(Environment $twig)
    {
        $this->tiwg = $twig;
    }  

    
    /**
     * @Route("/", name="home")
     */
    public function index(Environment $twig, MaisonsRepository $repository)
    {
        $property = $repository->findLatest();
        // dump($property);
        $content = $twig->render('Home/Home.html.twig', [
            'properties' => $property
        ]);
        return new Response($content);
        
    }

    /**
     * @Route("/index2", name="index2")
     */
    public function index2()
    {
        $content = "tout marche bien";
        return new Response($content);
    }
}
