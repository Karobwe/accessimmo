<?php

namespace App\Controller;

use App\Entity\Housing;
use App\Form\Housing1Type;
use App\Repository\HousingRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/housing")
 */
class HousingController extends AbstractController
{
    /**
     * @Route("/", name="housing_index", methods={"GET"})
     */
    public function index(HousingRepository $housingRepository): Response
    {
        return $this->render('housing/index.html.twig', [
            'housings' => $housingRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="housing_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $housing = new Housing();
        $form = $this->createForm(Housing1Type::class, $housing);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($housing);
            $entityManager->flush();

            return $this->redirectToRoute('housing_index');
        }

        return $this->render('housing/new.html.twig', [
            'housing' => $housing,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="housing_show", methods={"GET"})
     */
    public function show(Housing $housing): Response
    {
        return $this->render('housing/show.html.twig', [
            'housing' => $housing,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="housing_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Housing $housing): Response
    {
        $form = $this->createForm(Housing1Type::class, $housing);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('housing_index');
        }

        return $this->render('housing/edit.html.twig', [
            'housing' => $housing,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="housing_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Housing $housing): Response
    {
        if ($this->isCsrfTokenValid('delete'.$housing->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($housing);
            $entityManager->flush();
        }

        return $this->redirectToRoute('housing_index');
    }
}
