<?php

namespace App\Controller;

use App\Entity\Photo;
use App\Form\PhotoType;
use App\Repository\PhotoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("dashboard/photo")
 */
class PhotoController extends AbstractController
{
    /**
     * @var PhotoRepository
     */
    private $photoRepository;
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(PhotoRepository $photoRepository, EntityManagerInterface $em)
    {
        $this->photoRepository = $photoRepository;
        $this->em = $em;
    }

    /**
     * @Route("/", name="photo_admin_index")
     * @return Response
     */
    public function index(PaginatorInterface $paginator, Request $request): Response
    {
        $photos = $paginator->paginate(
            $this->photoRepository->findAllVisibleQuery(),
            $request->query->getInt('page', 1),
            10
        );
        return $this->render('photo/index.html.twig', [
            'photos' => $photos
        ]);
    }

    /**
     * @Route("/new", name="photo_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $photo = new Photo();
        $form = $this->createForm(PhotoType::class, $photo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($photo);
            $entityManager->flush();

            $this->addFlash('success', 'La photo a bien été mise en ligne');
            return $this->redirectToRoute('photo_admin_index');
        }

        return $this->render('photo/new.html.twig', [
            'photo' => $photo,
            'form' => $form->createView(),
        ]);
    }

    /**
         * @Route("/{id}", name="photo_show", methods={"GET"})
     */
    public function show(Photo $photo): Response
    {
        return $this->render('photo/show.html.twig', [
            'photo' => $photo,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="photo_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Photo $photo): Response
    {
        $form = $this->createForm(PhotoType::class, $photo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'La photo a bien été mise à jour');
            return $this->redirectToRoute('photo_admin_index');
        }

        return $this->render('photo/edit.html.twig', [
            'photo' => $photo,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="photo_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Photo $photo): Response
    {
        if ($this->isCsrfTokenValid('delete'.$photo->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($photo);
            $entityManager->flush();
        }

        $this->addFlash('success', 'La photo a bien été supprimée');
        return $this->redirectToRoute('photo_admin_index');
    }
}
