<?php

namespace App\Repository;

use App\Entity\Event;
use App\Entity\Group;
use App\Entity\Status;
use App\Entity\Ticket;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Ticket|null find($id, $lockMode = null, $lockVersion = null)
 * @method Ticket|null findOneBy(array $criteria, array $orderBy = null)
 * @method Ticket[]    findAll()
 * @method Ticket[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TicketRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ticket::class);

    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Ticket $entity, bool $flush = true): void
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
    public function remove(Ticket $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    private function getOpenTicketQueryBuilder($alias){
        return $this ->createQueryBuilder('ticket')
            ->orderBy("ticket,createdAt", "ASC");
    }

    public function ThereIsEvent(Event $event){
        return $this->createQueryBuilder('t')

            ->innerJoin(Group::class, "g", Join::WITH, "t.group = g.id")
            ->leftJoin(Status::class, "s", Join::WITH, "t.status = s.id")
            ->where("g.event = :event")
            ->andWhere("t.status is NULL")
            ->andWhere("g.event = :event")
            ->orWhere("s.isArchived = false")
            ->andWhere("g.event = :event")
            ->setParameter('event', $event)
            ->orderBy('t.createdAt', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Doctrine\ORM\NoResultException
     */
    public function findbyEventCount(Event $event){
        return $this->createQueryBuilder('t')
            ->select('count(t.id)')
            ->innerJoin(Group::class, "g", Join::WITH, "t.group = g.id")
            ->leftJoin(Status::class, "s", Join::WITH, "t.status = s.id")
            ->where("g.event = :event")
            ->andWhere("t.status is NULL")
            ->andWhere("g.event = :event")
            ->orWhere("s.isArchived = false")
            ->andWhere("g.event = :event")
            ->setParameter('event', $event)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function countGroupBefore(Event $event, Group $group, $LastTicket){
        return $this->createQueryBuilder('t')
            ->select('COUNT(DISTINCT g.id)')
            ->innerJoin(Group::class, "g", Join::WITH, "t.group = g.id")
            ->innerJoin(Event::class, "e", Join::WITH, "g.event = e.id")
            ->leftJoin(Status::class, "s", Join::WITH, "t.status = s.id")
            ->where("g.event = :event")
            ->andWhere("t.createdAt < :date")
            ->andWhere("g != :group")
            ->andWhere("s.isArchived = false")
            ->orWhere("t.status is NULL")
            ->andWhere("g != :group")
            ->andWhere("g.event = :event")
            ->andWhere("t.createdAt < :date")
            ->setParameter('event', $event)
            ->setParameter('group', $group)
            ->setParameter('date', $LastTicket)
            ->getQuery()
           ->getSingleScalarResult();
    }



}
