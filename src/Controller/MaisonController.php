<?php

namespace App\Controller;

use App\Entity\Maisons;
use App\Repository\MaisonsRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;


class MaisonController extends AbstractController
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
     * @Route("/maison", name="maison")
     */
    public function maison()
    {
        /*
         * pour injecter les donner dans la bse 
         */
        // $proprety = new Maisons();
        // $proprety->setTitle('mon troisieme bien')
        //     ->setPrice(120000)
        //     ->setRooms(8)
        //     ->setBedrooms(5)
        //     ->setDescription('une petite description')
        //     ->setSurface(200)
        //     ->setFloor(6)
        //     ->setHeat(3)
        //     ->setCity('Paris')
        //     ->setAdresse('23 street boys')
        //     ->setPostalCode('75000');
        // $em = $this->getDoctrine()->getManager();
        // $em->persist($proprety);
        // $em->flush();
      

        /**
         * avant de faire le contructeur on utiliser cette methodes pour appeler le repository
         *  
         * $proprety = $this->getDoctrine()->getRepository(Maisons::class);
         * dump($proprety);
         * 
         */

        /*
         * Recuperer les lignes de la base de donner -- trouver l'id num un 
         */
        // $proprety = $this->repository->find(1);

        /*
         * find all les lignes
         */
        // $proprety = $this->repository->findAll(); 

        /**
         * find avec parametre avec findOneBy
         */
        //$proprety = $this->repository->findOneBy(['floor' => 4]);
        //dump($proprety);

        /*
         * Si on veut retourner toutes les maison non vendu on doit utiliser le findAll mais
         * pour fonctionner il faut creer une methode et elle se fait dans ce cas (MaisonsRepository)
         * findByExampleField. a voir
         */

         /**
          * une fois on a creer notre findAllVisible on l'utilise
          */
        
        // $proprety = $this->repository->findAllVisible(); // depuis MaisonsRepository
        
        /* le flush va detecter que le sold a ete change a 'true' et il va apporter les modifs 
        * la base de donnee et on doit supprimer le setSold et flush car elle a deja ete changer definitivemnet
        */
        //$proprety[0]->setSold(true);
        //$this->em->flush();

        return $this->render('Maisons/Maison.html.twig', [
            'controller_name' => 'MaisonController',
            'current_menu' => 'maison',
        ]);
    }

    /**
     * @return Response
     * @Route("/maison/{slug}-{id}", name="maison.show", requirements={"slug" : "[a-z0-9\-]*"})
     */
    // public function show($slug, $id) : Response
    public function show(Maisons $property, string $slug) : Response
    {
        /**
         * si on fait pas les parametres slug et id et on fait maisons $property a la place
         * on peut supprimer la ligne de      find($id)
         */
        // $property = $this->repository->find($id);

        if ($property->getSlug() !== $slug)
        {
            return $this->redirectToRoute('maison.show', [
                'id' => $property->getId(),
                'slug' => $property->getSlug(),
            ], 301);
        }
        return $this->render('Maisons/show.html.twig', [
            'controller_name' => 'MaisonController',
            'current_menu' => 'maison',
            // envoyer les property al a vue
            'house' => $property,
        ]);
    }
}
