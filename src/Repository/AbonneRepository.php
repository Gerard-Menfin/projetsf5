<?php

namespace App\Repository;

use App\Entity\Abonne;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @method Abonne|null find($id, $lockMode = null, $lockVersion = null)
 * @method Abonne|null findOneBy(array $criteria, array $orderBy = null)
 * @method Abonne[]    findAll()
 * @method Abonne[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AbonneRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Abonne::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof Abonne) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newHashedPassword);
        $this->_em->persist($user);
        $this->_em->flush();
    }

    /**
     * @return Abonne[] Retourne les abonnés dont le nom ou le prénom ou le pseudo contiennent le mot passé en paramètre
     */
    public function recherche($mot)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.pseudo LIKE :mot')
            ->orWhere("a.nom LIKE :mot")
            ->orWhere("a.prenom LIKE :mot")
            ->setParameter('mot', '%' . $mot . '%')
            ->orderBy('a.pseudo', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    
    // /**
    //  * @return Abonne[] Returns an array of Abonne objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Abonne
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
