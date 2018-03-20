<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class AppController extends Controller
{
    /**
     * @Route("/accueil", name="nom_page_accueil")
     */
    public function accueilAction(Request $request)
    {
        return $this->render('app/accueil.html.twig');
    }

    /**
     * @Route("/login", name="login")
     */
    public function loginAction(Request $request)
    {
        return $this->render('app/login.html.twig');
    }

    /**
     * @Route("/jeu", name="jeu")
     */
    public function jeuAction(Request $request)
    {
        return $this->render('app/jeu.html.twig');
    }
}
