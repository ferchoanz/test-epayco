<?php

namespace App\Soap;

use App\Entities\Billetera;
use App\Entities\Cliente;
use App\Http\Resources\ErrorResource;
use App\Http\Resources\SaldoResource;
use App\Http\Resources\SuccessResource;
use Doctrine\ORM\Query\ResultSetMapping;
use Exception;
use GuzzleHttp\Psr7\Request;
use LaravelDoctrine\ORM\Facades\EntityManager;

use function Psy\bin;

class SoapProvider
{
    public function registro_cliente()
    {
        $parametros = func_get_args();

        try {
            $cliente = new Cliente($parametros[0], $parametros[1], $parametros[2], $parametros[3]);
            $cliente->inicializar_billetera();
            EntityManager::persist($cliente);
            EntityManager::flush();
            return new SuccessResource(true);
        } catch (Exception $error) {
            return new ErrorResource($error);
        }
    }

    public function recarga_billetera()
    {
        $parametros = func_get_args();

        try {
            $billetera = EntityManager::createQueryBuilder()
            ->from(Cliente::class, 'c')
            ->innerJoin(Billetera::class, 'b')
            ->select('b.id')
            ->where("c.documento = '{$parametros[0]}'")
            ->andWhere("c.celular = '{$parametros[1]}'")
            ->setMaxResults(1)
            ->getQuery()
            ->getSingleResult();

            if(is_object($billetera)) {
                abort(404, $billetera->resource->message);
            }

            $billetera = EntityManager::find(Billetera::class, intval($billetera['id']));
            $billetera->recarga($parametros[2]);
            EntityManager::flush();

            return new SuccessResource(true);

        } catch (Exception $error) {
            return new ErrorResource($error);
        }
    }

    public function consultar_saldo()
    {
        $parametros = func_get_args();

        try {
            $billetera = EntityManager::createQueryBuilder()
            ->from(Cliente::class, 'c')
            ->innerJoin(Billetera::class, 'b')
            ->select('b.id, b.saldo')
            ->where("c.documento = '{$parametros[0]}'")
            ->andWhere("c.celular = '{$parametros[1]}'")
            ->setMaxResults(1)
            ->getQuery()
            ->getSingleResult();


            if(is_object($billetera)) {
                abort(404, $billetera->resource->message);
            }

            return new SaldoResource($billetera);

        } catch (Exception $error) {
            return new ErrorResource($error);
        }
    }
}
