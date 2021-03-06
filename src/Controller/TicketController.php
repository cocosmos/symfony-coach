<?php

namespace App\Controller;

use App\Entity\Group;
use App\Entity\Ticket;
use App\Form\TicketType;
use App\Repository\TicketRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TicketController extends AbstractController
{
    /**
     * Page to edit a ticket
     */
    #[Route('/group/{linkToken}/ticket/{id}/edit', name: 'app_ticket_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, TicketRepository $ticketRepository, Group $group, Ticket $ticket): Response
    {

        $form = $this->createForm(TicketType::class, $ticket);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ticketRepository->add($ticket);
            return $this->redirectToRoute('app_group_show', ["linkToken" => $group->getLinkToken()]);
        }

        return $this->renderForm('ticket/edit.html.twig', [
            'group'=> $group,
            'ticket' => $ticket,
            'form' => $form,
        ]);
    }
    /**
     * Delete ticket
     */

    #[Route('/group/{linkToken}/ticket/{id}/delete', name: 'app_ticket_delete', methods: ['POST'])]
    public function delete(Request $request, Ticket $ticket, Group $group, TicketRepository $ticketRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$ticket->getId(), $request->request->get('_token'))) {
            $ticketRepository->remove($ticket);
        }

        return $this->redirectToRoute('app_group_show', ["linkToken" =>$group->getLinkToken()], Response::HTTP_SEE_OTHER);
    }
}
