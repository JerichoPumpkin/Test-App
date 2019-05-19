<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Product::class);
    }

    // /**
    //  * @return Product[] Returns an array of Product objects
    //  */
    
    public function findByTagName($value = null)
    {
        $query = $this->createQueryBuilder('p');
        
        if($value){
            $query->join('p.tag', 't')
            ->andWhere('t.name LIKE :val')
            ->setParameter('val', $value);
        }

        return $query->orderBy('p.created', 'ASC')
            ->orderBy('p.name', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }
}
