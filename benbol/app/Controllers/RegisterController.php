<?php
namespace App\Controllers;
use App\Models\Usuario;
use App\Models\Carrito;

use Respect\Validation\Validator as v;
class RegisterController extends BaseController{
	public function showRegister(){
		$responseMessage=null;
		return $this->renderHTML('register.twig', [
            	'responseMessage' =>$responseMessage
        	]);
	}
	public function addRegister($request){
		$responseMessage=null;
		if($request->getMethod()=='POST'){
			$postData = $request->getParsedBody();
			//var_dump($postData);
			$existente = new Usuario();
			try{
				$existente = (Usuario::where('correo',$postData['email'])->firstOrFail());
				$responseMessage = 'Credenciales Invalidas';	
			}catch (\Exception $e) {
               if($postData['password']==$postData['password2']){
					try {		
		                //PARA EL USUARIO
		                $user = new Usuario();
		                $user->nombre = $postData['name'];
		                $user->correo = $postData['email'];
		                $user->contrasenia = password_hash($postData['password'], PASSWORD_DEFAULT);
		                $user->direccion = $postData['direccion'];
		                if($postData['genre']=='femenino'){
		                	$user->genero =1;
		                }
		                else{
		                	$user->genero = 2;
		                }
		                $user->tipo = 3;
		                $user->activo = 1;
		                $user->save();
		           		$responseMessage = 'Registrado';
		           		$user = Usuario::where('correo',$postData['email'])->first();
		           		//PARA EL CARRITO
                    	$curcarrito = new Carrito();
            
                        $hoy = getdate();
                        $dia =$hoy['mday'];
                        $mes =$hoy['mon'];
                        $anio =$hoy['year'];
                        $part='com'.substr($user->nombre,0,3);
                        $part=strtoupper($part);
                        $curcarrito->usuario = $user->idUsuario;
                        $curcarrito->estado = 0;
                        $curcarrito->fecha = ($anio.'-'.$mes.'-'.$dia);
                        $curcarrito->codigo ="$part$user->idUsuario";
                        $curcarrito->monto = 0.0;
                        $curcarrito->save();
                        
		            } catch (\Exception $e) {
		                $responseMessage = $e->getMessage();
		            }

	            }
	            else{
	            	$responseMessage='Contraseñas no Coinciden';
	            }
            }
        	return $this->renderHTML('register.twig', [
            	'responseMessage' =>$responseMessage
        	]);
		}
	}
}