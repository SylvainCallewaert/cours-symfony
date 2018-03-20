<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Article;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\User\User;

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
    /**
     * @Route("/article1", name="article1")
     */
    public function article1Action(Request $request)
    {
        // récupération de l'article en BDDD qui a l'ID 1

        $em = $this->getDoctrine()->getManager();
        $article = $em->getRepository('AppBundle:Article')->find(1);

        if ($article == null) {
            throw new NotFoundHttpException();
        }

        return $this->render('app/article1.html.twig', ['article' => $article]);
    }


    /**
     * @Route("/article/ajout", name="article_ajout")
     */
    public function insertArticleAction()
    {
        // création instance d'un article manuellement
        $article = new Article();
        $article->setCreatedAt(new \DateTime());
        $article->setTitle("Titre article inséré via doctrine");
        $article->setDescription("J'ai été enregistré grâce à l'entity manager de doctrine");
        $article->setAuteur('fab');

        // enregistrement en base grâce à doctrine
        $em = $this->getDoctrine()->getManager();
        // persist pour dire à doctrine d'enregistrer l'objet lors du flush
        $em->persist($article);

        // flush : execute les requetes pour insérer tous les objets
        // sur lesquels on a préalablement passé à la méthode persist de $em
        $em->flush();

        // afficher à l'écran l'ID de l'article qui vient d'être créé
        // cet ID est immédiatement disponible après l'appel à $em->flush()
        // grâce au getter getId() de l'objet persisté
        return new Response($article->getId());
    }

    /**
     * @Route("/article/update/{id}", name="article_update")
     */
    public function updateArticleAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $article = $em->getRepository('AppBundle:Article')->find($id);

        if (!$article instanceof Article) {
            throw new NotFoundHttpException();
        }

        $article->setTitle('Titre article 1 Modifié');

        // persist pour dire à doctrine de modifier l'objet lors du flush
        // persist est utilisé pour les insert et les update
        // la différence se fait grâce à l'ID de l'objet
        // qui est soit null (insert), soit setté (update)
        $em->persist($article);

        // flush : execute les requetes pour modifier tous les objets
        // sur lesquels on a préalablement passé à la méthode persist de $em
        $em->flush();

        // afficher à l'écran l'ID de l'article qui vient d'être créé
        // cet ID est immédiatement disponible après l'appel à $em->flush()
        // grâce au getter getId() de l'objet persisté
        return new Response($article->getId());
    }

    /**
     * @Route("/article/delete/{id}", name="article_delete")
     */
    public function deleteArticleAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $article = $em->getRepository('AppBundle:Article')->find($id);

        if ($article instanceof Article) {
            // remove pour dire à doctrine de supprimer l'objet en bdd
            // lors du flush
            $em->remove($article);

            // flush : execute les requetes pour supprimer tous les objets
            // sur lesquels on a préalablement passé à la méthode remove de $em
            $em->flush();
        }

        // après cette ligne, $article->getId() renvoie NULL

        return $this->redirectToRoute("jeu");
    }

    /**
     * @Route("/article/{id}", name="article_affichage")
     */
    public function articleAction(Request $request, $id)
    {
        // récupération de l'article en BDD qui a l'ID 1

        $em = $this->getDoctrine()->getManager();
        $article = $em->getRepository('AppBundle:Article')->find($id);

        if ($article == null) {
            throw new NotFoundHttpException();
        }

        return $this->render('app/article.html.twig', ['article' => $article]);
    }

    /**
     *
     * Dans le template jeu.html.twig, créer un lien de suppression pour chaque article
     *
     */

}
