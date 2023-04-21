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
    #[Route('/chambre', name: 'show_chambre', methods: ['GET'])]
    public function showChambres(ChambreRepository $repository, Request $request, SluggerInterface $slugger, EntityManagerInterface $entityManager): Response
    {

        $chambres = $entityManager->getRepository(Chambre::class)->findAll();

        return $this->render('/room/show_chambre.html.twig', [
            'chambres' => $chambres
        ]);
    } // END VIEW

    #[Route('/reservation-chambre/{id}', name: 'reservation_chambre', methods: ['GET', 'POST'])]
    public function reservationChambre(Chambre $chambre, Request $request, CommandeRepository $repository, EntityManagerInterface $entityManager): Response
    {
        $commande = new Commande();

        $form = $this->createForm(CommandeFormType::class, $commande)
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // $entityManager = $this->getDoctrine()->getManager();
            // $entityManager->persist($commande);
            // $entityManager->flush();

            $dateDebut = $commande->getDateDebut();
            $dateFin = $commande->getDateFin();
            $duree = $dateDebut->diff($dateFin);
            $nbJours = $duree->days;

            $prixJournalier = $chambre->getPrixJournalier();
            $prixTotal = $prixJournalier * $nbJours;

            $commande->setPrixTotal($prixTotal);

            // $commande->setPrixTotal($chambre->getPrixJournalier() * ($commande->getDateFin() - $commande->getDateDebut()));

            $commande->setCreatedAt(new DateTime());
            $commande->setUpdatedAt(new DateTime());
            

            $repository->save($commande, true);

            // $this->addFlash('success', "Votre réservation a été pris en compte : {{commande.prixTotal}} !");
            $message = "Votre réservation a été prise en compte, le prix total est de : " . $prixTotal . " €";
            $this->addFlash('success', $message);
        }

        return $this->render('commande/reservation.html.twig', [
            'form' => $form->createView(),
            'chambre' => $chambre,
            'commande' => $commande
        ]);
    }  // end createArticle()


    #[Route('/voir-les-commandes', name: 'show_commandes', methods: ['GET'])]
    public function showCommandes( EntityManagerInterface $entityManager): Response
    {
        
        // $entityManager->persist($chambre);
        // $entityManager->flush();

        // $commande->setChambre($chambre->getTitre());

        $commandes = $entityManager->getRepository(Commande::class)->findAll();
        $chambres = $entityManager->getRepository(Chambre::class)->findAll();
        // $articles = $entityManager->getRepository(Article::class)->findBy(['author' => $this->getUser()]);
        // $chambres = $entityManager->getRepository(Commande::class)->findBy(['id'=> $this->getChambre()]);

        return $this->render('admin/commandes/show_commandes.html.twig', [
            'commandes' => $commandes,
            'chambres' => $chambres,
        ]);
    }

    #[Route('/modifier-une-commande{id}', name: 'update_commande', methods: ['GET', 'POST'])]
    public function updateArticle(Commande $article, Request $request, CommandeRepository $repository, SluggerInterface $slugger): Response
    {
        $currentPhoto = $article->getPhoto();

        $form = $this->createForm(ArticleFormType::class, $article, [
            'photo' => $currentPhoto
        ])
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $article->setCreatedAt(new DateTime());
            $article->setUpdatedAt(new DateTime());
            $article->setAlias($slugger->slug($article->getTitle()));

            $article->setAuthor($this->getUser());

            /** @var UploadedFile $photo */
            $photo = $form->get('photo')->getData();

            if ($photo) {
                $this->handleFile($photo, $article, $slugger);
                # Si une nouvelle photo est uploadé on va supprimer l'ancienne :
                unlink($this->getParameter('uploads_dir') . DIRECTORY_SEPARATOR . $currentPhoto);
            }
            else {
                $article->setPhoto($currentPhoto);
            } // end if($photo)

            $repository->save($article, true);

            $this->addFlash('success', "L'article a bien eté modifié avec succès !");
            return $this->redirectToRoute(('show_dashboard'));
        }
        return $this->render('article/form.article.html.twig', [
            'form' => $form->createView(),
            'article' => $article

        ]);
    } // end updateArticle()
    // // ------------------------------ HARD-DELETE-ARTICLE -------------------------------
    // #[Route('/supprimer-une-chambre/{id}', name: 'hard_delete_chambre', methods: ['GET'])]
    // public function hardDeleteChambre(Chambre $chambre, ChambreRepository $repository): Response

    // {
    //     $photo = $chambre->getPhoto();

    //     $repository->remove($chambre, true);

    //     unlink($this->getParameter('uploads_dir') . DIRECTORY_SEPARATOR . $photo);

    //     $this->addFlash('success', "La chambre a bien été supprimé définitivement de la base.");
    //     return $this->redirectToRoute('create_chambre');
    // } // end hardDeleteArticle()
    // ----------------------------------------------------------------------------------


}


