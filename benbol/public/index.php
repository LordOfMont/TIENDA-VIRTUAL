<?php
$flag=true; 
header('Expires: Sun, 01 Jan 2014 00:00:00 GMT');
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Cache-Control: post-check=0, pre-check=0', FALSE);
header('Pragma: no-cache');
ini_set('display_errors', 1);
ini_set('display_starup_error', 1);
error_reporting(E_ALL);
require_once '../vendor/autoload.php';
session_start();
$timestamp = date("YmdHis"); // output: 20150715164614
use Illuminate\Database\Capsule\Manager as Capsule;
use Aura\Router\RouterContainer;
use App\Models\Usuario;
use App\Models\Producto;
    $capsule = new Capsule;

    $capsule->addConnection([
        'driver'    => 'mysql',
        'host'      => 'localhost',
        'database'  => 'proyectotiendavirtual',
        'username'  => 'root',
        'password'  => '',
        'charset'   => 'utf8',
        'collation' => 'utf8_unicode_ci',
        'prefix'    => '',
    ]);
    $capsule->setAsGlobal();
  	$capsule->bootEloquent();

  	$request = Zend\Diactoros\ServerRequestFactory::fromGlobals(
    $_SERVER,
    $_GET,
    $_POST,
    $_COOKIE,
    $_FILES
);
  		
  	$routerContainer = new RouterContainer();
  	$map = $routerContainer->getMap();
  	$map->get('indexVisitor','/benbol/',[
      'controller' => 'App\Controllers\IndexController',
      'action' => 'showIndexVisitor'
    ]);
    $map->get('indexUser','/benbol/user',[
      'controller' => 'App\Controllers\IndexController',
      'action' => 'showIndexUser',
      'auth' => true,
      'level' => 3
    ]);
    $map->get('indexAdmin','/benbol/admin',[
      'controller' => 'App\Controllers\IndexController',
      'action' => 'showIndexAdmin',
      'auth' => true,
      'level' => 1
    ]);
    $map->get('indexSeller','/benbol/seller',[
      'controller' => 'App\Controllers\IndexController',
      'action' => 'showIndexSeller',
      'auth' => true,
      'level' => 2
    ]);
    $map->get('login','/benbol/login',[
      'controller' => 'App\Controllers\LoginController',
      'action' => 'showLogin'
    ]);
    $map->get('register','/benbol/register',[
      'controller' => 'App\Controllers\RegisterController',
      'action' => 'showRegister'
    ]);
    $map->post('registerDo','/benbol/register',[
      'controller' => 'App\Controllers\RegisterController',
      'action' => 'addRegister'
    ]);
    $map->post('loginDo','/benbol/login',[
      'controller' => 'App\Controllers\LoginController',
      'action' => 'Authenticate'
    ]);
    $map->get('logout','/benbol/logout',[
      'controller' => 'App\Controllers\LoginController',
      'action' => 'Logout'
    ]);
    $map->get('adminproductos','/benbol/adminproductos',[
      'controller' => 'App\Controllers\AdminController',
      'action' => 'showAdminProductos',
      'auth' => true,
      'level' => 1
    ]);
    $map->get('adminpromociones','/benbol/adminpromociones',[
      'controller' => 'App\Controllers\AdminController',
      'action' => 'showAdminPromociones',
      'auth' => true,
      'level' => 1
    ]);
    $map->get('admincajeros','/benbol/admincajeros',[
      'controller' => 'App\Controllers\AdminController',
      'action' => 'showAdminCajeros',
      'auth' => true,
      'level' => 1
    ]);
    $map->post('addProductos','/benbol/addproductos',[
      'controller' => 'App\Controllers\ProductController',
      'action' => 'AddProducto',
      'auth' => true,
      'level'=> 1
    ]);
    $map->post('editProductos','/benbol/editproductos',[
      'controller' => 'App\Controllers\ProductController',
      'action' => 'EditProducto',
      'auth' => true,
      'level'=> 1
    ]);
    $map->get('deleteProductos','/benbol/deleteproducto/{id}',[
      'controller' => 'App\Controllers\ProductController',
      'action' => 'DeleteProducto',
      'auth' => true,
      'level'=> 1,
      'id' => $request->getAttribute('id')
    ]);
    $map->post('addCajero','/benbol/addcajero',[
      'controller' => 'App\Controllers\CajeroController',
      'action' => 'AddCajero',
      'auth' => true,
      'level'=> 1
    ]);
    $map->get('deleteCajero','/benbol/deletecajero/{id}',[
      'controller' => 'App\Controllers\CajeroController',
      'action' => 'DeleteCajero',
      'auth' => true,
      'level'=> 1
    ]);
    $map->get('mujer','/benbol/mujer',[
      'controller' => 'App\Controllers\NavigateController',
      'action' => 'ShowMujeres',
      'auth' => true,
      'level'=> 3
    ]);
    $map->get('hombre','/benbol/hombre',[
      'controller' => 'App\Controllers\NavigateController',
      'action' => 'ShowHombres',
      'auth' => true,
      'level'=> 3
    ]);
    $map->get('nina','/benbol/nina',[
      'controller' => 'App\Controllers\NavigateController',
      'action' => 'ShowNinas',
      'auth' => true,
      'level'=> 3
    ]);
    $map->get('nino','/benbol/nino',[
      'controller' => 'App\Controllers\NavigateController',
      'action' => 'ShowNinos',
      'auth' => true,
      'level'=> 3
    ]);
    $map->post('addcarrito','/benbol/addcarrito/{id}',[
      'controller' => 'App\Controllers\CarritoController',
      'action' => 'AddCarrito',
      'auth' => true,
      'level'=> 3
    ]);
    $map->get('showcarrito','/benbol/carrito',[
      'controller' => 'App\Controllers\CarritoController',
      'action' => 'ShowCarrito',
      'auth' => true,
      'level'=> 3
    ]);
    $map->get('deletecarritoprod','/benbol/deleteprodcarrito/{id}/{id2}',[
      'controller' => 'App\Controllers\CarritoController',
      'action' => 'DeleteProdCarrito',
      'auth' => true,
      'level'=> 3
    ]);
    $map->get('comprar','/benbol/comprar',[
      'controller' => 'App\Controllers\CarritoController',
      'action' => 'ShowDetails',
      'auth' => true,
      'level'=> 3
    ]);
    $map->get('pagar','/benbol/pago',[
      'controller' => 'App\Controllers\CarritoController',
      'action' => 'MostrarPago',
      'auth' => true,
      'level'=> 3
    ]);
    $map->post('procesar','/benbol/pago',[
      'controller' => 'App\Controllers\CarritoController',
      'action' => 'ProcesarPago',
      'auth' => true,
      'level'=> 3
    ]);
    $map->get('showCajero','/benbol/cajero',[
      'controller' => 'App\Controllers\CajeroController',
      'action' => 'showCajero',
      'auth' => true,
      'level'=> 2
    ]);
    $map->post('ValidarCodigo','/benbol/cajero',[
      'controller' => 'App\Controllers\CarritoController',
      'action' => 'ValidarCodigo',
      'auth' => true,
      'level'=> 2
    ]);
    $map->post('facturar','/benbol/factura/{id}',[
      'controller' => 'App\Controllers\CarritoController',
      'action' => 'Facturar',
      'auth' => true,
      'level'=> 2
    ]);
  	$matcher = $routerContainer->getMatcher();
  	$route = $matcher->match($request);
    //var_dump($route);
  	if(!$route){
  		echo 'No route';
  	}
  	else{
      $handlerData = $route->handler;
      $needsAuth= $handlerData['auth'] ?? false;
      $sessionUserId=$_SESSION['userId'] ?? null;
      $requiredlevel=$handlerData['level'] ?? null;
      $id=$handlerData['id'] ?? null;
      if($needsAuth && !$sessionUserId){
        $actionName = 'Logout';
        $controllerName = 'App\Controllers\LoginController';
      }
      else{
          $actionName = $handlerData['action'];
          $controllerName = $handlerData['controller'];
      }
      $controller = new $controllerName;
      $response=$controller->$actionName($request,$requiredlevel,null);
      foreach ($response->getHeaders() as $name => $values) {
        foreach ($values as $value) {
          header(sprintf('%s: %s',$name,$value),false);
        }
      }
      http_response_code($response->getStatusCode());
      echo $response->getBody();
  	}