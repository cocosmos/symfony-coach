<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\Group;
use App\Entity\Ticket;
use App\Form\GroupType;
use App\Form\TicketType;
use App\Repository\GroupRepository;
use App\Repository\TicketRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/group')]
class GroupController extends AbstractController
{
    #[Route('/', name: 'app_group_index', methods: ['GET'])]
    public function index(GroupRepository $groupRepository): Response
    {
       // $group = new Group(/*$event*/);

        return $this->render('group/index.html.twig', [
            'groups' => $groupRepository->findAll(),
           // 'group' =>$group,
        ]);
    }

//    #[Route('/new', name: 'app_group_new', methods: ['GET', 'POST'])]
//    public function new(Request $request, GroupRepository $groupRepository, Event $event): Response
//    {
//        $group = new Group(/*$event*/);
//        $form = $this->createForm(GroupType::class, $group);
//        $form->handleRequest($request);
//
//        if ($form->isSubmitted() && $form->isValid()) {
//            $groupRepository->add($group);
//            return $this->redirectToRoute('app_group_index', [], Response::HTTP_SEE_OTHER);
//        }
//
//        return $this->renderForm('group/new.html.twig', [
//            'group' => $group,
//            'form' => $form,
//        ]);
//    }
//Create a new ticket
    #[Route('/{linkToken}', name: 'app_group_show', methods: ['GET', 'POST'])]
    public function show( Group $group, Request $request, TicketRepository $ticketRepository): Response
    {

        $ticket = new Ticket($group);
        $form = $this->createForm(TicketType::class, $ticket);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ticketRepository->add($ticket);
           // return $this->redirectToRoute('app_ticket_index', [], Response::HTTP_SEE_OTHER);
        }
        $tickets = $ticketRepository ->findByGroup($group, array('createdAt' => 'DESC'));

        return $this->renderForm('group/show.html.twig', [
            'group' => $group,
            'ticket' => $ticket,
            'form' => $form,
            'tickets'=>$tickets,
        ]);
    }

//    #[Route('/{linkToken}/edit', name: 'app_group_edit', methods: ['GET', 'POST'])]
//    public function edit(Request $request, Group $group, GroupRepository $groupRepository): Response
//    {
//        $form = $this->createForm(GroupType::class, $group);
//        $form->handleRequest($request);
//
//        if ($form->isSubmitted() && $form->isValid()) {
//            $groupRepository->add($group);
//            return $this->redirectToRoute('app_group_index', [], Response::HTTP_SEE_OTHER);
//        }
//
//        return $this->renderForm('group/edit.html.twig', [
//            'group' => $group,
//            'form' => $form,
//        ]);
//    }
//
    #[Route('/{linkToken}', name: 'app_group_delete', methods: ['POST'])]
    public function delete(Request $request, Group $group, GroupRepository $groupRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$group->getId(), $request->request->get('_token'))) {
            $groupRepository->remove($group);
        }

        return $this->redirectToRoute('app_group_index', [], Response::HTTP_SEE_OTHER);
    }
}
