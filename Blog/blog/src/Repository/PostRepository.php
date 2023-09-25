<?php

namespace App\Repository;

use App\Entity\Post;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Post>
 *
 * @method Post|null find($id, $lockMode = null, $lockVersion = null)
 * @method Post|null findOneBy(array $criteria, array $orderBy = null)
 * @method Post[]    findAll()
 * @method Post[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Post::class);
    }

    public function save(Post $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Post $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

   /**
    * @return Post[] Returns an array of Post objects
    */
   public function search($search): array
   {
       return $this->createQueryBuilder('p')
           ->join('p.category', 'c')
           ->where('p.title LIKE :serach')
           ->orwhere('p.content LIKE :serach')
           ->orwhere('c.name LIKE :serach')
           ->setParameter('serach', '%'.$search.'%')
           ->getQuery()
           ->getResult()
       ;
   }

   public function apiSearch($search): array
   {
       return $this->createQueryBuilder('p')
           ->select("p.title")
           ->where('p.title LIKE :serach')
           ->setParameter('serach', '%'.$search.'%')
           ->getQuery()
           ->setMaxResults(10)
           ->getResult()
       ;
   }

//    public function findOneBySomeField($value): ?Post
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
