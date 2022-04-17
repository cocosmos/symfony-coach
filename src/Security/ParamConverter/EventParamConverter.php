<?php
namespace App\Security\ParamConverter;

use App\Entity\Event;
use App\Repository\EventRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;


use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;

class EventParamConverter implements ParamConverterInterface {
    private $eventRepository;

    public function __construct(EventRepository $eventRepository)
    {
        $this->eventRepository = $eventRepository;
    }


    public function apply(Request $request, ParamConverter $configuration){

        if ($request->get("adminLinkToken") == null){
            $adminLinkToken = $request->attributes->get("group")->getEvent()->getAdminLinkToken();
        } else{
            $adminLinkToken = $request->get("adminLinkToken");
        }

        $id = $this->eventRepository->findOneBy(['adminLinkToken'=> $adminLinkToken])->getId();


        $event = $this->eventRepository->findOneBy([
            'id' =>$id,
            'adminLinkToken'=> $adminLinkToken,
        ]);

        if ($event){
            $request->attributes->set($configuration->getName(), $event);
        }

    }

    public function supports(ParamConverter $configuration){
        if ($configuration->getClass()===Event::class and $configuration->getName()==="event"){
            return true;
        }
        return false;
    }


}