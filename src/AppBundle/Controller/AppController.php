<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Article;
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
        // instanciation manuelle des articles
        $article = new Article();
        $article->setTitle("Titre dynamique article");
        $article->setDescription("<p style='color:red;'>Mon paragraphe article en rouge</p>");
        $article->setAuteur("Fabian");
        $now = new \DateTime();
        $article->setCreatedAt($now);

        $article2 = new Article();
        $article2->setTitle("Titre article 2");
        $article2->setDescription("Paragraphe 2");
        $article2->setAuteur("Fab");
        $now = new \DateTime();
        $article2->setCreatedAt($now);

        // on met les deux objets articles dans un tableau "articles"
        $articles[] = $article2;
        $articles[] = $article;

        // on passe le tableau à la vue
        return $this->render('app/accueil.html.twig', [
            'articles' => $articles,
        ]);
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
