<?php

namespace App\Entity;

use App\Repository\PackRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\YGOCard;

#[ORM\Entity(repositoryClass: PackRepository::class)]
class Pack
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * @var Collection<int, YGOCard>
     */
    #[ORM\OneToMany(targetEntity: YGOCard::class, mappedBy: 'pack', orphanRemoval: true)]
    private Collection $ygo_cards;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\OneToOne(mappedBy: 'pack', cascade: ['persist', 'remove'])]
    private ?User $user = null;

    public function __construct()
    {
        $this->ygo_cards = new ArrayCollection();
    }
    
    /**
     * @return string
     */
    public function __toString()
    {
        $s = 'ID : '. $this->getId() .' | Title : '. $this->getTitle();
        return $s;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, YGOCard>
     */
    public function getYgoCards(): Collection
    {
        return $this->ygo_cards;
    }

    public function addYgoCard(YGOCard $ygoCard): static
    {
        if (!$this->ygo_cards->contains($ygoCard)) {
            $this->ygo_cards->add($ygoCard);
            $ygoCard->setPack($this);
        }

        return $this;
    }

    public function removeYgoCard(YGOCard $ygoCard): static
    {
        if ($this->ygo_cards->removeElement($ygoCard)) {
            // set the owning side to null (unless already changed)
            if ($ygoCard->getPack() === $this) {
                $ygoCard->setPack(null);
            }
        }

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        // unset the owning side of the relation if necessary
        if ($user === null && $this->user !== null) {
            $this->user->setPack(null);
        }

        // set the owning side of the relation if necessary
        if ($user !== null && $user->getPack() !== $this) {
            $user->setPack($this);
        }

        $this->user = $user;

        return $this;
    }
}
