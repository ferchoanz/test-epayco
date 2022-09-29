<?php

namespace App\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="pagos")
 */
class Pago
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
    protected $session_id;

    /**
     * @ORM\Column(type="string")
     */
    protected $token;


    /**
     * @ORM\Column(type="string")
     */
    protected $valor;


    /**
     * @ORM\Column(type="integer")
     */
    protected $cliente_id;

    /**
     * @ORM\Column(type="integer")
     */
    protected $billetera_id;

    /**
     * @ORM\Column(type="string")
     */
    protected $estado;

    /**
     * @param $session_id
     * @param $token
     * @param $valor
     * @param $cliente_id
     */

    public function __construct($session_id, $token, $valor, $cliente_id, $billetera_id)
    {
        $this->session_id = $session_id;
        $this->token = $token;
        $this->valor = $valor;
        $this->cliente_id = $cliente_id;
        $this->billetera_id = $billetera_id;
        $this->estado = 'Pendiente';
    }

    public function setEstado($estado)
    {
        $this->estado = $estado;
    }
}
