<?php

namespace App\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="billeteras")
 */
class Billetera
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\oneToOne(targetEntity="Cliente", inversedBy="billetera")
     * @var Cliente
     */

    protected $cliente;

    /**
     * @ORM\Column(type="integer")
     */
    protected $saldo;

    /**
     * @param $saldo
     */
    public function __construct($saldo)
    {
        $this->saldo = $saldo;
    }

    public function getSaldo()
    {
        return $this->saldo;
    }

    public function setCliente(Cliente $cliente)
    {
        $this->cliente = $cliente;
    }

    public function recarga($valor)
    {
        $this->saldo += $valor;
    }
}
