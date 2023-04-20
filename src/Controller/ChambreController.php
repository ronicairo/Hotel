<?php

namespace App\Controller;

use DateTime;
use App\Entity\Chambre;
use App\Form\ChambreFormType;
use App\Repository\ChambreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class ChambreController extends AbstractController
{
    #[Route('/ajouter-une-chambre', name: 'create_chambre', methods: ['GET', 'POST'])]
    public function createChambre(ChambreRepository $repository, Request $request, SluggerInterface $slugger,EntityManagerInterface $entityManager): Response
    {
        $chambre = new Chambre();

        $form = $this->createForm(ChambreFormType::class, $chambre)
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $chambre->setCreatedAt(new DateTime());
            $chambre->setUpdatedAt(new DateTime());
       

            # Set de la relation entre Article et User
            // $chambre->setAuthor($this->getUser());

            /** @var UploadedFile $photo */
            $photo = $form->get('photo')->getData();

            if ($photo) {
                $this->handleFile($photo, $chambre, $slugger);
            } //end if($photo)

            $repository->save($chambre, true);

            $this->addFlash('success', "L'article a bien été crée avec succès !");
            // return $this->redirectToRoute('create_chambre');
        } // end if($form)

$chambres=$entityManager->getRepository(Chambre::class)->findAll();
// dd($chambres);

        return $this->render('admin/chambre/create_chambre.html.twig', [
            'form' => $form->createView(),
            'chambres'=>$chambres,
          
        ]);
    
    } // end createArticle()

    #[Route('/modifier-une-chambre{id}', name: 'update_chambre', methods: ['GET', 'POST'])]
    public function updateChambre(Chambre $chambre, Request $request, ChambreRepository $repository, SluggerInterface $slugger,EntityManagerInterface $entityManager): Response
    {
        $currentPhoto = $chambre->getPhoto();

        $form = $this->createForm(ChambreFormType::class, $chambre, [
            'photo' => $currentPhoto
        ])
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $chambre->setCreatedAt(new DateTime());
            $chambre->setUpdatedAt(new DateTime());
        

            // $chambre->setAuthor($this->getUser());

            /** @var UploadedFile $photo */
            $photo = $form->get('photo')->getData();

            if ($photo) {
                $this->handleFile($photo, $chambre, $slugger);
                # Si une nouvelle photo est uploadé on va supprimer l'ancienne :
                unlink($this->getParameter('uploads_dir') . DIRECTORY_SEPARATOR . $currentPhoto);
            }
            else {
                $chambre->setPhoto($currentPhoto);
            } // end if($photo)

            $repository->save($chambre, true);

            $this->addFlash('success', "L'article a bien eté modifié avec succès !");
            // return $this->redirectToRoute(('show_dashboard'));
        }
        $chambres=$entityManager->getRepository(Chambre::class)->findAll();
        return $this->render('admin/chambre/create_chambre.html.twig', [
            'form' => $form->createView(),
            'chambres' => $chambres

        ]);
    
    } // end updateArticle()











// ------------------------------ HARD-DELETE-ARTICLE -------------------------------
#[Route('/supprimer-une-chambre/{id}', name: 'hard_delete_chambre', methods: ['GET'])]
public function hardDeleteChambre(Chambre $chambre, ChambreRepository $repository): Response

{
    $photo = $chambre->getPhoto();

    $repository->remove($chambre, true);

    unlink($this->getParameter('uploads_dir') . DIRECTORY_SEPARATOR . $photo);

    $this->addFlash('success', "La chambre a bien été supprimé définitivement de la base.");
    return $this->redirectToRoute('create_chambre');
} // end hardDeleteArticle()
// ----------------------------------------------------------------------------------






    private function handleFile(UploadedFile $photo, Chambre $chambre, SluggerInterface $slugger)
    {
        $extension = "." . $photo->guessExtension();
        $safeFilename = $slugger->slug(pathinfo($photo->getClientOriginalName(), PATHINFO_FILENAME));

        $newFilename = $safeFilename . '_' . uniqid() . $extension;

        try {
            $photo->move($this->getParameter('uploads_dir'), $newFilename);
            $chambre->setPhoto($newFilename);
        } catch (FileException $exception) {
            // code a exécuter en cas d'erreur
        }
    } // end handleFile()

    #[Route('/', name: 'show_chambre', methods:['GET'])]
    public function showHome(): Response
    {
        return $this->render('chambre/show_chambre.html.twig');
    }
}
