<?php

namespace App\Entity;

use App\Repository\ShowcaseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ShowcaseRepository::class)]
class Showcase
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column]
    private ?bool $published = null;

    #[ORM\ManyToOne(inversedBy: 'showcases')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $creator = null;

    /**
     * @var Collection<int, YGOCard>
     */
    #[ORM\ManyToMany(targetEntity: YGOCard::class, inversedBy: 'showcases')]
    private Collection $ygoCards;

    public function __construct()
    {
        $this->ygoCards = new ArrayCollection();
    }
    
    /**
     * @return string
     */
    public function __toString()
    {
        $s = 'ID : '. $this->getId() .' | Description : '. $this->getDescription();
        return $s;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function isPublished(): ?bool
    {
        return $this->published;
    }

    public function setPublished(bool $published): static
    {
        $this->published = $published;

        return $this;
    }

    public function getCreator(): ?User
    {
        return $this->creator;
    }

    public function setCreator(?User $creator): static
    {
        $this->creator = $creator;

        return $this;
    }

    /**
     * @return Collection<int, YGOCard>
     */
    public function getYgoCards(): Collection
    {
        return $this->ygoCards;
    }

    public function addYgoCard(YGOCard $ygoCard): static
    {
        if (!$this->ygoCards->contains($ygoCard)) {
            $this->ygoCards->add($ygoCard);
        }

        return $this;
    }

    public function removeYgoCard(YGOCard $ygoCard): static
    {
        $this->ygoCards->removeElement($ygoCard);

        return $this;
    }
}
