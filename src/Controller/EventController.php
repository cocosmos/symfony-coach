<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\Group;
use App\Form\EventType;
use App\Form\GroupType;
use App\Repository\EventRepository;
use App\Repository\GroupRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/event')]
class EventController extends AbstractController
{
    #[Route('/', name: 'app_event_index')]
    public function index(EventRepository $eventRepository): Response
    {
        return $this->render('event/index.html.twig', [
            'events' => $eventRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_event_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EventRepository $eventRepository): Response
    {
        $event = new Event();
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $eventRepository->add($event);
            return $this->redirectToRoute('app_event_show', [
            'adminLinkToken'=> $eventRepository->find($event)
            ], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('event/new.html.twig', [
            'event' => $event,
            'form' => $form,
        ]);
    }


    #[Route('/{adminLinkToken}/groups', name: 'app_event_show', methods: ['GET'])]
    public function show(Event $event, GroupRepository $groupRepository ): Response
    {
       $groups= $groupRepository -> findByEvent($event, array('lastArchived' => 'ASC'));

        return $this->render('event/show.html.twig', [
            'event' => $event,
            "groups" => $groups,
        ]);
    }
}
