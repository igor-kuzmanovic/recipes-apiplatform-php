<?php

namespace App\Repository;

use App\Entity\ImageObject;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ImageObject|null find($id, $lockMode = null, $lockVersion = null)
 * @method ImageObject|null findOneBy(array $criteria, array $orderBy = null)
 * @method ImageObject[]    findAll()
 * @method ImageObject[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ImageObjectRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ImageObject::class);
    }

    // /**
    //  * @return ImageObject[] Returns an array of ImageObject objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ImageObject
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
