<?php

namespace App\Entity;

use App\Repository\EmployeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EmployeRepository::class)]
class Employe
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $Email = null;

    #[ORM\Column(nullable: true)]
    private ?int $cost = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $hiredAt = null;

    #[ORM\ManyToOne(inversedBy: 'employes')]
    private ?Job $job = null;

    #[ORM\OneToMany(mappedBy: 'employe', targetEntity: WorkUnit::class)]
    private Collection $workUnits;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $imageURL = null;

    #[ORM\Column(length: 255)]
    private ?string $firstName = null;

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

    public function getEmail(): ?string
    {
        return $this->Email;
    }

    public function setEmail(string $Email): self
    {
        $this->Email = $Email;

        return $this;
    }

    public function getCost(): ?int
    {
        return $this->cost;
    }

    public function setCost(?int $cost): self
    {
        $this->cost = $cost;

        return $this;
    }

    public function getHiredAt(): ?\DateTimeImmutable
    {
        return $this->hiredAt;
    }

    public function setHiredAt(\DateTimeImmutable $hiredAt): self
    {
        $this->hiredAt = $hiredAt;

        return $this;
    }

    public function getJob(): ?Job
    {
        return $this->job;
    }

    public function setJob(?Job $job): self
    {
        $this->job = $job;

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
            $workUnit->setEmploye($this);
        }

        return $this;
    }

    public function removeWorkUnit(WorkUnit $workUnit): self
    {
        if ($this->workUnits->removeElement($workUnit)) {
            // set the owning side to null (unless already changed)
            if ($workUnit->getEmploye() === $this) {
                $workUnit->setEmploye(null);
            }
        }

        return $this;
    }

    public function getImageURL(): ?string
    {
        return $this->imageURL;
    }

    public function setImageURL(?string $imageURL): self
    {
        $this->imageURL = $imageURL;

        return $this;
    }

    public function getTotalCost(): int
    {
        $totalCost = 0;
        foreach ($this->getWorkUnits() as $workUnit) {
            if ($workUnit->getDuration() > 0) {
                $totalCost += $workUnit->getDuration() * $this->getCost();
            }
        }

        return $totalCost;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

}