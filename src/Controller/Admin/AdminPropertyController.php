<?php

namespace App\Controller\Admin;

use App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\MaisonsRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Maisons;
use Doctrine\Common\Persistence\ObjectManager;



class AdminPropertyController extends AbstractController
{
    /**
     * @var MaisonsRepository
     */
    private $repository;

    public function __construct(MaisonsRepository $repository)
    {
        $this->repository = $repository;
    }
   
    /**
     * @Route("/admin", name="admin.index") 
     */
    public function index()
    {
        $property = $this->repository->findAll();
        return $this->render('Admin/index.html.twig', [
            'properties' => $property
        ]);
    }

     /**
     * @Route("/admin/{id}", name="admin.edit") 
     */
    public function edit(Maisons $property)
    {
        return $this->render('Admin/edit.html.twig' , [
            'editer' => $property
        ]);
    }
}