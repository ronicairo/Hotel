<?php

namespace App\Controller;

use DateTime;
use App\Entity\Chambre;
use App\Entity\Commande;
use App\Form\CommandeFormType;
use App\Repository\CommandeRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CommandeController extends AbstractController
{
    #[Route('/chambre-classique', name: 'chambre_classique', methods:['GET','POST'])]
    public function chambreClassique(Request $request, CommandeRepository $repository): Response
    {
        $commande =new Commande();

        $form = $this->createForm(CommandeFormType::class, $commande)
        ->handleRequest($request);
    
        if($form->isSubmitted() && $form->isValid()) {

            $commande->setCreatedAt(new DateTime());
            $commande->setUpdatedAt(new DateTime());

            

            $repository->save($commande, true);

        $this->addFlash('success', "Votre réservation a été pris en compte !");
    
        }
        return $this->render('chambre/chambre_classique.html.twig', [
            'form' => $form->createView()
        ]);
    }
    /****************** */

    #[Route('/chambre-confort', name: 'chambre_confort', methods:['GET'])]
    public function chambreConfort(): Response
    {
        return $this->render('chambre/chambre_confort.html.twig');
    }

        /****************** */

        #[Route('/chambre-suite', name: 'chambre_suite', methods:['GET'])]
        public function chambreSuite(): Response
        {
            return $this->render('chambre/chambre_suite.html.twig');
        }
}
