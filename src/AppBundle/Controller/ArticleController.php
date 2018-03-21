<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Article;
use AppBundle\Entity\Commentaire;
use AppBundle\Entity\Tag;
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
     * @Route("/display/{id}", name="article_commentaire_display")
     */
    public function displayAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        /** @var Article $article */
        $article = $em->getRepository('AppBundle:Article')->find($id);

        return $this->render('article/display.html.twig', ['article' => $article]);
    }

    /**
     * @Route("/insert", name="article_commentaire_insert")
     */
    public function insertArticleAction()
    {
        // création instance d'un article manuellement
        $article = new Article();
        $article->setCreatedAt(new \DateTime());
        $article->setTitle("Titre article avec des commentaires");
        $article->setDescription("Je suis un article avec des commentaires");
        $article->setAuteur('fab');

        $commentaire = new Commentaire();
        $commentaire->setCreatedAt(new \DateTime());
        $commentaire->setTexte("Commentaire lié à un article");
        $commentaire->setNom("Patrick");
        $commentaire->setEnabled(true);

        // liaison des deux entités :
        $article->addCommentaire($commentaire);

        // plus nécessaire car on a également associé l'article au commentaire
        // dans la méthode addCommentaire() de la classe Article
        // $commentaire->setArticle($article); // liaison bidirectionnelle

        $em = $this->getDoctrine()->getManager();
        $em->persist($article);
        //$em->persist($commentaire); // plus nécessaire car configuré dans en cascade dans le fichier article.orm
        $em->flush();

        /* code pour ajouter un commentaire pas associé à un article */
        /*
            $commentaire = new Commentaire();
            $commentaire->setCreatedAt(new \DateTime());
            $commentaire->setTexte("Commentaire orphelin");
            $commentaire->setNom("Patrick");
            $commentaire->setEnabled(true);
            $em->persist($commentaire);
            $em->flush();
        */

        $this->addFlash('success', 'Article bien enregistré avec un commentaire');
        return $this->redirectToRoute("article_commentaire_list");
    }

    /**
     * @Route("/insert-with-tags", name="article_commentaire_insert_tags")
     */
    public function insertArticleTagAction()
    {
        // création instance d'un article manuellement
        $article = new Article();
        $article->setCreatedAt(new \DateTime());
        $article->setTitle("Titre article avec des commentaires");
        $article->setDescription("Je suis un article avec des commentaires");
        $article->setAuteur('fab');

        $commentaire = new Commentaire();
        $commentaire->setCreatedAt(new \DateTime());
        $commentaire->setTexte("Commentaire lié à un article");
        $commentaire->setNom("Patrick");
        $commentaire->setEnabled(true);
        // liaison des deux entités :
        $article->addCommentaire($commentaire);

        $tag = new Tag();
        $tag->setCreatedAt(new \DateTime());
        $tag->setText('Guerre');
        // liaison des deux entités :
        $article->addTag($tag);
        $tag->addArticle($article);

        $tag2 = new Tag();
        $tag2->setCreatedAt(new \DateTime());
        $tag2->setText('Paix');
        $article->addTag($tag2);
        $tag2->addArticle($article);

        $em = $this->getDoctrine()->getManager();
        $em->persist($article);
        $em->flush();

        $this->addFlash('success', 'Article bien enregistré avec un commentaire et des tags');
        return $this->redirectToRoute("article_commentaire_list");
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
