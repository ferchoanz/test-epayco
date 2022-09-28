<?php

namespace App\Entities;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="clientes")
 */

class Cliente
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\Column(type="string")
     */
    protected $documento;

    /**
     * @ORM\Column(type="string")
     */
    protected $nombres;


    /**
     * @ORM\Column(type="string")
     */
    protected $email;


    /**
     * @ORM\Column(type="string")
     */
    protected $celular;

    /**
     * @param $documento
     * @param $nombres
     * @param $email
     * @param $celular
     */

    public function __construct($documento, $nombres, $email, $celular)
    {
        $this->documento = $documento;
        $this->nombres = $nombres;
        $this->email = $email;
        $this->celular = $celular;
    }
}
