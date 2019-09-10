<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Notification\ContactNotification;
use App\Entity\Maisons;
use App\Entity\PropertySearch;
use App\Form\ContactType;
use App\Form\PropertySearchType;
use App\Repository\MaisonsRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;

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
    // public function index() : Response
    // {
        /*
         * pour injecter les donner dans la bse 
         */
        // $property = new Maisons();
        // $property->setTitle('mon troisieme bien')
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
        // $em->persist($property);
        // $em->flush();
      

        /**
         * avant de faire le contructeur on utiliser cette methodes pour appeler le repository
         *  
         * $property = $this->getDoctrine()->getRepository(Maisons::class);
         * dump($property);
         * 
         */

        /*
         * Recuperer les lignes de la base de donner -- trouver l'id num un 
         */
        // $property = $this->repository->find(1);

        /*
         * find all les lignes
         */
        // $property = $this->repository->findAll(); 

        /**
         * find avec parametre avec findOneBy
         */
        //$property = $this->repository->findOneBy(['floor' => 4]);
        //dump($property);

        /*
         * Si on veut retourner toutes les maison non vendu on doit utiliser le findAll mais
         * pour fonctionner il faut creer une methode et elle se fait dans ce cas (MaisonsRepository)
         * findByExampleField. a voir
         */

         /**
          * une fois on a creer notre findAllVisible on l'utilise
          */
        
        // $property = $this->repository->findAllVisible(); // depuis MaisonsRepository
        
        /* le flush va detecter que le sold a ete change a 'true' et il va apporter les modifs 
        * la base de donnee et on doit supprimer le setSold et flush car elle a deja ete changer definitivemnet
        */
        //$property[0]->setSold(true);
        //$this->em->flush();

        // recuperer tt les biens depuis la BDD
        // $properties = $this->repository->findAllVisible();

    /**
     * @Route("/maison", name="maison")
     */
    public function index(PaginatorInterface $paginator, Request $request) : Response
    {
        // Apres la pagination on fais les champs de recherche donc les champs de filtre
        $search = new PropertySearch();
        $form = $this->createForm(PropertySearchType::class, $search);
        //on lui demande de gerer la requete
        $form->handleRequest($request);

        //faire la pagination ça change comme quit
        $properties = $paginator->paginate(
            $this->repository->findAllVisibleQuery($search),
            $request->query->getInt('page', 1), /*page number par default c'est 1 ici*/
            // 12 /*limit par page 3x4*/
            9 /*limit par page 3x3*/
        );
        return $this->render('Maisons/Maison.html.twig', [
            'controller_name' => 'MaisonController',
            'current_menu'    => 'maison',
            'properties'      => $properties,
            'form'            => $form->createView()
        ]);
    }

    /**
     * @return Response
     * @Route("/maison/{slug}-{id}", name="maison.show", requirements={"slug" : "[a-z0-9\-]*"})
     */
    // public function show($slug, $id) : Response
    public function show(Maisons $property, string $slug, Request $request, ContactNotification $notification) : Response
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

        $contact = new Contact();
        $contact->setProperty($property);
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            // on notify la personne et on gere son message
            $notification->notify($contact);
            $this->addFlash('success', 'Votre email a bien été envoyé');
            return $this->redirectToRoute('maison.show', [
                'id' => $property->getId(),
                'slug' => $property->getSlug()
            ]);
        }

        return $this->render('Maisons/show.html.twig', [
            'controller_name' => 'MaisonController',
            'current_menu'    => 'maison',
            // envoyer les property a la vue
            'house'           => $property,
            'form'            => $form->createView()
        ]);
    }
}
