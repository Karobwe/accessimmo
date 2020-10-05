<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class UserController extends AbstractController
{
    /**
     * Permet d'afficher et de traiter le formulaire de modification de profil
     * 
     * @Route("user/profile", name="user_profile")
     * 
     * @return Response
     */
    public function profile(Request $request) {
        
        $user = $this->getUser();
        
        $form = $this->createForm(UserType::class, $user);
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash(
                'success',
                'Les donnés du profil ont été enregistrer avec succès !'
            );
        }

        return $this->render('user/profile.html.twig', [
            'from' => $form->createView()
        ]);
    }
}
