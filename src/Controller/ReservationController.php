<?php

namespace App\Controller;

use DateTime;
use App\Entity\Chambre;
use App\Entity\Commande;
use App\Form\CommandeFormType;
use App\Repository\ChambreRepository;
use App\Repository\CommandeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ReservationController extends AbstractController
{
    #[Route('/chambre', name: 'show_chambre', methods:['GET'])]
    public function showChambres(ChambreRepository $repository, Request $request, SluggerInterface $slugger,EntityManagerInterface $entityManager): Response
    {

        $chambres=$entityManager->getRepository(Chambre::class)->findAll();

        return $this->render('/room/show_chambre.html.twig', [
            'chambres' => $chambres
        ]); 
    
    } // END VIEW

    #[Route('/reservation-chambre/{id}', name: 'reservation_chambre', methods:['GET','POST'])]
    public function reservationChambre(Chambre $chambre, Request $request, CommandeRepository $repository): Response
    {
        $commande = new Commande();

        $form = $this->createForm(CommandeFormType::class, $commande)
        ->handleRequest($request);
    
        if($form->isSubmitted() && $form->isValid()) {

            $commande->setCreatedAt(new DateTime());
            $commande->setUpdatedAt(new DateTime());
            $commande->setPrixTotal('300');

            $repository->save($commande, true);

        $this->addFlash('success', "Votre réservation a été pris en compte !");

        }

        return $this->render('commande/reservation.html.twig', [
            'form' => $form->createView(),
            'chambre' => $chambre
        ]);
    }  // end createArticle()

    
    }

