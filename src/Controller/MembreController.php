<?php

namespace App\Controller;

use DateTime;
use App\Entity\Membre;
use App\Form\MembreFormType;
use App\Repository\MembreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class MembreController extends AbstractController
{
    // #[Route('/connexion', name:'connexion', methods: ['GET'])]
    // public function connexion(Request $request, MembreRepository $repository, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager): Response
    // {


    // }





    #[Route('/inscription', name:'register', methods: ['GET', 'POST'])]
    public function register(Request $request, MembreRepository $repository, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager): Response
    {
        if ($this->getUser()) {
            $this->addFlash('warning', "Vous êtes déja membre. <a href='/logout'>Deconnexion </a>");
            return $this->redirectToRoute('show_home');
        }
        $membre = new Membre();

        $form = $this->createForm(MembreFormType::class, $membre)
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            $membre->setCreatedAt(new DateTime());
            $membre->setUpdatedAt(new DateTime());

            $membre->setRoles(['ROLE_ADMIN']);


            # On doit resseter manuellement la valeur du password, car par défaut il n'est pas hashé.
            # Pour cela, nous devons utiliser une méthode de hashage appelée hashPassword() :
            #   => cette méthode attend 2 arguments : $user, $plainPassword
            $membre->setPassword(
                $passwordHasher->hashPassword($membre, $membre->getPassword())
            );

            $repository->save($membre, true);

            $this->addFlash('success', "Votre inscription a été correctement enregistrée");
            //return $this->redirectToRoute('app_login');

        }

        $membres = $entityManager->getRepository(Membre::class)->findAll();

        return $this->render('admin/register_form.html.twig', [
            'form' => $form->createView(),
            'membres'=>$membres
        ]);
    }


}
