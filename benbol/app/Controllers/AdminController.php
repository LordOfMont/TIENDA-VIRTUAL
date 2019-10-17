<?php
namespace App\Controllers;
use App\Models\Usuario;
use App\Models\Producto;
use Zend\Diactoros\Response\RedirectResponse;
class AdminController extends BaseController{
	public function showAdminProductos($request,$requiredlevel){
		$actual = Usuario::where('idUsuario',$_SESSION['userId'])->first();
		$level = $actual->tipo;
		if($requiredlevel==$level){	
			$productoslista = Producto::All();
			return $this->renderHTML('adminproductos.twig',[
				'products' => $productoslista
			]);
		}
		else{
			unset($_SESSION['userId']);
			return new RedirectResponse('/benbol/');
		}
	}
	public function showAdminPromociones($request,$requiredlevel){
		$actual = Usuario::where('idUsuario',$_SESSION['userId'])->first();
		$level = $actual->tipo;
		if($requiredlevel==$level){
			return $this->renderHTML('adminpromociones.twig');	
		}
		else{
			unset($_SESSION['userId']);
			return new RedirectResponse('/benbol/');
		}
	}
	public function showAdminCajeros($request,$requiredlevel){
		$actual = Usuario::where('idUsuario',$_SESSION['userId'])->first();
		$level = $actual->tipo;
		if($requiredlevel==$level){
			$listacajeros=Usuario::All();
			return $this->renderHTML('admincajeros.twig',[
				'cajeros'=>$listacajeros
			]);
		}
		else{
			unset($_SESSION['userId']);
			return new RedirectResponse('/benbol/');
		}
	}
}