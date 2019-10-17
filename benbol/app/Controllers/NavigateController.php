<?php
namespace App\Controllers;
use App\Models\Usuario;
use App\Models\Producto;
use App\Models\ProductoTalla;
use App\Models\CarritoProducto;
use Illuminate\Support\Facades\DB;
use Zend\Diactoros\Response\RedirectResponse;
use Illuminate\Database\Capsule\Manager as Capsule;
class NavigateController extends BaseController{
	public function ShowMujeres($request,$requiredlevel,$error){
		$actual = Usuario::where('idUsuario',$_SESSION['userId'])->first();
		$level = $actual->tipo;
		if($requiredlevel==$level){
			$categories = Capsule::table('categoria')->get();
			foreach ($categories as $c) {
    			//echo $c->nombre;
			}
			$productoslista = Producto::All();
			return $this->renderHTML('mujer.twig',[
				'products'=>$productoslista,
				'error'=> $error
			]);
		}
		else{
			unset($_SESSION['userId']);
			return new RedirectResponse('/benbol/');
		}
	}
	public function ShowHombres($request,$requiredlevel,$error){
		$actual = Usuario::where('idUsuario',$_SESSION['userId'])->first();
		$level = $actual->tipo;
		if($requiredlevel==$level){
			$productoslista = Producto::All();
			return $this->renderHTML('hombre.twig',[
				'products'=>$productoslista,
				'error'=> $error
			]);
		}
		else{
			unset($_SESSION['userId']);
			return new RedirectResponse('/benbol/');
		}
	}
	public function ShowNinos($request,$requiredlevel,$error){
		$actual = Usuario::where('idUsuario',$_SESSION['userId'])->first();
		$level = $actual->tipo;
		if($requiredlevel==$level){
			$productoslista = Producto::All();
			return $this->renderHTML('nino.twig',[
				'products'=>$productoslista,
				'error'=> $error
			]);
		}
		else{
			unset($_SESSION['userId']);
			return new RedirectResponse('/benbol/');
		}
	}
	public function ShowNinas($request,$requiredlevel,$error){
		$actual = Usuario::where('idUsuario',$_SESSION['userId'])->first();
		$level = $actual->tipo;
		if($requiredlevel==$level){
			$productoslista = Producto::All();
			return $this->renderHTML('nina.twig',[
				'products'=>$productoslista,
				'error'=> $error
			]);
		}
		else{
			unset($_SESSION['userId']);
			return new RedirectResponse('/benbol/');
		}
	}
}