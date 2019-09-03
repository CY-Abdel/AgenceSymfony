<?php

namespace App\Controller\Admin;

use App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\MaisonsRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Maisons;
use Doctrine\Common\Persistence\ObjectManager;
use App\Form\PropertyType;



class AdminPropertyController extends AbstractController
{
    /**
     * @var MaisonsRepository
     */
    private $repository;
    /**
     * @var ObjectManager
     */
    private $em;

    public function __construct(MaisonsRepository $repository, ObjectManager $em)
    {
        $this->repository = $repository;
        $this->em = $em;
    }
   
    /**
     * @Route("/admin", name="admin.index") 
     * @param Maisons $property
     */
    public function index()
    {
        $property = $this->repository->findAll();
        return $this->render('Admin/index.html.twig',[
            'properties' => $property
        ]);
    }

    /**
     * @Route("/admin/create", name="admin.new") 
     */
    public function new(Request $request)
    {
        $property = new Maisons();

        $form = $this->createForm(PropertyType::class, $property);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $this->em->persist($property);
            $this->em->flush();
            $this->addFlash('success', 'bien crée avec succès');
            return $this->redirectToRoute('admin.index');
        }

        return $this->render('Admin/new.html.twig' , [
            'property' => $property,
            'form' => $form->createView()
        ]);

    }

     /**
     * @Route("/admin/{id}", name="admin.edit", methods="GET|POST") 
     */
    public function edit(Maisons $property, Request $request)
    {
        $form = $this->createForm(PropertyType::class, $property);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $this->em->flush();
            $this->addFlash('success', 'bien modifié avec succès');
            return $this->redirectToRoute('admin.index');
        }

        return $this->render('Admin/edit.html.twig' , [
            'property' => $property,
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Maisons $property
     * @Route("/admin/{id}", name="admin.delete", methods="DELETE") 
     */
    public function delete(Maisons $property, Request $request)
    {
        if ($this->isCsrfTokenValid('delete' . $property->getId(), $request->get('_token'))) 
        {
            $this->em->remove($property);
            $this->em->flush();
            $this->addFlash('success', 'bien supprimé avec succès');
            //return new Response('Suppression'); 
        }
        return $this->redirectToRoute('admin.index');
       
    }
}