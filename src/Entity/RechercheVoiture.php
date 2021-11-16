<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;

class RechercheVoiture
{
    /**
     * @Assert\LessThanOrEqual(propertyPath = "maxAnnee", message = "La valeur du champ 'Année de :' doit être plus petite que la valeur du champ 'Année à : ' ")
     */
    private $minAnnee;

    /**
     * @Assert\GreaterThanOrEqual(propertyPath = "minAnnee", message = "La valeur du champ 'Année à :' doit être plus grande que la valeur du champ 'Année de :' ")
     */
    private $maxAnnee;

    public function getMinAnnee(): ?int
    {
        return $this->minAnnee;
    }

    public function setMinAnnee(int $minAnnee): self
    {
        $this->minAnnee = $minAnnee;

        return $this;
    }

    public function getMaxAnnee(): ?int
    {
        return $this->maxAnnee;
    }

    public function setMaxAnnee(int $maxAnnee): self
    {
        $this->maxAnnee = $maxAnnee;

        return $this;
    }

}
