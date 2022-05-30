<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class ArticleController extends AbstractController
{
    /**
     * @Route("/article", name="app_article")
     */
    public function index(): Response
    {
        return $this->render('article/index.html.twig', [
            'controller_name' => 'ArticleController',
        ]);
    }
    /**
     * @Route("/admin", name="admin")
     */
    public function admin(EntityManagerInterface $em, Request $request)
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);
        if( ($form->isSubmitted() && $form->isValid()))
        {
            $em->persist($article);
            $em->flush();
        }       
        return $this->render('article/admin.html.twig',[
            'form'=>$form->createView(),
        ]);
    }
    /**
     * @Route("/vue", name="vue")
     */
    public function vue(Request $request, ArticleRepository $article)
    {
        $data = $article->findAll();
        return $this->render('article/vue.html.twig',[
          'data'=>$data,
        ]);
    }
}
