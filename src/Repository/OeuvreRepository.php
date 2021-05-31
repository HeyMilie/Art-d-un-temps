<?php

namespace App\Repository;

use App\Entity\Membre;
use App\Entity\Oeuvre;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Oeuvre|null find($id, $lockMode = null, $lockVersion = null)
 * @method Oeuvre|null findOneBy(array $criteria, array $orderBy = null)
 * @method Oeuvre[]    findAll()
 * @method Oeuvre[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OeuvreRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Oeuvre::class);
    }

    /**
     * @return Oeuvre[] Returns an array of Oeuvre objects
     */

    // public function showPeinture(){
    //     /*
    //         SELECT o.*
    //         FROM oeuvre o
    //         WHERE o.categorie = peinture

    //     */
    //     return $this->createQueryBuilder("oeuvre")
    //         ->where("oeuvre.categorie = 'peinture'")
    //         ->setMaxResults(6)
    //         ->getQuery()
    //         ->getResult()
    //     ;
    // }

    public function home(){
        /*
            SELECT *
            FROM oeuvre
            ORDER BY id

        */
        return $this->createQueryBuilder("oeuvre")
            ->orderBy("oeuvre.id")
            ->setMaxResults(6)
            ->getQuery()
            ->getResult()
        ;
    }


    public function oeuvresByMembre(){
        /*
            SELECT o.*
            FROM oeuvres o JOIN membre m ON o.membre = m.pseudo

        */
        return $this->createQueryBuilder("o")
            ->join(Membre::class, "m", "WITH", "o.membre = m.pseudo")
            //->where("e.date_retour IS null")
            ->orderBy("o.id")
            //->addOrderBy("l.titre")
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
    

    
}
