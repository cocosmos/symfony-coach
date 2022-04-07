<?php

namespace App\Entity;

use App\Repository\GroupRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GroupRepository::class)]
#[ORM\Table(name: '`group`')]
class Group
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 50)]
    private $name;

    #[ORM\Column(type: 'string', length: 255)]
    private $linkToken;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private $lastArchived;

    #[ORM\ManyToOne(targetEntity: Event::class, inversedBy: 'groups')]
    #[ORM\JoinColumn(nullable: false)]
    private $event;

    #[ORM\OneToMany(mappedBy: 'group', targetEntity: Ticket::class)]
    private $tickets;

    public function __construct(Event $event)
    {
        $this->setEvent($event);
        $this->linkToken = bin2hex(random_bytes(20));

        // ->add('adminLinkToken', HiddenType::class, ['data' => bin2hex(random_bytes(20)),
        $this->tickets = new ArrayCollection();

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

    public function getLinkToken(): ?string
    {
        return $this->linkToken;
    }

    public function setLinkToken(string $linkToken): self
    {
        $this->linkToken = $linkToken;

        return $this;
    }

    public function getLastArchived(): ?\DateTimeInterface
    {
        return $this->lastArchived;
    }

    public function setLastArchived(?\DateTimeInterface $lastArchived): self
    {
        $this->lastArchived = $lastArchived;

        return $this;
    }

    public function getEvent(): ?Event
    {
        return $this->event;
    }

    public function setEvent(?Event $event): self
    {
        $this->event = $event;

        return $this;
    }

    /**
     * @return Collection<int, Ticket>
     */
    public function getTickets(): Collection
    {
        return $this->tickets;
    }

    public function addTicket(Ticket $ticket): self
    {
        if (!$this->tickets->contains($ticket)) {
            $this->tickets[] = $ticket;
            $ticket->setGroup($this);
        }

        return $this;
    }

    public function removeTicket(Ticket $ticket): self
    {
        if ($this->tickets->removeElement($ticket)) {
            // set the owning side to null (unless already changed)
            if ($ticket->getGroup() === $this) {
                $ticket->setGroup(null);
            }
        }

        return $this;
    }
}
