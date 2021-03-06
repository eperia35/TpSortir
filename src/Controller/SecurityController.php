<?php

namespace App\Controller;

use App\Entity\Participant;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    public const ROUTE_HOME = 'sortie';

    /**
     * @Route("/login", name="login")
     * @param AuthenticationUtils $authenticationUtils
     * @return Response
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
         if ($this->getUser()) {
             $repo = $this->getDoctrine()->getRepository(Participant::class);
             $p = $repo->findOneBy(['pseudo'=>$this->getUser()->getUsername()], []);
             if ($p && $p->getActif()) {
                 return $this->redirectToRoute(self::ROUTE_HOME);
             } else {
                 $this->addFlash("danger", "Votre compte a été désactivé.");
                 return $this->redirectToRoute('login');
             }
         }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        // focus sur mdp si last user name
        $autofocusPswd  = ($lastUsername) ? "autofocus" : "";
        $autofocusLogin = ($lastUsername) ? "" : "autofocus";

        return $this->render('security/login.html.twig', [
            'last_username'  => $lastUsername,
            'error'          => $error,
            'autofocusPswd'  => $autofocusPswd,
            'autofocusLogin' => $autofocusLogin,
        ]);
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }


    /**
     * @Route("/", name="base")
     */
    public function index()
    {
        //TODO: redirect sur page LISTE_SORTIES
        return $this->render("sortie/sortie.html.twig", []);
    }
}
