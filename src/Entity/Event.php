<?php

namespace App\Entity;

use App\Repository\EventRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

#[ORM\Entity(repositoryClass: EventRepository::class)]
#[ORM\Table(name: '`event`')]

class Event
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 50)]
    private ?string $name;

    #[ORM\Column(type: 'string', length: 100)]
    private ?string $adminLinkToken;

    #[ORM\Column(type: 'string', length: 150)]
    private ?string $email;

    #[ORM\OneToMany(mappedBy: 'event', targetEntity: Group::class)]
    private $groups;

    public function __construct()
    {
        $this->groups = new ArrayCollection();
        $this->adminLinkToken = bin2hex(random_bytes(20));
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getAdminLinkToken(): ?string
    {
        return $this->adminLinkToken;
    }

    public function setAdminLinkToken(string $adminLinkToken): self
    {
        $this->adminLinkToken = $adminLinkToken;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return Collection<int, Group>
     */
    public function getGroups(): Collection
    {
        return $this->groups;
    }

    public function addGroup(Group $group): self
    {
        if (!$this->groups->contains($group)) {
            $this->groups[] = $group;
            $group->setEvent($this);
        }

        return $this;
    }

    public function removeGroup(Group $group): self
    {
        if ($this->groups->removeElement($group)) {
            // set the owning side to null (unless already changed)
            if ($group->getEvent() === $this) {
                $group->setEvent(null);
            }
        }

        return $this;
    }
}
