<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\Group;
use App\Entity\Ticket;
use App\Form\EventType;
use App\Form\GroupType;
use App\Form\TicketByEventType;
use App\Repository\EventRepository;
use App\Repository\GroupRepository;
use App\Repository\TicketRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EventController extends AbstractController
{
    /**
     * Page to display existing event
     */
    #[Route('/admin', name: 'app_event_index')]
    public function index(EventRepository $eventRepository): Response
    {
        return $this->render('event/index.html.twig', [
            'events' => $eventRepository->findAll(),
        ]);
    }
    /**
     * Page to add event
     */
    #[Route('/', name: 'app_event_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EventRepository $eventRepository): Response
    {
        $event = new Event();
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $eventRepository->add($event);

            return $this->redirectToRoute('app_event_show', [
            'adminLinkToken'=> $event->getAdminLinkToken()
            ], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('event/new.html.twig', [
            'event' => $event,
            'form' => $form,
        ]);
    }

    /**
     * Page to show groups per event
     * @throws OptimisticLockException
     * @throws ORMException
     * @throws NonUniqueResultException
     * @throws NoResultException
     *
     */

    #[Route('event/{adminLinkToken}/groups', name: 'app_event_show', methods: ['GET', 'POST'])]
    public function show(Event $event, GroupRepository $groupRepository, Request $request, TicketRepository $ticketRepository ): Response
    {
        $groups= $groupRepository -> findByEvent($event);

        $tickets = $ticketRepository ->findbyEventCount($event);

        $group = new Group($event);
        $form = $this->createForm(GroupType::class, $group);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $groupRepository->add($group);
            return $this->redirectToRoute('app_event_show', ['adminLinkToken'=> $event->getAdminLinkToken()], Response::HTTP_SEE_OTHER);
        }
        return $this->renderForm('event/show.html.twig', [
            "event" => $event,
            "groups" => $groups,
            'group' => $group,
            'tickets'=> $tickets,
            'form' => $form,
        ]);
    }
    /**
     * Page to show tickets per event
     */
    #[Route('event/{adminLinkToken}/tickets', name: 'app_event_list', methods: ['GET'])]
    public function list(Event $event, TicketRepository $ticketRepository)
    {
        $tickets = $ticketRepository->ThereIsEvent($event);

        return $this->renderForm('event/list.html.twig', [
            "event" => $event,
            'tickets' => $tickets,

        ]);
    }
    /**
     * Page to edit status of ticket per event
     */

    #[Route('event/{adminLinkToken}/tickets/{id}', name: 'app_event_ticket_edit', methods: ['GET', 'POST'])]
    public function editTicket(Event $event, Ticket $ticket, Request $request, TicketRepository $ticketRepository): Response
    {
        $form = $this->createForm(TicketByEventType::class, $ticket);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            //if the status changed to archived the group set the lastArchivedAt at now
            $getStatus =$request->attributes->get("ticket")->getStatus()->getIsArchived();

            if($getStatus == true){
                $ticket->getGroup()->setLastArchived(new \DateTime());
            }
            $ticketRepository->add($ticket);


            return $this->redirectToRoute('app_event_list', ["adminLinkToken" => $event->getAdminLinkToken()]);
        }

        return $this->renderForm('event/edit.html.twig', [
            'event' => $event,
            'ticket' => $ticket,
            'form' => $form,
        ]);


    }

}
