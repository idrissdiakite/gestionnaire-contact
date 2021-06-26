<?php

namespace App\Controller;

use App\Data\SearchData;
use App\Entity\Category;
use App\Entity\Contact;
use App\Form\SearchForm;
use App\Repository\ContactRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{

    /**
     * Affiche tous les contacts en fonction de la recherche
     * 
     * @Route("/", name="main")
     */
    public function index(ContactRepository $contactRepository, Request $request)
    {
        // Je crée un objet de la classe SearchData vide
        $data = new SearchData();
        
        // Je crée un formulaire qui utilise cette classe
        // Lorsque je vais faire un handlerequest ca va modifier cet objet qui représente mes données
        $form = $this->createForm(SearchForm::class, $data);

        // handleRequest() sert à associer la requête au formulaire
        $form->handleRequest($request);
        
        // Je vais chercher les contacts en fonction de leur categories
        // et je passe ma requête à mon formulaire
        $contacts = $contactRepository->findSearch($data);

        return $this->render('main/index.html.twig', [
            'contacts' => $contacts,
            // J'envoie le formulaire à ma vue
            'form' => $form->createView()
            ]);
    }

    /**
     * Affiche un contact selon son id
     * 
     * @Route("/contact/{id}", name="contact_show", requirements={"id": "\d+"})
     */
    public function show(int $id, ContactRepository $contactRepository): Response
    {
        // Je vais chercher le contact en fonction de son id
        $contact = $contactRepository->find($id);

        // Je vérifie si $contact vaut null
        // Si c'est le cas, j'envoie une 404 et j'arrête l'exécution de la méthode
        if ($contact === null) {
            throw $this->createNotFoundException('Le contact n\'existe pas');
        }

        return $this->render('contact/show.html.twig', [
            'contact' => $contact,
            ]);
    }
 
}