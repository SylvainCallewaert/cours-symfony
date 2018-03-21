<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Article;
use AppBundle\Entity\Commentaire;
use AppBundle\Entity\Tag;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
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

    /**
     * @Route("/insert-form", name="article_commentaire_insert_form")
     */
    public function insertArticleFormAction(Request $request)
    {
        // récupérer le paramètre GET qui a pour clé "nom"
        /* SANS OBJET FORM DE SYMFONY
        $nomGET = $request->query->get('nom');
        var_dump($nomGET);
        // récupérer le paramètre POST qui a pour clé "nom"
        $nomPOST = $request->request->get('nom');
        var_dump($nomPOST);exit;
        // création instance d'un article manuellement

        */

        /*
            $article->setCreatedAt(new \DateTime());
            $article->setTitle("Titre article avec des commentaires");
            $article->setDescription("Je suis un article avec des commentaires");
            $article->setAuteur('fab');
         */

        $article = new Article();
        $formBuilder = $this->get('form.factory')->createBuilder(FormType::class, $article);

        // On ajoute les champs de l'entité que l'on veut à notre formulaire
        $formBuilder
            ->add('title', TextType::class, ['label' => 'Titre de l\'article', 'required' => false])
            ->add('description', Textarea::class, ['attr' => ['placeholder' => 'description de l\'article']])
            ->add('valider', SubmitType::class)
        ;
        // on récupérer l'objet form
        $form = $formBuilder->getForm();

        // handle request qui appelle automatiquement les seeter de l'objet article
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                // On enregistre notre objet $article dans la base de données, par exemple
                $em = $this->getDoctrine()->getManager();
                $em->persist($article);
                $em->flush();
                $this->addFlash('success', "L'article a bien été via un formulaire.");

                return $this->redirectToRoute('article_commentaire_insert_form');
            }
        }

        return $this->render('article/form.html.twig', ['formArticle' => $form->createView()]);
    }
}
