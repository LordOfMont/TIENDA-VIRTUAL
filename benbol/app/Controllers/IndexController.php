<?php
namespace App\Controllers;
use App\Models\Usuario;
use Zend\Diactoros\Response\RedirectResponse;
class IndexController extends BaseController{
	public function showIndexVisitor($request,$requiredlevel){
		return $this->renderHTML('indexvisitor.twig');	
	}
	public function showIndexUser($request,$requiredlevel){
		$actual = Usuario::where('idUsuario',$_SESSION['userId'])->first();
		$level = $actual->tipo;
		if($requiredlevel==$level){
			return $this->renderHTML('indexuser.twig');	
		}
		else{
			unset($_SESSION['userId']);
			return new RedirectResponse('/benbol/');
		}
	}
	public function showIndexAdmin($request,$requiredlevel){
		
		$actual = Usuario::where('idUsuario',$_SESSION['userId'])->first();
		$level = $actual->tipo;
		if($requiredlevel==$level){
			return $this->renderHTML('indexadmin.twig');
		}
		else{
			unset($_SESSION['userId']);
			return new RedirectResponse('/benbol/');
		}
	}
	public function showIndexSeller($request,$requiredlevel){
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