<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Article;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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

        return $this->render('app/accueil.html.twig', [
            'articles' => $articles,
        ]);
    }

    public function afficher() {
        $var = 10 + 10;

        return $var;
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
        // récupération des articles en BDD

        // récupérer doctrine par son nom service
        // $em = $this->get('doctrine')->getManager();

        // raccourci $this->getDoctrine()
        // récupérer le manager qui gère le CRUD des entités
        $em = $this->getDoctrine()->getManager();

        // doctrine : choisir le repository (quelle classe récupérer)
        // puis findAll pour récupérer toutes les entrées en base de données
        $articles = $em->getRepository('AppBundle:Article')->findAll();

        return $this->render('app/jeu.html.twig', ['articles' => $articles]);
    }

    /*
     *  créer une méthode qui récupére l'article ayant l'id = 1
     *  et l'afficher dans un nouveau template
     */
}
