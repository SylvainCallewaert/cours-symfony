<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\User;

class DefaultController extends Controller
{
    /**
     * @Route("/test", name="homepage")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
        ]);
    }

    /**
     * @Route("/profil", name="profil")
     */
    public function profilAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('base.html.twig');
    }

    /**
     * @Route("/inscription", name="inscription")
     */
    public function inscriptionAction(Request $request)
    {
        return $this->render('default/inscription.html.twig');
    }

    /**
     * @Route("/inscription2", name="inscription2")
     */
    public function inscription2Action(Request $request)
    {
        $user = new User("username", "1234");

        // passer la variable php $user au template twig
        // pour pouvoir l'utiliser avec {{ }} au sein du template
        return $this->render('default/inscription2.html.twig', [
            'user' => $user
        ]);
    }
}
