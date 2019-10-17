<?php
namespace App\Controllers;
use App\Models\Usuario;
use Respect\Validation\Validator as v;
use Zend\Diactoros\Response\RedirectResponse;
class LoginController extends BaseController{
	public function showLogin(){
		return $this->renderHTML('login.twig');
	}
	public function Authenticate($request){
		$responseMessage=null;
		$postData = $request->getParsedBody();
		$user = Usuario::where('correo',$postData['email'])->first();
		if($user){
            if(\password_verify($postData['password'],$user->contrasenia)){
                if($user->activo==1){
                    $_SESSION['userId']= $user->idUsuario;
                    $nivelacceso=$user->tipo;
                    if($nivelacceso==1){
                    	return new RedirectResponse('/benbol/admin');
                    }
                    if($nivelacceso==2){
                        return new RedirectResponse('/benbol/cajero');
                    }
                    if($nivelacceso==3){
                    	return new RedirectResponse('/benbol/user');
                    }
                }
                else{
                    $responseMessage='Credenciales Invalidas';
                }
            }
            else{
                $responseMessage='Credenciales Invalidas';
            }
        }
        else{
            $responseMessage='Credenciales Invalidas';
        }
        return $this->renderHTML('login.twig',[
            'responseMessage' => $responseMessage
        ]);
	}
	public function Logout(){
        unset($_SESSION['userId']);
        return new RedirectResponse('/benbol/');
    }
}