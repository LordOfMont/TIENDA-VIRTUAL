<?php
namespace App\Controllers;
use Zend\Diactoros\Response\RedirectResponse;
use App\Models\Usuario;
use App\Models\Producto;
use App\Models\ProductoTalla;
class ProductController extends BaseController{
	public function AddProducto($request,$requiredlevel){
		$actual = Usuario::where('idUsuario',$_SESSION['userId'])->first();
		$level = $actual->tipo;
		if($requiredlevel==$level){
			$postData = $request->getParsedBody();
			//var_dump($postData);
			//echo '<br>';
			$product = new Producto();
			$product->codigo = $postData['codigo'];
			$product->nombre = $postData['producto'];
			$product->precio = $postData['precio'];
			$product->detalle = $postData['detalle'];
			$product->activo=1;
			$files = $request->getUploadedFiles();
			//var_dump($files);
            $imagenprod = $files['imagenprod'];
            if($imagenprod->getError() == UPLOAD_ERR_OK) {
                    $fileName = $imagenprod->getClientFilename();
                    $imagenprod->moveTo("uploads/$fileName");
            }
			$product->imagen = $fileName;
			$product->categoria = $postData['categoria'];
			$product->save();
			$product = Producto::where('codigo',$postData['codigo'])->first();
			$curtallas = $postData['talla'];
			foreach ($curtallas as $t) {
				$prodt = new ProductoTalla();
				$prodt->producto=$product->idProducto;
				$prodt->stockdisp=$postData['cantidad'];
				$prodt->stockcompra=0;
				$prodt->stockreserva=0;
				if($t=='4'){ $prodt->talla=1; }
				if($t=='6'){ $prodt->talla=2; }
				if($t=='8'){ $prodt->talla=3; }
				if($t=='10'){ $prodt->talla=4; }
				if($t=='12'){ $prodt->talla=5; }
				if($t=='14'){ $prodt->talla=6; }
				if($t=='S'){ $prodt->talla=7; }
				if($t=='M'){ $prodt->talla=8; }
				if($t=='L'){ $prodt->talla=9; }
				if($t=='XL'){ $prodt->talla=10; }
				$prodt->save();
			}
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
	public function EditProducto($request,$requiredlevel){
		$actual = Usuario::where('idUsuario',$_SESSION['userId'])->first();
		$level = $actual->tipo;
		if($requiredlevel==$level){
			$postData = $request->getParsedBody();
			$product = Producto::find($postData['id']);
			$product->nombre = $postData['producto'];
			$product->precio = $postData['precio'];
			$product->detalle = $postData['detalle'];
			$product->categoria = $postData['categoria'];
			$product->save();
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
	public function DeleteProducto($request,$requiredlevel){
		$actual = Usuario::where('idUsuario',$_SESSION['userId'])->first();
		$level = $actual->tipo;
		if($requiredlevel==$level){
			//var_dump($request);
			$enlace_actual = $_SERVER['REQUEST_URI'];
			$id=substr($enlace_actual,23);
			$id+=0;
			//var_dump($id);
    		$borrar=Producto::find($id);
    		//var_dump($borrar);
    		$borrar->activo=0;
    		//var_dump($borrar->nombre);
    		$borrar->save();
    		$productoslista = Producto::All();
			return new RedirectResponse('/benbol/adminproductos');
		}
		else{
			unset($_SESSION['userId']);
			return new RedirectResponse('/benbol/');
		}
	}
}