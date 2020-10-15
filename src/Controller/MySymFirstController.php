<?php

namespace App\Controller;
use App\Entity\Article;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
//use Symfony\Component\BrowserKit\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Routing\Annotation\Route;


USE Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class MySymFirstController extends AbstractController
{
    /**
     * @Route("/my/sym/first", name="my_sym_first")
     */
    public function index()
    {
        return $this->render('my_sym_first/index.html.twig', [
            'controller_name' => 'MySymFirstController',
        ]);
    }

      /**
     * @Route("/",name="home")
     */
    public function home()
    {
        $articles= $this->getDoctrine()->getRepository(Article::class)->findAll();
        return $this->render('my_sym_first/home.html.twig' , array(
            'articles' => $articles
        ));
    }


 

    /**
     * @Route("/article/new",name="new_article")
     * Method({"GET","POST"})
     */
    public function new(Request $request)
    {
        $article = new Article();
        $form = $this->createFormBuilder($article)
        ->add('title',TextType::class,array(
            'attr'=> array('class'=>'form-control') ))
                    

        ->add('body',TextareaType::class,array(
            'required'=>false,
            'attr'=> array( 'class'=>'form-control') ))
            
        ->add('save',SubmitType::class,array(
            'label'=>'Create',
            'attr'=> array( 'class'=>'btn btn-primary mt-3') ))
                              
        ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
           $article=$form->getData();
            $entityManager= $this->getDoctrine()->getManager();
            $entityManager->persist($article);
            $entityManager->flush();

            return $this->redirectToRoute('home');
        }

        return $this->render('my_sym_first/addNew.html.twig' , array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/article/edit/{id}",name="edit_article")
     * Method({"GET","POST"})
     */
    public function edit(Request $request, $id)
    {
        $article = new Article();
        $article= $this -> getDoctrine()->getRepository(Article::class)->find($id);

        $form = $this->createFormBuilder($article)
        ->add('title',TextType::class,array(
            'attr'=> array('class'=>'form-control') ))
                    

        ->add('body',TextareaType::class,array(
            'required'=>false,
            'attr'=> array( 'class'=>'form-control') ))
            
        ->add('save',SubmitType::class,array(
            'label'=>'Update',
            'attr'=> array( 'class'=>'btn btn-primary mt-3') ))
                              
        ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $entityManager= $this->getDoctrine()->getManager();
            $entityManager->flush();

            return $this->redirectToRoute('home');
        }

        return $this->render('my_sym_first/edit.html.twig' , array(
            'form' => $form->createView()
        ));
    }


   /**
     * @Route("/article/{id}",name="show_article")
     */
    public  function show($id)
    {
       $article= $this -> getDoctrine()->getRepository(Article::class)->find($id);

       return $this->render('my_sym_first/show.html.twig', array(
        'article' => $article
    ));
    }
     
     /**
     * @Route("/articles/delete/{id}")
     * @Method({"DELETE"})
        */
    public function delete(Request $request, $id) {
        $article = $this->getDoctrine()->getRepository(Article::class)->find($id);
  
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($article);
        $entityManager->flush();
  
        $response = new Response();
        $response->send();
      }
     

}
