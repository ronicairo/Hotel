<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SpaController extends AbstractController

{
    #[Route('/spadetente', name: 'show_spa_detente',methods:['GET'] )]
    public function showSpaDetente(): Response
    {
        return $this->render('spadetente/show_spa_detente.html.twig', [
        
        ]);
    } 
    
    #[Route('/sparelaxant', name: 'show_spa_relaxant',methods:['GET'] )]
    public function showSpaRelaxant(): Response
    {
        return $this->render('sparelaxant/show_spa_relaxant.html.twig', [
        
        ]);
    }

    #[Route('/spaplaisir', name: 'show_spa_plaisir',methods:['GET'] )]
    public function showSpaPlaisir(): Response
    {
        return $this->render('spaplaisir/show_spa_plaisir.html.twig', [

        ]);
    }

}