<?php

namespace App\Entity;

use App\Repository\YGOCardRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: YGOCardRepository::class)]
class YGOCard
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\ManyToOne(inversedBy: 'ygo_cards')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Pack $pack = null;

    /**
     * @var Collection<int, Showcase>
     */
    #[ORM\ManyToMany(targetEntity: Showcase::class, mappedBy: 'ygoCards')]
    private Collection $showcases;

    public function __construct()
    {
        $this->showcases = new ArrayCollection();
    }
    
    
    /**
     * @return string
     */
    public function __toString()
    {
        $s = '';
        $s .= 'ID : '. $this->getId() .' | Name : '. $this->getName() .' | Pack : '. $this->getPack()->getTitle();
        return $s;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getPack(): ?Pack
    {
        return $this->pack;
    }

    public function setPack(?Pack $pack): static
    {
        $this->pack = $pack;

        return $this;
    }

    /**
     * @return Collection<int, Showcase>
     */
    public function getShowcases(): Collection
    {
        return $this->showcases;
    }

    public function addShowcase(Showcase $showcase): static
    {
        if (!$this->showcases->contains($showcase)) {
            $this->showcases->add($showcase);
            $showcase->addYgoCard($this);
        }

        return $this;
    }

    public function removeShowcase(Showcase $showcase): static
    {
        if ($this->showcases->removeElement($showcase)) {
            $showcase->removeYgoCard($this);
        }

        return $this;
    }
}
