<?php

namespace App\Controller;

use App\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;

class CategoryController extends AbstractController
{
    #[Route('/category/create', name: 'category_create')]
    public function create(Request $request, EntityManagerInterface $em): Response
    {   
        $category=new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);
        if($form->isSubmitted()&& $form->isValid()){
            //pour que le chapm created at soit rempli totut seul
            $category->setCreatedAt(new DateTimeImmutable());
            $category->setUpdatedAt(new DateTimeImmutable());
            //enregistrent de la catégorie dans la BDD
            $em->persist($category);   
            $em->flush();
        }





        return $this->renderForm('category/create.html.twig', [
            'categoryForm' => $form,
        ]);
    }


    #[Route('/category', name: 'category')]
    public function index(CategoryRepository $categoryRepo): Response

    {       
            //$categoryRepository=$doctrine->getRepository(Category::class) au lieu de faire ca on ajoute dans l'argument

            $categories= $categoryRepo->findAll();

            return $this->render('category/index.html.twig', [
                'categories' => $categories,
            ]);

           
        
    }
}
