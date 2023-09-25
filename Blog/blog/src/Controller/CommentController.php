<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\User;
use DateTimeImmutable;
use App\Entity\Comment;
use App\Form\CommentType;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CommentController extends AbstractController
{
    #[Route('/comment', name: 'comment')]
    public function index(): Response
    {
        return $this->render('comment/index.html.twig', [
            'controller_name' => 'CommentController',
        ]);
    }

    #[IsGranted("ROLE_USER")]
    #[Route('/comment/create/{id}', name: 'comment_create')]
    public function create(Request $request, EntityManagerInterface $em ,Post $post): Response
    {   
        $comment=new Comment();
      
        $comment->setPost($post);
        $comment->setUser($this->getUser());
        $form = $this->createForm(CommentType::class,$comment);
        $form->handleRequest($request);
        if($form->isSubmitted()&& $form->isValid()){
                
                $comment->setCreatedAt(new DateTimeImmutable());
                $comment->setUpdatedAt(new DateTimeImmutable());
                //enregistrent de la catégorie dans la BDD
                $em->persist($comment);   
                $em->flush();
            return $this->redirectToRoute('home');
            //pour que le chapm created at soit rempli totut seul
            
        }

        return $this->renderForm('comment/index.html.twig', [
            'CommentForm' => $form,
        ]);
    }
}
