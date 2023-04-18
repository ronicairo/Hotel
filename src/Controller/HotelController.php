<?php

namespace App\Controller;

use App\Form\ContactFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HotelController extends AbstractController
{
    #[Route('/quisommesnous', name: 'show_quisommesnous')]
    public function quisommesNous(): Response
    {
        return $this->render('hotel/quisommesnous.html.twig');
    }

    #[Route('/acces', name: 'show_acces')]
    public function showAcces(): Response
    {
        return $this->render('hotel/acces.html.twig');
    }

    #[Route('/contact', name: 'show_contact')]
    public function showContact(Request $request): Response
    {
        $form = $this->createForm(ContactFormType::class)
        ->handleRequest($request);
    
        if($form->isSubmitted() && $form->isValid()) {

        $this->addFlash('success', "Votre demande a bien été envoyée !");
    
        }
        
    return $this->render('hotel/contact_form.html.twig', [
        'form' => $form->createView()
    ]);

}

}
