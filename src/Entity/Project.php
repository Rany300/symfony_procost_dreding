<?php

namespace App\Entity;

use App\Repository\ProjectRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProjectRepository::class)]
class Project
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(nullable: true)]
    private ?int $price = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $deliveredAt = null;

    #[ORM\OneToMany(mappedBy: 'project', targetEntity: WorkUnit::class, orphanRemoval: true)]
    private Collection $workUnits;

    public function __construct()
    {
        $this->workUnits = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(?int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getDeliveredAt(): ?\DateTimeImmutable
    {
        return $this->deliveredAt;
    }

    public function setDeliveredAt(?\DateTimeImmutable $deliveredAt): self
    {
        $this->deliveredAt = $deliveredAt;

        return $this;
    }

    /**
     * @return Collection<int, WorkUnit>
     */
    public function getWorkUnits(): Collection
    {
        return $this->workUnits;
    }

    public function addWorkUnit(WorkUnit $workUnit): self
    {
        if (!$this->workUnits->contains($workUnit)) {
            $this->workUnits->add($workUnit);
            $workUnit->setProject($this);
        }

        return $this;
    }

    public function removeWorkUnit(WorkUnit $workUnit): self
    {
        if ($this->workUnits->removeElement($workUnit)) {
            // set the owning side to null (unless already changed)
            if ($workUnit->getProject() === $this) {
                $workUnit->setProject(null);
            }
        }

        return $this;
    }

    public function getCost(): int
    {
        $cost = 0;
        foreach ($this->workUnits as $workUnit) {
            $dailyCost = $workUnit->getEmploye()->getCost();
            $days = $workUnit->getDuration();
            if ($days > 0) {
                $cost += $dailyCost * $days;
            }
        }

        return $cost;
    }

}
