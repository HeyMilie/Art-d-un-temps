<?php

namespace App\Repository;

use App\Entity\Oeuvres;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Oeuvres>
 *
 * @method Oeuvres|null find($id, $lockMode = null, $lockVersion = null)
 * @method Oeuvres|null findOneBy(array $criteria, array $orderBy = null)
 * @method Oeuvres[]    findAll()
 * @method Oeuvres[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OeuvresRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Oeuvres::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Oeuvres $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Oeuvres $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

     /**
    * @return Oeuvres[] Returns an array of Users objects
    */
    
    public function findByTitre(string $titre)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.titre LIKE :titre')
            ->setParameter('titre', $titre)
            ->orderBy('o.titre', 'ASC')
            //->getQuery()
            //->getResult()
        ;
    }

    /**
     * @return Oeuvres[] Returns an array of Oeuvres objects
     */
    public function home(){
        /*
            SELECT *
            FROM oeuvres
            ORDER BY id

        */
        return $this->createQueryBuilder("o")
            ->orderBy("o.id")
            ->setMaxResults(6)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findByCategorie($categorie)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.categorie = :categorie')
            ->setParameter('categorie', $categorie)
            ->orderBy('o.id', 'ASC')
            ->setMaxResults(6)
            ->getQuery()
            ->getResult()
        ;
    }

    // /**
    //  * @return Oeuvres[] Returns an array of Oeuvres objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('o.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Oeuvres
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
