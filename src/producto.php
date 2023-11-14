<?php
session_start();
// Verifica si $_SESSION["idUsuario"] ya está definida
if (!isset($_SESSION["idUsuario"])) {
    // Si no está definida, inicialízala en 0
    $_SESSION["idUsuario"] = 0;
}else{
    if ($_SESSION["idUsuario"] >0){
        $idUsuario = $_SESSION["idUsuario"]; // Reemplaza esto con el ID del usuario deseado
    }else{
        $idUsuario = $_SESSION["idUsuario"];
    }   
} 

if (!isset($_GET["idProd"])) {
    $idProducto = null;
} else {
    // Se obtiene id del producto pasado a traves de la url
    $idProducto = $_GET["idProd"];
}

include("db/conexionBD.php");
include("dao/productoDAO.php");
include("dao/productoCarritoDAO.php");
include("dao/carritoDAO.php");
include("dao/comentarioDAO.php");
$con = conectar();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/navbarStyle.css">
    <link rel="stylesheet" href="../css/comentarioStyle.css">
    <title>Detalles del Producto</title>
</head>
<body>
    <?php include("templates/navbar.php"); ?>
    <div class="container mt-4">
        <br>
        <br>
        <div class="row">
            <div class="col-md-6">
                <?php
                if ($idProducto === null){
                    echo 'PRODUCTO NO ENCONTRADO';
                }else{
                    $arProducto = obtenerProductoConId($idProducto, $con);
                    if (empty($arProducto)){
                        //VACIO
                    }else{
                        foreach ($arProducto as $producto) {
                            $precioProductoFormat = number_format($producto["precioProducto"], 0, ',', '.');
                            echo '<form action="handlers/insertProdCarrito.php" method="get">';
                            echo '<input type="hidden" name="idUsuario" value="' . $idUsuario . '">';
                            echo '<input type="hidden" name="idProducto" value="' . $producto["idProducto"] . '">';
                            echo '<img src="'.$producto["imagenProducto"].'" class="img-fluid" style="width: 400px; height: 400px; alt="' .$producto["nombreProducto"]. '">';
                            echo '</div>';
                            echo '<div class="col-md-6">';
                            echo '<h2>' .$producto["nombreProducto"]. '</h2>';
                            echo '<p>' .$producto["descripcionProducto"]. '</p>';
                            echo '<p><strong>Precio:</strong> $' .$precioProductoFormat. '</p>';
                            echo '<button type="submit" class="btn btn-primary">Añadir al Carrito</button>';
                            echo '</form>';  
                        }
                    }
                }    
                ?>
            </div>
        </div>
        <br><br>
        <?php include("templates/comentarios.php"); ?>    
    </div>
    <?php include("templates/footer.php"); ?>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>