<?php
namespace App\Controllers;
use App\Models\Usuario;
use Zend\Diactoros\Response\RedirectResponse;
use Respect\Validation\Validator as v;
class CajeroController extends BaseController{
	public function AddCajero($request,$requiredlevel){
		$actual = Usuario::where('idUsuario',$_SESSION['userId'])->first();
		$level = $actual->tipo;
		if($requiredlevel==$level){
			$cajero = new Usuario();
			$postData = $request->getParsedBody();
			$cajero->nombre=$postData['name'];
			$cajero->correo=$postData['email'];
			$cajero->contrasenia = password_hash($postData['password'], PASSWORD_DEFAULT);
			$cajero->tipo=2;
			$cajero->activo=1;
			$cajero->save();
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
	public function DeleteCajero($request,$requiredlevel){
		$actual = Usuario::where('idUsuario',$_SESSION['userId'])->first();
		$level = $actual->tipo;
		if($requiredlevel==$level){
			$enlace_actual = $_SERVER['REQUEST_URI'];
			$id=substr($enlace_actual,21);
			$id+=0; 
			var_dump($id);
			$cajeroborrar=Usuario::find($id);
			$cajeroborrar->activo=0;
			$cajeroborrar->save();
			/*
			$cajero = new Usuario();
			$postData = $request->getParsedBody();
			$cajero->nombre=$postData['name'];
			$cajero->correo=$postData['email'];
			$cajero->contrasenia = password_hash($postData['password'], PASSWORD_DEFAULT);
			$cajero->tipo=2;
			$cajero->activo=1;
			$cajero->save();*/
			//$listacajeros=Usuario::All();
			return new RedirectResponse('/benbol/admincajeros');
		}
		else{
			unset($_SESSION['userId']);
			return new RedirectResponse('/benbol/');
		}
	}
	public function showCajero($request,$requiredlevel){
		$actual = Usuario::where('idUsuario',$_SESSION['userId'])->first();
		$level = $actual->tipo;
		if($requiredlevel==$level){
			return $this->renderHTML('indexseller.twig');
		}
		else{
			unset($_SESSION['userId']);
			return new RedirectResponse('/benbol/');
		}
	}
}