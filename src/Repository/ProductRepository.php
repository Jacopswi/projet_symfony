<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Category;
use Doctrine\ORM\Query;


/**
 * @extends ServiceEntityRepository<Product>
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    // App\Controller\ArticleController.php

    public function listAction(EntityManagerInterface $em, PaginatorInterface $paginator, Request $request)
    {
        $dql   = "SELECT a FROM AcmeMainBundle:Article a";
        $query = $em->createQuery($dql);

        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1), /* page number */
            10 /* limit per page */
        );

        // parameters to template
        return $this->render('article/list.html.twig', ['pagination' => $pagination]);
    }

    public function searchProducts(array $criteria): array
{   
    $qb = $this->createQueryBuilder('p');

    if (!empty($criteria['name'])) {
        $qb->andWhere('p.name LIKE :name')
           ->setParameter('name', '%' . $criteria['name'] . '%');
    }

    return $qb->getQuery()->getResult();
}

public function search(string $criteria): array
{   
    $qb = $this->createQueryBuilder('p');

        $qb->andWhere('p.name LIKE :name')
           ->setParameter('name', '%' . $criteria . '%');

    return $qb->getQuery()->getResult();
}

public function findByCategory(Category $category)
{
    return $this->createQueryBuilder('p')
        ->join('p.Category', 'c')
        ->where('c = :category')
        ->setParameter('category', $category)
        ->getQuery()
        ->getResult();
}

public function findAllQuery(): Query
{
    return $this->createQueryBuilder('p')
        ->getQuery();
}



//    /**
//     * @return Product[] Returns an array of Product objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Product
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
