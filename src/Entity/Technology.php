<?php

namespace App\Entity;

use App\Repository\TechnologyRepository;
use App\Entity\Interfaces\IdInterface;
use App\Entity\Interfaces\NameInterface;
use App\Entity\Traits\IdTrait;
use App\Entity\Traits\NameTrait;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TechnologyRepository::class)]
class Technology implements IdInterface, NameInterface
{
    use IdTrait;
    use NameTrait;
}
