<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
}
