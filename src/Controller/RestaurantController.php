<?php

namespace App\Controller;


use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RestaurantController extends AbstractController
{
    #[Route('/restaurant', name: 'show_restaurant', methods:['GET'])]
    public function ShowRestaurant(): Response
    {
        return $this->render('restaurant/show_restaurant.html.twig', [
            
        ]);
    }

    #[Route('/brasserie', name: 'show_brasserie', methods:['GET'])]
    public function ShowBrasserie(): Response
    {
        return $this->render('restaurant/show_brasserie.html.twig', [
            
        ]);
    }
    
    #[Route('/degustation', name: 'show_degustation', methods:['GET'])]
    public function Show(): Response
    {
        return $this->render('restaurant/show_degustation.html.twig', [
            
        ]);
    }
}
