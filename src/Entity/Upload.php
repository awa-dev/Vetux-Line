<?php

namespace App\Entity;

use App\Repository\UploadRepository;
use Doctrine\ORM\Mapping as ORM;

class Upload
{
    /* nom du fichier */
    private $names;

    public function getNames()
    {
        return $this->names;
    }

    public function setNames($names)
    {
        $this->names= $names;

        return $this;
    }

    public function addNames($name)
    {
        $this->names[] = $name;

        return $this;
    }
}

