<?php
include("../db/conexionBD.php");
include("../dao/productoCarritoDAO.php");
include("../dao/productoDAO.php");
include("../dao/carritoDAO.php");
$con = conectar();
session_start(); //sesion

if (isset($_GET['idUsuario'], $_GET['idProducto'])) {
    $idUsuario = $_GET['idUsuario'];
    $idProducto = $_GET['idProducto'];

    if($idUsuario == 0){
        if (!isset($_SESSION['carrito'])) {
            //SE CREA CARRITO TEMPORAL YA QUE EL USUARIO NO ESTA LOGEADO
            $_SESSION['carrito'] = array();
        }
        $arProductoEncontrado = obtenerProductoConId($idProducto, $con);
        if(empty($arProductoEncontrado)){
            //PRODUCTO NO ENCONTRADO
        }else{
            foreach ($arProductoEncontrado as $producto) {
                $arProductosCarrito = array(
                    "idProducto" => $producto["idProducto"],
                    "nombreProducto" => $producto["nombreProducto"],
                    "precioProducto" => $producto["precioProducto"],
                    "imagenProducto" => $producto["imagenProducto"]
                );
            }
            $_SESSION['carrito'][] = $arProductosCarrito;
        }       
    }else{
        // Realiza la inserción en el carrito utilizando $idUsuario y $idProducto
        insertarProductoCarrito($idUsuario, $idProducto, $con);
        actualizarCarrito($idUsuario, $con);
    }
    
} else {
    // Maneja el caso en el que no se proporcionaron los IDs
    echo "Los IDs de usuario y producto no se proporcionaron.";
}

mysqli_close($con);
header("Location: ../producto.php?idProd=" . $idProducto);
exit(); // Asegúrate de que el script se detenga después de la redirección
?>
