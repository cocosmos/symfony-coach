<?php
namespace App\Security\ParamConverter;

use App\Entity\Group;
use App\Repository\GroupRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;


use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;

class GroupParamConverter implements ParamConverterInterface {
    private $groupRepository;

    public function __construct(GroupRepository $groupRepository)
    {
        $this->groupRepository = $groupRepository;
    }


    public function apply(Request $request, ParamConverter $configuration){
        $linkToken = $request->get("linkToken");
        $id = $this->groupRepository->findOneBy(['linkToken'=> $linkToken])->getId();


        $group = $this->groupRepository->findOneBy([
            'id' =>$id,
            'linkToken'=> $linkToken,
        ]);


        if ($group){
            $request->attributes->set($configuration->getName(), $group);
        }

    }

    public function supports(ParamConverter $configuration){
        if ($configuration->getClass()===Group::class and $configuration->getName()==="group"){
            return true;
        }
        return false;
    }


}