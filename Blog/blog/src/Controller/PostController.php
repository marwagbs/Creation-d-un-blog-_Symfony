<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use DateTimeImmutable;
use App\Entity\Comment;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use App\Repository\CommentRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class PostController extends AbstractController
{
    #[Route('/post', name: 'app_post')]
    public function index(): Response
    {
        return $this->render('post/index.html.twig', [
            'controller_name' => 'PostController',
        ]);
    }

    #[IsGranted("ROLE_USER")]
    #[Route('/post/create', name: 'post_create')]
    public function create(Request $request, EntityManagerInterface $em , UserRepository $userRepo, sluggerInterface $slugger): Response
    {   
        $post=new Post();
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);
        if($form->isSubmitted()&& $form->isValid()){


            $imageFile = $form->get('image')->getData();

            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $imageFile->move(
                        $this->getParameter('file_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $slug=$slugger->slug($post->getTitle());
                $post->setimage($newFilename);
                $post->setCreatedAt(new DateTimeImmutable());
                $post->setUpdatedAt(new DateTimeImmutable());
                $post->setSlug($slug);
                // $user=$userRepo->find(1);
                 $post->setUser($this->getUser());
                //enregistrent de la catégorie dans la BDD
                $em->persist($post);   
                $em->flush();
               
            }
            return $this->redirectToRoute('home');
            //pour que le chapm created at soit rempli totut seul
            
        }

        return $this->renderForm('post/create.html.twig', [
            'postForm' => $form,
        ]);
    }
    #[IsGranted("ROLE_USER")]
    #[Route('/post/edit/{id}', name: 'post_edit')]
    public function edit(Request $request, EntityManagerInterface $em , sluggerInterface $slugger, Post $post, CategoryRepository $categroyRepo): Response
    {   $categories=$categroyRepo->findAll();
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);
        $imageFile = $form->get('image')->getData();
        if($form->isSubmitted()&& $form->isValid()){

            if ($imageFile){
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();
                try {
                    $imageFile->move(
                        $this->getParameter('file_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }
            }
                $slug=$slugger->slug($post->getTitle());
                $post->setimage($newFilename);
                $post->setCreatedAt(new DateTimeImmutable());
                $post->setUpdatedAt(new DateTimeImmutable());
                $post->setSlug($slug);
                $em->flush();
                return $this->redirectToRoute('home');
                
        }

        return $this->renderForm('post/create.html.twig', [
            'postForm' => $form,
            'post'=>$post,
            'categories'=>$categories
        ]);
    }
    //afficher la totalité de l'article par son id
    #[Route('/post/show/{id}', name:"post_show")]
    public function findArtcileById(Post $post, CommentRepository $commentRepo, Request $request){
        //au lieu de mettre int $id, on peut recuperer le post et c'est lui qui va faire  convertir l'id recuperer dans la route
        // echo ($id);
        // $post= $PostRepo->find($id);
        //dd($post);
        // $comment=new Comment();
        // $form = $this->createForm(CommentType::class, $comment);
        // $form->handleRequest($request);
        return $this->render('post/index.html.twig', [
            'post' => $post,
            // 'formComments'=>$comment
        ]);
        
    }
    //fonction supprimr un article
    #[Route('/post/remove/{id}', name:"post_remove")]
    public function remove(Post $post,EntityManagerInterface $em ){
        //au lieu de mettre int $id, on peut recuperer le post et c'est lui qui va faire  convertir l'id recuperer dans la route
        // echo ($id);
        // $post= $PostRepo->find($id);
        //dd($post);
        $em->remove($post);
        $em->flush();
       
        
        return $this->redirectToRoute('home');
    }

    //système de recherche
    #[Route('/search', name:"search")]
    public function search(Request $request, PostRepository $postRepo): Response
    {
      $search= $request->query->get("q");
     $post=$postRepo->search($search);
        
     return $this->renderForm('home/index.html.twig', [
        'posts' => $post
    ]);

    }
    //afficher la totalité d'article avec le slug
    #[Route('/post/{slug}', name:"post_slug")]
    public function slug( Post $post): Response
    {
        
     return $this->renderForm('post/index.html.twig', [
        'post' => $post,
        
    ]);

    }

    //fonction qui permet d'afficher qlq element on choisir sur search
    #[Route('/apiSearch', name:"apiSearch")]
    public function searchAPi(Request $request, PostRepository $postRepo): Response
    {
        $search= $request->query->get("q");
        $post=$postRepo->apiSearch($search);
        return $this->json($post);

    }
   

 
}
