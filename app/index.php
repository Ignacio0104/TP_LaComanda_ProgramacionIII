<?php
// Error Handling
error_reporting(-1);
ini_set('display_errors', 1);

use Psr7Middlewares\Middleware;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Factory\AppFactory;
use Slim\Routing\RouteCollectorProxy;
use Slim\Routing\RouteContext;

//require_once  './middlewares/MiddlewareLogin.php';
require_once  './middlewares/CheckMozoMiddleware.php';
require_once  './middlewares/CheckTokenMiddleware.php';
require_once  './middlewares/CheckSocioMiddleware.php';
//require_once './middlewares/CheckPerfilMiddleware.php';

require __DIR__ . '/../vendor/autoload.php';

require_once './db/AccesoDatos.php';
// require_once './middlewares/Logger.php';

//require_once './controllers/UsuarioController.php';
//require_once './controllers/LoginController.php';
require_once './controllers/AutenticadorController.php';
require_once './controllers/EmpleadoController.php';
require_once './controllers/ProductoController.php';
require_once './controllers/MesaController.php';
require_once './controllers/ComandaController.php';
require_once './controllers/ProductoPedidoController.php';

// Load ENV
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

// Instantiate App
$app = AppFactory::create();

// Add error middleware
$app->addErrorMiddleware(true, true, true);

// Add parse body
$app->addBodyParsingMiddleware();

// Routes
$app->group('/usuarios', function (RouteCollectorProxy $group) {
    $group->post('/cargarUsuario', \EmpleadoController::class . ':CargarUno')->add(new CheckSocioMiddleware());;
    $group->get('/traerUsuarios', \EmpleadoController::class . ':TraerTodos');
    $group->post('/cargarPlato', \ProductoController::class . ':CargarUno')->add(new CheckSocioMiddleware());
    $group->get('/traerProductos', \ProductoController::class . ':TraerTodos');
    $group->post('/altaDeMesa', \MesaController::class . ':CargarUno')->add(new CheckMozoMiddleware());
    $group->post('/altaPedido', \ComandaController::class . ':CargarUno')->add(new CheckMozoMiddleware());
    $group->get('/traerPedidos', \ComandaController::class . ':TraerTodos');
    $group->post('/altaPendiente', \ProductoPedidoController::class . ':CargarUno')->add(new CheckMozoMiddleware());
    $group->get('/traerPendientes', \ProductoPedidoController::class . ':TraerPendientesPersonales');
    $group->get('/traerTodosPendientes', \ProductoPedidoController::class . ':TraerTodosPendientes');
    $group->post('/completarPedido', \ProductoPedidoController::class . ':CompletarPedido');
    //$group->get('[/]', \UsuarioController::class . ':TraerTodos') ;
    //$group->get('/{usuario}', \UsuarioController::class . ':TraerUno');
    //$group->post('[/]', \UsuarioController::class . ':CargarUno')->add(new CheckPerfilMiddleware());
    //$group->put("/modificar", \UsuarioController::class . ':ModificarUno')->add(new CheckPerfilMiddleware());
    //$group->delete("/borrar", \UsuarioController::class . ':BorrarUno')->add(new CheckPerfilMiddleware());
  })->add(new CheckTokenMiddleware());

//Genero el token
$app->post('/login', \AutentificadorController::class . ':CrearTokenLogin');
$app->get('/esperaMesa', \MesaController::class . ':TraerEsperaMesa');





$app->get('[/]', function (Request $request, Response $response) {    
    $response->getBody()->write("La Comanda - TP Programacion III ");
    return $response;
});

$app->run();
