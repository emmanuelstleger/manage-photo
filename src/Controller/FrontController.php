<?php

namespace App\Controller;

use App\Entity\Galerie;
use App\Entity\Photo;
use App\Repository\GalerieRepository;
use App\Repository\PhotoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FrontController extends AbstractController
{
    /**
     * @Route("/", name="front_index", methods={"GET"})
     */
    public function index(GalerieRepository $galerieRepository): Response
    {
        return $this->render('front/index.html.twig', [
            'controller_name' => 'FrontController',
            'galeries'=>$galerieRepository->findAll(),
        ]);
    }

    /**
     * @Route("/about", name="front_about", methods={"GET"})
     */
    public function frontAbout()
    {
        return $this->render('front/about.html.twig');
    }

    /**
     * @Route("/contact", name="front_contact", methods={"GET"})
     */
    public function frontContact()
    {
        return $this->render('front/contact.html.twig');
    }

    /**
     * @Route("/portfolio", name="front_portfolio", methods={"GET"})
     */
    public function frontPortfolio(GalerieRepository $galerieRepository)
    {
        return $this->render('front/portfolio.html.twig',[
            'galeries'=>$galerieRepository->findAll(),
        ]);
    }

    /**
     * @Route("/galerie/{id}", name="front_galerie", methods={"GET"})
     */
    public function showFrontGalerie(Galerie $galerie): Response
    {
        return $this->render('front/galerie.html.twig',[
            'galerie'=> $galerie,
        ]);
    }
}
