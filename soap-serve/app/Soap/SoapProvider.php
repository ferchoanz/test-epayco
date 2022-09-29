<?php

namespace App\Soap;

use App\Entities\Billetera;
use App\Entities\Cliente;
use App\Entities\Pago;
use App\Http\Resources\ErrorResource;
use App\Http\Resources\PagoResource;
use App\Http\Resources\SaldoResource;
use App\Http\Resources\SuccessResource;
use Exception;
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

            if (is_object($billetera)) {
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

    public function pagar()
    {
        $parametros = func_get_args();

        try {
            $billetera = EntityManager::createQueryBuilder()
                ->from(Cliente::class, 'c')
                ->innerJoin(Billetera::class, 'b')
                ->select('b.id, b.saldo, c.id as cliente_id, c.nombres, c.documento, c.email')
                ->where("c.documento = '{$parametros[0]}'")
                ->andWhere("c.celular = '{$parametros[1]}'")
                ->setMaxResults(1)
                ->getQuery()
                ->getSingleResult();

            if (is_object($billetera)) {
                abort(404, $billetera->resource->message);
            }

            if (intval($billetera['saldo'] < intval($parametros[2]))) {
                abort(400, 'saldo insufiente');
            }

            $token = $this->generar_token($billetera['nombres'], $billetera['documento']);

            $compra = new Pago($parametros[3], $token, $parametros[2], $billetera['cliente_id'], $billetera['id']);
            EntityManager::persist($compra);
            EntityManager::flush();

            $billetera = array_merge($billetera, ['token' => $token, 'session_id' => $parametros[3]]);

            return new PagoResource($billetera);
        } catch (Exception $error) {
            return new ErrorResource($error);
        }
    }

    public function confirmar_pago()
    {
        $parametros = func_get_args();

        try {
            $pago = EntityManager::createQueryBuilder()
                ->from(Pago::class, 'p')
                ->select('p.id, p.valor, p.cliente_id, p.billetera_id')
                ->where("p.token = '{$parametros[0]}'")
                ->andWhere("p.session_id = '{$parametros[1]}'")
                ->andWhere("p.estado = 'Pendiente'")
                ->setMaxResults(1)
                ->getQuery()
                ->getSingleResult();

            if (is_object($pago)) {
                abort(404, $pago->resource->message);
            }

            $billetera = EntityManager::find(Billetera::class, $pago['billetera_id']);
            $billetera->descuento(intval($pago['valor']));
            EntityManager::persist($billetera);
            EntityManager::flush();

            $pago = EntityManager::find(Pago::class, $pago['id']);
            $pago->setEstado('Finalizado');
            EntityManager::persist($pago);
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


            if (is_object($billetera)) {
                abort(404, $billetera->resource->message);
            }

            return new SaldoResource($billetera);
        } catch (Exception $error) {
            return new ErrorResource($error);
        }
    }

    public function generar_token($nombres, $documento)
    {
        $token = str_split(md5("{$nombres}{$documento}"));
        $token = array_splice($token, 0, 6);
        $token = implode($token);
        return $token;
    }
}
