# Prueba de Epayco para el puesto de desarrolador Backend
# Ingeniero en Computacion Fernando Pacheco
## Correo: fercho0281@gmail.com

## Descipcion:
Pequeno sistema para la getion de pagos relacionados a clientes.

### Consideraciones para que el sistema funcione:
- la base de datos utilizada es MySql.
- Hacer migracion de tablas en el proyecto soap-servemediante el comando: php artisan doctrine:schema:update.
- Arrancar el sistema de soap-serve mediante el comando: php artisan serve --port=8001.
- Arrancar el sistema de rest-serve mediante el comando: php artisan serve.
- debe configurar un servicio SMTP para el envio de correos el que utilice para las pruebas fue mailtrap.
- version de php utilizada 8.1.10
- version de MySql mysql  Ver 8.0.30-0ubuntu0.20.04.2 for Linux on x86_64 ((Ubuntu)).
- es necesario activar el estnsion de SOAP para PHP.
- dejo un archivo json con las prueba utilizadas en postman.