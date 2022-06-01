<?php

namespace App\Controller;

use App\Entity\Post;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class PostController extends AbstractController
{

    public function index(Request $request): Response
    {
        return $this->render('post/index.html.twig', [
            'controller_name' => 'PostController',
            'mon_nom' => "Patrick",
        ]);
    }


    public function create(Request $request, ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        // les parametres de routes
        $name = $request->attributes->get("name");

        $post = new Post();
        $post->setName($name);

        //Methodes pour persiter l'objet en base de donnees
        $entityManager->persist($post);
        $entityManager->flush();

        return $this->render('post/create.html.twig', [
            'post_name' => $post->getName(),
            'post_id' => $post->getId(),
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
