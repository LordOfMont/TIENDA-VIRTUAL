<?php
namespace App\Controllers;
use App\Models\Usuario;
use App\Models\Producto;
use App\Models\ProductoTalla;
use App\Models\CarritoProducto;
use App\Models\Carrito;
use Illuminate\Support\Facades\DB;
use Zend\Diactoros\Response\RedirectResponse;
use Illuminate\Database\Capsule\Manager as Capsule;
class CarritoController extends BaseController{
	public function AddCarrito($request,$requiredlevel){
		$actual = Usuario::where('idUsuario',$_SESSION['userId'])->first();
		$level = $actual->tipo;
		if($requiredlevel==$level){
			$carrito=null;
			$cantidad=0;
			$idpt=null;	
			$enlace_actual = $_SERVER['REQUEST_URI'];
			//$responseMessage=null;
			$useractual=$actual->idUsuario; 
			$postData = $request->getParsedBody();
			$selectedtalla=$postData['tallas'];
			$selectedtalla+=0;
			//var_dump($selectedtalla);
			$idpro=substr($enlace_actual,19);
			$idpro+=0;
			$prodid=Producto::find($idpro);
			$sector=$prodid->categoria;
			$sector+=0;
			$carritoactual = Capsule::select('select idCarrito from carrito where usuario = ? and estado = ?', [$useractual,0]);
            foreach ($carritoactual as $c) {
    			$carrito= $c->idCarrito;
    			break;
			}
			//var_dump($carrito);
			//carrito tiene al carrito del usuario
			$ptactual = Capsule::select('select idpt,stockdisp from productotalla where producto = ? and talla = ?', [$idpro,$selectedtalla]);

			foreach ($ptactual as $pt) {
				$idpt = $pt->idpt;
				$cantidad = $pt->stockdisp;
				break;
			}
			if($cantidad>0){
				if($carrito){
					$carritoproducto = new CarritoProducto;
					$carritoproducto->carrito=$carrito;
					$carritoproducto->productotalla=$idpt;
					$carritoproducto->activo=1;
					$carritoproducto->save();
					$actualizarpt = ProductoTalla::find($idpt);
					$cantactual=$actualizarpt->stockdisp;
					$compraactual=$actualizarpt->stockcompra;
					$actualizarpt->stockdisp=$cantactual-1;
					$actualizarpt->stockcompra=$compraactual+1;
					$actualizarpt->save();
					if($sector>=1 && $sector<=5){
						return new RedirectResponse('/benbol/nino');
					}
					if($sector>=6 && $sector<=10){
						return new RedirectResponse('/benbol/nina');
					}
					if($sector>=11 && $sector<=15){
						return new RedirectResponse('/benbol/hombre');
					}
					if($sector>=16 && $sector<=20){
						return new RedirectResponse('/benbol/mujer');
					}
				}
				else{
					echo '<script>alert("USTED CUENTA CON UNA RESERVA COMPLETE ESA RESERVA");</script>';
					if($sector>=1 && $sector<=5){
						return new RedirectResponse('/benbol/nino');
					}
					if($sector>=6 && $sector<=10){
						return new RedirectResponse('/benbol/nina');
					}
					if($sector>=11 && $sector<=15){
						return new RedirectResponse('/benbol/hombre');
					}
					if($sector>=16 && $sector<=20){
						return new RedirectResponse('/benbol/mujer');
					}
				}
			}
			else{
				echo '<script>alert("YA NO EXISTE STOCK DE ESE PRODUCTO POR FAVOR SELECCIONA OTRO");</script>';
				if($sector>=1 && $sector<=5){
						return new RedirectResponse('/benbol/nino');
					}
					if($sector>=6 && $sector<=10){
						return new RedirectResponse('/benbol/nina');
					}
					if($sector>=11 && $sector<=15){
						return new RedirectResponse('/benbol/hombre');
					}
					if($sector>=16 && $sector<=20){
						return new RedirectResponse('/benbol/mujer');
					}
			}
			
		}
		else{
			unset($_SESSION['userId']);
			return new RedirectResponse('/benbol/');
		}
	}
	public function ShowCarrito($request,$requiredlevel){
		$actual = Usuario::where('idUsuario',$_SESSION['userId'])->first();
		$level = $actual->tipo;
		if($requiredlevel==$level){
			$carrito=null;
			$useractual=$actual->idUsuario;
			$carritoactual = Capsule::select('select idCarrito from carrito where usuario = ? and estado = ?', [$useractual,0]);
            foreach ($carritoactual as $c) {
    			$carrito= $c->idCarrito;
    			break;
			}
			$prodcarrito = Capsule::select('select c.idProducto,c.imagen,c.nombre as producto,c.precio,d.nombre as tallap, count(c.idProducto) as cantidad from productotalla a, carritoproducto b, producto c, talla d where b.carrito = ? and b.productotalla = a.idpt and a.producto = c.idProducto and a.talla=d.idTalla and b.activo=1 group by c.idProducto,d.nombre', [$carrito]);
			
			return $this->renderHTML('carrito.twig',[
				'products'=>$prodcarrito
			]);
		}
		else{
			unset($_SESSION['userId']);
			return new RedirectResponse('/benbol/');
		}
	}
	public function DeleteProdCarrito($request,$requiredlevel){
		$actual = Usuario::where('idUsuario',$_SESSION['userId'])->first();
		$level = $actual->tipo;
		if($requiredlevel==$level){
			$useractual=$actual->idUsuario;
			$produtalla=0;
			$enlace_actual = $_SERVER['REQUEST_URI'];
			$util=substr($enlace_actual,26);
			$pos=strripos($util, "/");
			$idprod=substr($util, 0,$pos);
			$idprod+=0;
			$talla=substr($util,$pos+1);
			$idtalla=0;
			if($talla=='4'){ $idtalla=1; }
			if($talla=='6'){ $idtalla=2; }
			if($talla=='8'){ $idtalla=3; }
			if($talla=='10'){ $idtalla=4; }
			if($talla=='12'){ $idtalla=5; }
			if($talla=='14'){ $idtalla=6; }
			if($talla=='S'){ $idtalla=7; }
			if($talla=='M'){ $idtalla=8; }
			if($talla=='L'){ $idtalla=9; }
			if($talla=='XL'){ $idtalla=10; }
			$carrito=0;
			$carritoactual = Capsule::select('select idCarrito from carrito where usuario = ? and estado = ?', [$useractual,0]);
            foreach ($carritoactual as $c) {
    			$carrito= $c->idCarrito;
    			break;
			}
			$prodtall = Capsule::select('select idpt from productotalla where talla = ? and producto = ?', [$idtalla,$idprod]);
			foreach ($prodtall as $p) {
				$produtalla=$p->idpt;
				break;
			}
			$quantity=0;
			if($produtalla){
				$cantidad=Capsule::select('select count(idcp) as cant from carritoproducto where productotalla= ? and carrito=? and activo=1',[$produtalla,$carrito]);
				foreach ($cantidad as $c) {
					$quantity=$c->cant;
					break;
				}
				$quantity+=0;
				$affected = Capsule::update('update carritoproducto set activo=0 where productotalla= ? and carrito=?',[$produtalla,$carrito]);
				$actualizarpt = ProductoTalla::find($produtalla);
				$cantactual=$actualizarpt->stockdisp;
				//echo $quantity;
				//echo '<br>';
				//echo $cantactual;
				//echo '<br>';
				$compraactual=$actualizarpt->stockcompra;
				//echo $compraactual;
				//echo '<br>';
				$actualizarpt->stockdisp=$cantactual+$quantity;
				$actualizarpt->stockcompra=$compraactual-$quantity;
				$actualizarpt->save();
			}
			return new RedirectResponse('/benbol/carrito');
		}
		else{
			unset($_SESSION['userId']);
			return new RedirectResponse('/benbol/');
		}
	}
	public function ShowDetails($request,$requiredlevel){
		$actual = Usuario::where('idUsuario',$_SESSION['userId'])->first();
		$level = $actual->tipo;
		if($requiredlevel==$level){
			$carrito=null;
			$useractual=$actual->idUsuario;
			$carritoactual = Capsule::select('select idCarrito from carrito where usuario = ? and estado = ?', [$useractual,0]);
            foreach ($carritoactual as $c) {
    			$carrito= $c->idCarrito;
    			break;
			}
			$prodcarrito = Capsule::select('select c.idProducto,c.nombre as producto,c.precio,d.nombre as tallap, count(c.idProducto) as cantidad, sum(precio) as subt from productotalla a, carritoproducto b, producto c, talla d where b.carrito = ? and b.productotalla = a.idpt and a.producto = c.idProducto and a.talla=d.idTalla and b.activo=1 group by c.idProducto,d.nombre', [$carrito]);
			$total=0;
			foreach ($prodcarrito as $p) {
				$total=$total+($p->precio*$p->cantidad);
			}
			return $this->renderHTML('detallecompra.twig',[
				'products'=>$prodcarrito,
				'total'=>$total
			]);
		}
		else{
			unset($_SESSION['userId']);
			return new RedirectResponse('/benbol/');
		}
	}
	public function MostrarPago($request,$requiredlevel){
		$actual = Usuario::where('idUsuario',$_SESSION['userId'])->first();
		$level = $actual->tipo;
		if($requiredlevel==$level){
			return $this->renderHTML('pago.twig');
		}
		else{
			unset($_SESSION['userId']);
			return new RedirectResponse('/benbol/');
		}
	}
	public function ProcesarPago($request,$requiredlevel){
		$actual = Usuario::where('idUsuario',$_SESSION['userId'])->first();
		$level = $actual->tipo;
		if($requiredlevel==$level){
			$postData = $request->getParsedBody();
			$existe = Capsule::select('select nt from tarjetas where nombre=? and apellido=? and nt=? and venc=? and codigo=?',[$postData['nombre'],$postData['apellido'],$postData['numero'],$postData['fecha'],$postData['cvv']]);
			if($existe){
				$carrito=null;
				$useractual=$actual->idUsuario;
				$carritoactual = Capsule::select('select idCarrito from carrito where usuario = ? and estado = ?', [$useractual,0]);
				foreach ($carritoactual as $c) {
    				$carrito= $c->idCarrito;
    				break;
				}	
				$affected=Capsule::update('update carrito set estado=1 where idCarrito=?',[$carrito]);
				$curcarrito = new Carrito();
				$hoy = getdate();
                $dia =$hoy['mday'];
                $mes =$hoy['mon'];
                $anio =$hoy['year'];
                $curcarrito->usuario = $useractual;
                $curcarrito->estado = 0;
                $curcarrito->fecha = ($anio.'-'.$mes.'-'.$dia);
                $curcarrito->save();
                
				return $this->renderHTML('codigo.twig',[
					'code'=>$carrito
				]);
			}
			else{
				return $this->renderHTML('pago.twig',[
					'responseMessage'=>'TARJETA INVALIDA'
				]);
			}
		}
		else{
			unset($_SESSION['userId']);
			return new RedirectResponse('/benbol/');
		}
	}
	public function ValidarCodigo($request,$requiredlevel){
		$actual = Usuario::where('idUsuario',$_SESSION['userId'])->first();
		$level = $actual->tipo;
		if($requiredlevel==$level){
			$postData = $request->getParsedBody();
			$existe = Capsule::select('select idCarrito from carrito where idCarrito=? and estado=1',[$postData['code']]);
			if($existe){
				$affected= Capsule::update('update carrito set estado=2 where idCarrito=?',[$postData['code']]);
				$prodcarrito = Capsule::select('select c.idProducto,c.nombre as producto,c.precio,d.nombre as tallap, count(c.idProducto) as cantidad, sum(precio) as subt from productotalla a, carritoproducto b, producto c, talla d where b.carrito = ? and b.productotalla = a.idpt and a.producto = c.idProducto and a.talla=d.idTalla and b.activo=1 group by c.idProducto,d.nombre', [$postData['code']]);
				$total=0;
				foreach ($prodcarrito as $p) {
					$total=$total+($p->precio*$p->cantidad);
				}
				return $this->renderHTML('datosfactura.twig',[
					'products'=>$prodcarrito,
					'total'=>$total,
					'codigo'=>$postData['code']
				]);	
			}
			else{
				return $this->renderHTML('indexseller.twig',[
					'responseMessage'=>'CODIGO INVALIDO'
				]);
			}
		}
		else{
			unset($_SESSION['userId']);
			return new RedirectResponse('/benbol/');
		}
	}
	public function Facturar($request,$requiredlevel){
		$actual = Usuario::where('idUsuario',$_SESSION['userId'])->first();
		$level = $actual->tipo;
		if($requiredlevel==$level){
			$nitempre='1020487021';
			$nofactura=null;
			$autorizacion='272401800072534';
			$fecha=null;
			$hora=null;
			$literal=null;
			$codcontrol=null;
			$flimite=null;
			$postData = $request->getParsedBody();
			$enlace_actual = $_SERVER['REQUEST_URI'];
			$util=substr($enlace_actual,16);
			$util+=0;
			$prodcarrito = Capsule::select('select c.idProducto,c.nombre as producto,c.precio,d.nombre as tallap, count(c.idProducto) as cantidad, sum(precio) as subt from productotalla a, carritoproducto b, producto c, talla d where b.carrito = ? and b.productotalla = a.idpt and a.producto = c.idProducto and a.talla=d.idTalla and b.activo=1 group by c.idProducto,d.nombre', [$util]);
			$total=0;
			foreach ($prodcarrito as $p) {
				$total=$total+($p->precio*$p->cantidad);
			}
			//OBTENER nofactura
			$tf=Capsule::select('select * from factura');
			$i=0;
			foreach ($tf as $fac) {
				$i=$i+1;
			}
			$nofactura=$i+1;
			Capsule::insert('insert into factura(carrito) values (?)',[$util]);
			//echo $nofactura;
			//echo '<br>';
			//OBTENER fecha y hora
			$hoy=getdate();
			$horacorrecta=$hoy['hours']-5;
			if($horacorrecta<0){
				$horacorrecta=19+$hoy['hours'];
			}
			$hora=$horacorrecta.':'.$hoy['minutes'].':'.$hoy['seconds'];
			$fecha=$hoy['mday'].'/'.$hoy['mon'].'/'.$hoy['year'];
			//echo $hora;
			//echo '<br>';
			//echo $fecha;
			//echo '<br>';
			//OBTENER LITERAL
			$pos=strripos($total,'.');
			if($pos){
			$entero=substr($total, 0,$pos);
			$entero+=0;
			$decimal=substr($total,$pos+1);
			$decimal+=0;
			}
			else{
				$entero=$total;
				$entero+=0;
				$decimal=0;
			}
			$normal=array(
				0=>null,
				1=>'un',
				2=>'dos',
				3=>'tres',
				4=>'cuatro',
				5=>'cinco',
				6=>'seis',
				7=>'siete',
				8=>'ocho',
				9=>'nueve',
			);
			$centena=array(
				0=>null,
				1=>'cien',
				2=>'doscientos',
				3=>'trescientos',
				4=>'cuatrocientos',
				5=>'quinientos',
				6=>'seiscientos',
				7=>'setecientos',
				8=>'ochocientos',
				9=>'novecientos',
			);
			$decena=array(
				0=>null,
				1=>null,
				2=>'veinti',
				3=>'treinta y ',
				4=>'cuarenta y ',
				5=>'cincuenta y ',
				6=>'sesenta y ',
				7=>'setenta y ',
				8=>'ochenta y ',
				9=>'noventa y ',
			);
			$menores=array(
				0=>null,
				1=>'uno',
				2=>'dos',
				3=>'tres',
				4=>'cuatro',
				5=>'cinco',
				6=>'seis',
				7=>'siete',
				8=>'ocho',
				9=>'nueve',
				10=>'diez',
				11=>'once',
				12=>'doce',
				13=>'trece',
				14=>'catorce',
				15=>'quince',
				16=>'dieciseis',
				17=>'diecisiete',
				18=>'dieciocho',
				19=>'diecinueve',
				20=>'veinte',
			);

			$miles=floor($entero/1000);
			$entero=$entero%1000;
			$cientos=floor($entero/100);
			$entero=$entero%100;
			//echo $miles;
			//echo '<br>';
			//echo $cientos;
			//echo '<br>';
			if($miles>0){
				$literal=$normal[$miles].' mil ';
			}
			if($cientos>0){
				$literal=$literal.$centena[$cientos].' ';
			}
			if($entero>20){
				$dec=$entero/10;
				$entero=$entero%10;
				$literal=$literal.$decena[$dec].$menores[$entero];
			}
			else{
				$literal=$literal.$menores[$entero];
			}
			$literal=strtoupper($literal);
			$literal=$literal.' CON '.$decimal.'/100 BOLIVIANOS.';
			//CODIGO DE CONTROL RANDOMICO
			$a=strtoupper(dechex(rand(0,255)));
			$b=strtoupper(dechex(rand(0,255)));
			$c=strtoupper(dechex(rand(0,255)));
			$d=strtoupper(dechex(rand(0,255)));
			//var_dump($a);
			//var_dump($b);
			//var_dump($c);
			//var_dump($d);
			$codcontrol=$a.'-'.$b.'-'.$c.'-'.$d;
			//LIMITE DE EMISION A UNA ANIO
			$newyear=$hoy['year']+1;
			$flimite=$hoy['mday'].'/'.$hoy['mon'].'/'.$newyear;
			return $this->renderHTML('factura.twig',[
					'products'=>$prodcarrito,
					'total'=>$total,
					'nombre'=>$postData['nombre'],
					'nit'=>$postData['nit'],
					'nitempre'=>$nitempre,
					'nofactura'=>$nofactura,
					'autorizacion'=>$autorizacion,
					'fecha'=>$fecha,
					'hora'=>$hora,
					'literal'=>$literal,
					'codcontrol'=>$codcontrol,
					'flimite'=>$flimite
				]);
		}
		else{
			unset($_SESSION['userId']);
			return new RedirectResponse('/benbol/');
		}
	}
	
}