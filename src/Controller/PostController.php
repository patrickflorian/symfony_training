<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class PostController extends AbstractController
{

    public function index(Request $request,  ManagerRegistry $doctrine): Response
    {
        //Entity manager 
        $entityManager = $doctrine->getManager();

        //Create post object for form building and request handling
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);
        // handle form request data
        $form->handleRequest($request);

        //Verify form validity
        if($form->isSubmitted() && $form->isValid()){

            $post = $form->getData();

            //Methodes pour persiter l'objet en base de donnees
            $entityManager->persist($post);
            $entityManager->flush();

            return $this->render('post/detail.html.twig',[
                'post_name' => $post->getName(),
                'post_description' => $post->getDescription(),
            ]);
        }

        return $this->renderForm('post/index.html.twig',[
            'post_form' => $form,
        ]);
    }


    public function create(Request $request, ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        // les parametres de routes
        $name = $request->attributes->get("name");
        $description = $request->attributes->get("description");

        $post = new Post();
        $post->setName($name);
        $post->setDescription($description);

        //Methodes pour persiter l'objet en base de donnees
        $entityManager->persist($post);
        $entityManager->flush();

        return $this->render('post/create.html.twig', [
            'post_name' => $post->getName(),
            'post_id' => $post->getId(),
            'post_desc' => $post->getDescription(),
        ]);
    }

    public function detail(Request $request): Response
    {
        // les parametres de routes
        $name = $request->attributes->get("name");
        //Les parametres d'url
        $version = $request->query->get("version");
        // Les parametres du corps de la requete
        //$version_post = $request->request->get("version");

        return $this->json([
            'name' => $name,
            //'version'=> $version,
            'version_post'=> $version_post,
        ]);
    }
}
