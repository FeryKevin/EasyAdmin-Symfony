<?php

namespace App\Entity;

use App\Repository\TechnologyRepository;
use App\Entity\Interfaces\IdInterface;
use App\Entity\Interfaces\NameInterface;
use App\Entity\Traits\IdTrait;
use App\Entity\Traits\NameTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TechnologyRepository::class)]
class Technology implements IdInterface, NameInterface
{
    use IdTrait;
    use NameTrait;

    #[ORM\ManyToMany(targetEntity: Project::class, mappedBy: 'technologies')]
    private Collection $projects;

    public function __construct()
    {
        $this->projects = new ArrayCollection();
    }

    /**
     * @return Collection<int, Project>
     */
    public function getProjects(): Collection
    {
        return $this->projects;
    }

    public function addProject(Project $projects): self
    {
        if (!$this->projects->contains($projects)) {
            $this->projects[] = $projects;
        }
    
        return $this;
    }

    public function removeProject(Project $projects): self
    {
        $this->projects->removeElement($projects);

        return $this;
    }

    public function __toString()
    {
        return $this->name;
    }
}
