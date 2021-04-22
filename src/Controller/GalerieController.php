<?php

namespace App\Controller;

use App\Entity\Galerie;
use App\Entity\Photo;
use App\Form\GalerieType;
use App\Form\PhotoType;
use App\Repository\GalerieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("dashboard/galerie")
 */
class GalerieController extends AbstractController
{
    /**
     * @Route("/", name="galerie_admin_index", methods={"GET"})
     */
    public function index(GalerieRepository $galerieRepository): Response
    {
        return $this->render('galerie/index.html.twig', [
            'galeries' => $galerieRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="galerie_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $galerie = new Galerie();
        $form = $this->createForm(GalerieType::class, $galerie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($galerie);
            $entityManager->flush();

            return $this->redirectToRoute('galerie_admin_index');
        }

        return $this->render('galerie/new.html.twig', [
            'galerie' => $galerie,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="galerie_show", methods={"GET"})
     */
    public function show(Galerie $galerie): Response
    {
        return $this->render('galerie/show.html.twig', [
            'galerie' => $galerie,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="galerie_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Galerie $galerie): Response
    {
        $form = $this->createForm(GalerieType::class, $galerie);
        $form->handleRequest($request);


        $photo = new Photo();
        $form2 = $this->createForm(PhotoType::class, $photo);
        $form2->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('galerie_admin_index');
        }

        if ($form2->isSubmitted() && $form2->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $photo->setGalerie($galerie)->setUser($this->getUser());
            $entityManager->persist($photo);
            $entityManager->flush();
        }

        return $this->render('galerie/edit.html.twig', [
            'galerie' => $galerie,
            'form' => $form->createView(),
            'form2'=> $form2->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="galerie_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Galerie $galerie): Response
    {
        if ($this->isCsrfTokenValid('delete'.$galerie->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($galerie);
            $entityManager->flush();
        }

        return $this->redirectToRoute('galerie_admin_index');
    }
}
