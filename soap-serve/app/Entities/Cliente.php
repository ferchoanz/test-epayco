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
     * @ORM\Column(type="string", unique=true)
     */
    protected $documento;

    /**
     * @ORM\Column(type="string")
     */
    protected $nombres;


    /**
     * @ORM\Column(type="string", unique=true)
     */
    protected $email;


    /**
     * @ORM\Column(type="string", unique=true)
     */
    protected $celular;

    /**
     * @ORM\oneToOne(targetEntity="Billetera", inversedBy="clientes", cascade={"persist"})
     */
    protected $billetera;

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

    public function getDocumento()
    {
        return $this->documento;
    }

    public function setBilletera(Billetera $billetera)
    {
        $this->billetera = $billetera;
    }

    public function inicializar_billetera()
    {
        $billetera = new Billetera(0);
        $this->setBilletera($billetera);
        $this->billetera->setCliente($this);
    }

    public function getBilletera()
    {
        return $this->billetera;
    }
}
