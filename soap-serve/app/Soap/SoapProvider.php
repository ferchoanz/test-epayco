<?php

namespace App\Soap;

use App\Entities\Cliente;
use App\Http\Resources\ErrorResource;
use App\Http\Resources\SuccessResource;
use Exception;
use LaravelDoctrine\ORM\Facades\EntityManager;

class SoapProvider
{
    public function registro_cliente()
    {
        $parametros = func_get_args();

        try {
            $cliente = new Cliente($parametros[0], $parametros[1], $parametros[2], $parametros[3]);
            EntityManager::persist($cliente);
            EntityManager::flush();
            return new SuccessResource(null);
        } catch (Exception $error) {
            return new ErrorResource($error);
        }
    }
}
