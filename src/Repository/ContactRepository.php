<?php

namespace App\Repository;

use App\Entity\Contact;
use App\Data\SearchData;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Contact|null find($id, $lockMode = null, $lockVersion = null)
 * @method Contact|null findOneBy(array $criteria, array $orderBy = null)
 * @method Contact[]    findAll()
 * @method Contact[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ContactRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Contact::class);
    }

    /**
    * Récupère tous les contacts en fonction de la recherche
    * @return Contact[]
    */

    // Je passe en paramètre les données liées à ma recherche
     public function findSearch(SearchData $search): array
     {
         $query = $this
            ->createQueryBuilder('c')
            // Je selectionne toutes les infos liées aux contacts
            ->select('c');

        // Si le champs "Rechercher un contact" est complété
        // J'effectue une recherche dans ma base de donnée en fonction du prénom et/ou du nom
        if(!empty($search->q)) {
            $query= $query
                ->andWhere('c.firstname LIKE :q OR c.lastname LIKE :q')
                ->setParameter('q', "%{$search->q}%");
        }

        // Si une ou plusieurs catégories ont été cochées
        // J'effectue une recherche dans ma base de donnée en fonction des catégories associées
        if(!empty($search->categories)) {
            $query= $query
                ->andWhere('c.category IN (:categories)')
                ->setParameter('categories', $search->categories);
        }
        
         return $query->getQuery()->getResult();
    }

}