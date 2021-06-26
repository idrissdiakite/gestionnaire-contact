<?php

namespace App\Controller\Admin;

use App\Entity\Contact;
use App\Form\ContactType;
use App\Form\SearchForm;
use App\Repository\ContactRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/contact", name="admin_contact_")
 */
class ContactController extends AbstractController
{
    /**
     * @Route("", name="list")
     */
    public function list(contactRepository $contactRepository): Response
    {
        return $this->render('main/index.html.twig', [
            'contacts' => $contactRepository->findAll(),
        ]);
    }
    
    /**
     * @Route("/add", name="add")
     */
    public function add(Request $request): Response
    {

        // Je crée un objet de la classe Contact vide
        $contact = new Contact();

        // Je crée un objet Form en lui précisant
        //     - de se baser sur la définition d'un formulaire donnée dans ContactType
        //     - de s'associer à l'objet $contact
        $form = $this->createForm(ContactType::class, $contact);

        // handleRequest() associe la requête au formulaire
        // Cette méthode va prendre les informations reçues en POST et les associer aux champs de $form
        // Ce qui va permettre de pré-remplir les champs
        // Par la même occasion, cette méthode va modifier $contact et lui attribuer toutes les valeurs reçues dans $request
        $form->handleRequest($request);

        // Pour ajouter $contact en BDD, il faut absolument que le formulaire soit valide et soumis
        // J'utilise donc isSubmitted() pour vérifier que le formulaire a été soumis
        // J'utilise isValid() pour vérifier que le formulaire est valide.
        //    Cela permet de également de vérifier que le token anti-CSRF est valide
        //    Sur un autre aspect, ça permet aussi de valider des contraintes déclarés dans le formulaire
        if ($form->isSubmitted() && $form->isValid()) {

            // Je précise qu'il faut être ROLE_ADMIN pour avoir le droit d'ajouter un contact
            // Un ROLE_USER ne peut donc pas créer de nouveau contact
            // J'utilise denyAccessUnlessGranted() avec un rôle pour tester le rôle du user connecté
            $this->denyAccessUnlessGranted('ROLE_ADMIN', $contact);
            
            // Je récupère l'entity manager
            $em = $this->getDoctrine()->getManager();
            
            // Je persiste $contact pour annoncer à l'entity manager que je souhaite stocker cet objet en BDD
            $em->persist($contact);
            
            // Je flushe les modifications
            $em->flush();

            // OJen redirige sur la liste des contacts
            return $this->redirectToRoute('main');
        }
        
        return $this->render('admin/contact/add.html.twig', [

            // createView() permet de créer un objet de la classe FormView car Twig ne sait quoi faire d'un objet Form
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/edit/{id}", name="edit")
     */
    public function edit(Contact $contact, Request $request)
    {

        // Je crée un objet $form à partir de ContactType et je l'associe à mon objet $contact
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
           
            // Je flushe, sans persister, parce que l'objet est déjà persisté
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('main');
        }

        return $this->render('admin/contact/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/delete/{id}", name="delete", methods={"DELETE"})
     */
    public function delete(Contact $contact)
    {
        // Pour supprimer un objet j'ai besoin de l'entity manager
        $em = $this->getDoctrine()->getManager();
        
        // J'indique au manager de supprimer l'objet $contact
        $em->remove($contact);
        
        // Je demande au manager d'appliquer toutes les modifications
        $em->flush();

        // Je redirige vers la liste des contacts
        return $this->redirectToRoute('main');
    }

}