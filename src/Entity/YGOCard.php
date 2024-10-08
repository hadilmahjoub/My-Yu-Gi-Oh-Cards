<?php

namespace App\Entity;

use App\Repository\YGOCardRepository;
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
     * @return string
     */
    public function __toString()
    {
        $s = '';
        $s .= 'ID : '. $this->getId() .' | Name : '. $this->getName() .' | Pack ID : '. $this->getPack()->getId();
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
}
