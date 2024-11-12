<?php

namespace App\Entity;

use App\Repository\YGOCardRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: YGOCardRepository::class)]
#[Vich\Uploadable]
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

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    private ?string $attribute = null;

    #[ORM\Column(length: 255)]
    private ?string $type = null;

    #[ORM\Column(length: 255)]
    private ?string $race = null;

    #[ORM\Column]
    private ?int $level = null;
    
    #[Vich\UploadableField(mapping: 'ygocards', fileNameProperty: 'imageName', size: 'imageSize')]
    private ?File $imageFile = null;
    
    #[ORM\Column(nullable: true)]
    private ?string $imageName = null;
    
    #[ORM\Column(nullable: true)]
    private ?int $imageSize = null;
    
    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getAttribute(): ?string
    {
        return $this->attribute;
    }

    public function setAttribute(string $attribute): static
    {
        $this->attribute = $attribute;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getRace(): ?string
    {
        return $this->race;
    }

    public function setRace(string $race): static
    {
        $this->race = $race;

        return $this;
    }

    public function getLevel(): ?int
    {
        return $this->level;
    }

    public function setLevel(int $level): static
    {
        $this->level = $level;

        return $this;
    }
    
    //...
    /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $imageFile
     */
    public function setImageFile(?File $imageFile = null): void
    {
        $this->imageFile = $imageFile;
        
        if (null !== $imageFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
        }
    }
    
    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }
    
    public function setImageName(?string $imageName): void
    {
        $this->imageName = $imageName;
    }
    
    public function getImageName(): ?string
    {
        return $this->imageName;
    }
    
    public function setImageSize(?int $imageSize): void
    {
        $this->imageSize = $imageSize;
    }
    
    public function getImageSize(): ?int
    {
        return $this->imageSize;
    }
}
