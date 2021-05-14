<?php

namespace App\Controller;

use App\Entity\Galerie;
use App\Entity\Photo;
use App\Form\GalerieType;
use App\Form\PhotoType;
use App\Repository\GalerieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
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
     * @var GalerieRepository
     */
    private $galerieRepository;
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(GalerieRepository $galerieRepository, EntityManagerInterface $em)
    {
        $this->galerieRepository = $galerieRepository;
        $this->em = $em;
    }
    /**
     * @Route("/", name="galerie_admin_index")
     * @return Response
     */
    public function index(PaginatorInterface $paginator, Request $request): Response
    {
        $galeries = $paginator->paginate(
            $this->galerieRepository->findAllVisibleQuery(),
            $request->query->getInt('page', 1),
            6
        );
        return $this->render('galerie/index.html.twig', [
            'galeries' => $galeries
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

            $this->addFlash('success', 'La galerie a bien été créée');
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

            $this->addFlash('success', 'La galerie a bien été modifiée');
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
        $this->addFlash('success', 'La galerie a bien été supprimée');
        return $this->redirectToRoute('galerie_admin_index');
    }
}
