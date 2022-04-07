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

    public function supports(ParamConverter $configuration){
        if ($configuration->getClass()===Group::class and $configuration->getName()==="group"){
            return true;
        }
        return false;
    }
    public function apply(Request $request, ParamConverter $configuration){
        $id = $request->get("id");
        $linkToken = $request->get("linkToken");


        $group = $this->groupRepository->findOneBy([
            'id' =>$id,
            'linkToken'=> $linkToken,
        ]);

        if ($group){
            $request->attributes->set($configuration->getName(), $group);
        }

        //dd( $linkToken);
    }


}