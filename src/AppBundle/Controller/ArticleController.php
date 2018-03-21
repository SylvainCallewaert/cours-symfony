<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Article;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ArticleController extends Controller
{
    /**
     * @Route("/list", name="article_commentaire_list")
     */
    public function listAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $articles = $em->getRepository('AppBundle:Article')->findAll();

        return $this->render('article/list.html.twig', ['articles' => $articles]);
    }

    /**
     * @Route("/insert", name="article_commentaire_insert")
     */
    public function insertArticleAction()
    {
        // création instance d'un article manuellement
        $article = new Article();
        $article->setCreatedAt(new \DateTime());
        $article->setTitle("Titre article inséré via doctrine");
        $article->setDescription("J'ai été enregistré grâce à l'entity manager de doctrine");
        $article->setAuteur('fab');

        $em = $this->getDoctrine()->getManager();
        $em->persist($article);
        $em->flush();

        return new Response($article->getId());
    }

    /**
     * @Route("/update/{id}", name="article_commentaire_update")
     */
    public function updateArticleAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $article = $em->getRepository('AppBundle:Article')->find($id);

        if (!$article instanceof Article) {
            throw new NotFoundHttpException();
        }

        $article->setTitle('Titre article 1 Modifié');
        $em->persist($article);
        $em->flush();

        return new Response($article->getId());
    }

    /**
     * @Route("/delete/{id}", name="article_commentaire_delete")
     */
    public function deleteArticleAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $article = $em->getRepository('AppBundle:Article')->find($id);

        if ($article instanceof Article) {
            $em->remove($article);
            $em->flush();
            $this->addFlash('success', "L'article ".$id." a bien été supprimé.");
        }
        else {
            $this->addFlash('danger', "L'article n'existe pas.");
        }

        return $this->redirectToRoute("article_commentaire_list");
    }
}
