<?php

namespace App\Controller;


use App\Repository\PostRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(PostRepository $postRepo): Response

    {       
            //$categoryRepository=$doctrine->getRepository(Category::class) au lieu de faire ca on ajoute dans l'argument

            $posts= $postRepo->findBy([], ['createdAt' => 'DESC'], 5);
            
            return $this->render('home/index.html.twig', [
                'posts' => $posts,
            ]);

           
        
    }

    #[Route('/api/{offset}', name:"api")]
    public function api( PostRepository $postRepo, int $offset): Response
    {        //return new Response("toto");
       $posts= $postRepo->findBy([], ['createdAt' => 'DESC'], 5, $offset);

        return $this->render('home/posts.html.twig', [
            'posts' => $posts,
        ]);
    
    }

 


 
}
