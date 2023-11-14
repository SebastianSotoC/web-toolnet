<?php
session_start();
$idProducto = "";
$nombreProducto = "";
$precioProducto = "";
$totalCarrito = "Vacio";
if (!isset($_SESSION["idUsuario"])) {
    $_SESSION["idUsuario"] = 0;
}else{
    if ($_SESSION["idUsuario"] >0){
        $idUsuario = $_SESSION["idUsuario"]; // Reemplaza esto con el ID del usuario deseado
    }else{
        $idUsuario = $_SESSION["idUsuario"];
    } 
}
include("db/conexionBD.php");
//include("handlers/handlerProductoCarrito.php");
include("dao/productoCarritoDAO.php");
//include("handlers/handlerCarrito.php");      
include("dao/carritoDAO.php");
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <title>Carro de Compras</title>
</head>

<body>
    <?php include("templates/navbar.php"); ?>
    <div class="container mt-4">
        <h2>Carrito de Compras</h2>
        <br>
        <table class="table">
            <thead>
            <!-- SE OBTIENE INFO DE LOS PRODUCTOS -->
            <?php
            if ($idUsuario === 0){
                //MUESTRA EL CARRITO TEMPORAL CUANDO EL USUARIO NO ESTA LOGEADO
                if (isset($_SESSION['carrito']) && !empty($_SESSION['carrito'])) {
                    $arProductosCarritoTemporal = $_SESSION['carrito'];
                    foreach($arProductosCarritoTemporal as $productoCarrito){
                        $precioProductoFormat = number_format($productoCarrito["precioProducto"], 0, ',', '.');
                        echo '<tr>';
                        echo '<div class="card" style="width: 300px; margin-bottom: 50px;">';
                        echo '<div class="d-flex">';
                        echo '<img src="'.$productoCarrito["imagenProducto"].'" class="card-img-top" alt="' . $productoCarrito["nombreProducto"] . '" style="max-width: 150px; height: 150px; object-fit: cover;">';
                        echo '<div class="card-body">';
                        echo '<h5 class="card-title">' . $productoCarrito["nombreProducto"] . '</h5>';
                        echo '<p class="card-text">Precio: $' . $precioProductoFormat . '</p>';
                        echo '<div class="input-group">';
                        echo '<button class="btn btn-outline-secondary" type="button" id="restarBtn">-</button>';
                        echo '<input type="text" class="form-control" id="cantidadProducto" value="1" readonly>';
                        echo '<button class="btn btn-outline-secondary" type="button" id="sumarBtn">+</button>';
                        echo '</div>';
                        echo '</div>';
                        echo '</div>';
                        echo '<a href="handlers/borrarProducto.php?idProd='.$productoCarrito["idProducto"].'" class="btn btn-danger mt-2 btn-block"><i class="fas fa-trash"></i> Eliminar</a>';
                        echo '</div>';
                        echo '<tr>';

                        echo '<tr>';
                        echo '<td><img src="'.$productoCarrito["imagenProducto"].'" alt="' . $productoCarrito["nombreProducto"] . '" style="max-width: 150px;"></td>';
                        echo '<td> ' . $productoCarrito["nombreProducto"] . ' </td>';
                        echo '<td>$' . $precioProductoFormat . '</td>';
                        echo '<td> <a class="btn btn-danger" href="handlers/borrarProducto.php?idProd='.$productoCarrito["idProducto"].'"><i class="fas fa-trash"></i></a></td>';
                        echo '</tr>';
                    }
                }else{
                    echo'<br><br><br><br>';
                    echo '<div class="info-carro-empty text-center">';
                    echo '<h2><span class="icon-bag"></span></h2>';
                    echo '<h3>Tu Carro está vacío</h3>';
                    echo '<h4>¿Quieres agregar productos?</h4>';
                    echo '<br>';
                    echo '<div class="row justify-content-center">';
                    echo '<div class="col-md-4"></div>';
                    echo '<div class="col-md-4">';
                    echo '<a href="inicio.php" class="btn btn-primary btn-continuar-total-carro">';
                    echo 'Continuar';
                    echo '</a>';
                    echo '</div>';
                    echo '<div class="col-md-4"></div>';
                    echo '</div>';
                    echo '</div>';
                } 
            }else{           
                $arProductosCarrito = obtenerProductosCarrito($idUsuario, $con);//include("dao/productoCarritoDAO.php");    
                if (empty($arProductosCarrito)) {
                    echo'<br><br><br><br>';
                    echo '<div class="info-carro-empty text-center">';
                    echo '<h2><span class="icon-bag"></span></h2>';
                    echo '<h3>Tu Carro está vacío</h3>';
                    echo '<h4>¿Quieres agregar productos?</h4>';
                    echo '<br>';
                    echo '<div class="row justify-content-center">';
                    echo '<div class="col-md-4"></div>';
                    echo '<div class="col-md-4">';
                    echo '<a href="inicio.php" class="btn btn-primary btn-continuar-total-carro">';
                    echo 'Continuar';
                    echo '</a>';
                    echo '</div>';
                    echo '<div class="col-md-4"></div>';
                    echo '</div>';
                    echo '</div>';
                } else {
                    foreach ($arProductosCarrito as $productoCarrito) {
                        $precioProductoFormat = number_format($productoCarrito["precioProducto"], 0, ',', '.');
                        echo '<tr>';
                        echo '<td><img src="'.$productoCarrito["imagenProducto"].'" alt="' . $productoCarrito["nombreProducto"] . '" style="max-width: 150px;"></td>';
                        echo '<td> ' . $productoCarrito["nombreProducto"] . ' </td>';
                        echo '<td>$' . $precioProductoFormat . '</td>';
                        echo '<td> <a class="btn btn-danger" href="handlers/borrarProducto.php?idProd='.$productoCarrito["idProducto"].'"><i class="fas fa-trash"></i></a></td>';
                        echo '</tr>';
                    }               
                }               
            }
            ?>              
            </tbody>
        </table>
        <div class="text-right">
            <!-- SE MUESTRA EL TOTAL DEL CARRITO SIEMPRE Y CUANDO HAYAN PRODUCTOS -->
            <?php
            if ($idUsuario === 0){
                if (isset($_SESSION['carrito']) && !empty($_SESSION['carrito'])){
                    $arProductosCarritoTemporal = $_SESSION['carrito'];
                    $totalCarrito = 0;
                    foreach($arProductosCarritoTemporal as $productoCarrito){
                        $totalCarrito += $productoCarrito["precioProducto"];
                    }
                   if ($totalCarrito != 0){ 
                        $precioProductoFormat = number_format($totalCarrito, 0, ',', '.');
                       echo '<p>Total: $' .$precioProductoFormat. '</p>';
                       echo '<a href="login.php?logCheck=1" class="btn btn-primary btn-continuar-total-carro">';
                       mysqli_close($con);
                       echo 'Continuar Compra';
                       echo '</a>';
                   }
                }
            }else{
               if (empty($arProductosCarrito)) {
                   //NADA
               }else{
                   $totalCarrito = obtenerTotalCarrito($idUsuario, $con); //include("dao/carritoDAO.php");
                   if ($totalCarrito != null){ 
                       echo '<p>Total: $' .$totalCarrito. '</p>';
                       mysqli_close($con);
                       echo '<a href="pago.php" class="btn btn-primary btn-continuar-total-carro">';
                       echo 'Continuar Compra';
                       echo '</a>';
                   }
               }
            }     
            ?>
            <br>
            <br>
        </div>
    </div>
    <?php include("templates/footer.php"); ?>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
<?php 
if (!isset($_SESSION["cuentaLogeada"])) {
    // Si no está definida, inicialízala en 0
    $_SESSION["cuentaLogeada"] = null;
}else{
    if($_SESSION["cuentaLogeada"]){
        echo '<script>alert("Has iniciado sesion correctamente.");</script>';    
        $_SESSION["cuentaLogeada"] = null;       
    }
}
?>


