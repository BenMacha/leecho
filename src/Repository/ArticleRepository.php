<?php

namespace App\Repository;

use App\Entity\Article;
use App\Entity\Invoice\Invoice;
use App\Entity\Invoice\SepaOption;
use App\Entity\Invoice\SubscriptionInvoice;
use App\Entity\Platform;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Article>
 *
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Article::class);
    }

//    /**
//     * @return Article[] Returns an array of Article objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Article
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
    public function load(array $request)
    {
        $qb = $this->createQueryBuilder('article')->select(['article', 'tags', 'author',]);
        $filteredTotal = $this->createQueryBuilder('article')->select('count(article.id) as total');
        $total = $this->createQueryBuilder('article')->select('count(article.id) as total');

        $this->leftJoin($qb);
        $this->leftJoin($filteredTotal);

        if (isset($request['offset']) && null != $request['offset'])
            $qb->setFirstResult((int) $request['offset']);


        if (isset($request['limit']) && null != $request['limit']) {
            $qb->setMaxResults((int) $request['limit']);
        }

        if (isset($request['order'], $request['sort'])) {
            $qb->addOrderBy($request['sort'], $request['order']);
        }

        if (isset($request['search']) && '' != $request['search']) {
            $this->setSearch($qb, $request['search']);
            $this->setSearch($filteredTotal, $request['search']);
        }

        $qb->groupBy('article.id');

        return [
            'request' => $request,
            'totalNotFiltered' => $total->getQuery()->getSingleScalarResult(),
            'total' => $filteredTotal->getQuery()->getSingleScalarResult(),
            'rows' => $qb->getQuery()->getArrayResult(),
        ];
    }

    public function setSearch(QueryBuilder $qb, $search): void
    {
        $qb
            ->andWhere(
                $qb->expr()->orX(
                    $qb->expr()->like('article.title', ':search'),
                    $qb->expr()->like('article.slug', ':search'),
                    $qb->expr()->like('article.content', ':search'),
                    $qb->expr()->like('tags.name', ':search'),
                    $qb->expr()->like('author.firstName', ':search'),
                    $qb->expr()->like('author.lastName', ':search'),
                )
            )
            ->setParameter('search', '%' . $search . '%');
    }

    public function leftJoin(QueryBuilder $qb): void {
        $qb->leftJoin('article.tags', 'tags');
        $qb->leftJoin('article.author', 'author');
    }
}
