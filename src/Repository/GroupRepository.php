<?php

namespace App\Repository;

use App\Entity\Event;
use App\Entity\Group;
use App\Entity\Status;
use App\Entity\Ticket;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Group|null find($id, $lockMode = null, $lockVersion = null)
 * @method Group|null findOneBy(array $criteria, array $orderBy = null)
 * @method Group[]    findAll()
 * @method Group[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GroupRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Group::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Group $entity, bool $flush = true): void
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
    public function remove(Group $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }




//    public function countGroupBefore(Group $group, Event $event, $LastTicket){
//        return $this->createQueryBuilder('g')
//            //->select('COUNT(DISTINCT g.id)')
//            ->innerJoin(Event::class, "e", Join::WITH, "g.event = e.id")
//            ->join(Ticket::class, "t")
//            //->leftJoin(Ticket::class, "t", Join::WITH, "s.status = s.id")
//            ->leftJoin(Status::class, "s", Join::WITH, "t.status = s.id")
//            ->where("g.event = :event")
//            ->andWhere("g != :group")
//            //->orderBy('t.createdAt', 'ASC')
//            ->andWhere("t.createdAt > :date")
//            ->andWhere("g != :group")
//            ->andWhere("g.event = :event")
//            ->orWhere("s.isArchived = false")
//            ->andWhere("g != :group")
//            ->andWhere("g.event = :event")
//            ->setParameter('group', $group)
//            ->setParameter('event', $event)
//            ->setParameter('date', $LastTicket)
//
//            ->getQuery()
//            ->getResult();
//           // ->getSingleScalarResult();
//    }

    // /**
    //  * @return Group[] Returns an array of Group objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('g.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Group
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */


}
