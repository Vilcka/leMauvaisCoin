<?php

namespace App\Controller;

use App\Entity\Annonce;
use App\Form\AnnonceType;
use App\Repository\AnnonceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

/**
 * @Route("/annonce")
 */
class AnnonceController extends Controller
{
    /**
     * @Route("/", name="annonce_index", methods="GET")
     * @param AnnonceRepository $annonceRepository
     * @return Response
     */
    public function index(AnnonceRepository $annonceRepository): Response
    {
        return $this->render('annonce/index.html.twig', ['annonces' => $annonceRepository->findAll()]);
    }

    /**
     * @Route("/new", name="annonce_new", methods="GET|POST")
     * @param Request $request
     * @param Security $security
     * @return Response
     */
    public function new(Request $request, Security $security): Response
    {
        if ($security->getUser()) {
            $annonce = new Annonce();
            $form = $this->createForm(AnnonceType::class, $annonce);
            $form->handleRequest($request);
            $user = $this->getUser();
            $annonce->setCreatorId($user);
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($annonce);
            $em->flush();

            return $this->redirectToRoute('annonce_index');
        }

        return $this->render('annonce/new.html.twig', [
            'annonce' => $annonce,
            'form' => $form->createView(),

        ]);
    }

    /**
     * @Route("/{id}", name="annonce_show", methods="GET")
     * @param Annonce $annonce
     * @return Response
     */
    public function show(Annonce $annonce): Response
    {
        return $this->render('annonce/show.html.twig', ['annonce' => $annonce]);
    }

    /**
     * @Route("/{id}/edit", name="annonce_edit", methods="GET|POST")
     * @param Request $request
     * @param Annonce $annonce
     * @return Response
     */
    public function edit(Request $request, Annonce $annonce): Response
    {
        $form = $this->createForm(AnnonceType::class, $annonce);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('annonce_edit', ['id' => $annonce->getId()]);
        }

        return $this->render('annonce/edit.html.twig', [
            'annonce' => $annonce,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="annonce_delete", methods="DELETE")
     * @param Request $request
     * @param Annonce $annonce
     * @return Response
     */
    public function delete(Request $request, Annonce $annonce): Response
    {
        if ($this->isCsrfTokenValid('delete'.$annonce->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($annonce);
            $em->flush();
        }

        return $this->redirectToRoute('annonce_index');
    }
}
