<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\Group;
use App\Entity\Ticket;
use App\Form\TicketType;
use App\Repository\GroupRepository;
use App\Repository\TicketRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/group')]
class GroupController extends AbstractController
{

    /**
     * Page to create and show ticket
     */
    #[Route('/{linkToken}', name: 'app_group_show', methods: ['GET', 'POST'])]
    public function show( Group $group, Request $request, TicketRepository $ticketRepository, Event $event): Response
    {
        $ticket = new Ticket($group);
        $form = $this->createForm(TicketType::class, $ticket);
        $form->handleRequest($request);

        if($ticketRepository->findByGroup($group)){
            $LastTicket= $ticketRepository ->findByGroup($group, array('createdAt' => 'ASC'), 1,0)[0]->getCreatedAt();
        }else{
            $LastTicket = new \DateTime();
        }
        $waiting = $ticketRepository->countGroupBefore($event, $group, $LastTicket);


        if ($form->isSubmitted() && $form->isValid()) {
            $ticketRepository->add($ticket);
            return $this->redirectToRoute("app_group_show", ["linkToken"=>$group->getLinkToken()]);
        }
        $tickets = $ticketRepository ->findByGroup($group, array('createdAt' => 'ASC'));

        return $this->renderForm('group/show.html.twig', [
            'group' => $group,
            'ticket' => $ticket,
            'form' => $form,
            'tickets'=>$tickets,
            'waiting'=>$waiting,
        ]);
    }


    /**
     * Delete a group
     */
    #[Route('/{linkToken}/{id}/delete', name: 'app_group_delete', methods: ['POST'])]
    public function delete(Request $request, Group $group, GroupRepository $groupRepository, Event $event, TicketRepository $ticketRepository): Response
    {

        if ($this->isCsrfTokenValid('delete'.$group->getId(), $request->request->get('_token'))) {
            //check if group as ticket
            if($ticketRepository->findByGroup($group)){
                throw new AccessDeniedHttpException();
            }else {
                $groupRepository->remove($group);
            }
        }
        return $this->redirectToRoute('app_event_show', ["adminLinkToken" => $event->getAdminLinkToken()], Response::HTTP_SEE_OTHER);
    }


}
