<?php
function insertarProductoCarrito($idUsuario, $idProducto, $con){
    $sql = "INSERT INTO carrito_has_producto (carrito_id, producto_id) 
            VALUES (
            (SELECT usuario_id FROM carrito WHERE usuario_id = $idUsuario),
            $idProducto);";
    try{
        if ($con->query($sql) === TRUE) {
            
        } else { 
            die("Error al insertar producto al carrito: " . $con->error);
        }
    }catch (mysqli_sql_exception $e) {
        if (strpos($e->getMessage(), "Duplicate entry") !== false) {
            // Manejo específico para "Duplicate entry"
        } else {
            // Otras excepciones de MySQLi
            echo "Error de MySQLi: " . $e->getMessage();
        }
    }catch(Exception $e){
    }    
}

function obtenerProductosCarrito($idUsuario, $con){
    $sql = "SELECT *
            FROM producto p
            JOIN carrito_has_producto chp ON p.id = chp.producto_id
            JOIN carrito c ON chp.carrito_id = c.usuario_id
            WHERE c.usuario_id = $idUsuario";

    $result = $con->query($sql);
    $productosCarrito = array(); // Un arreglo para almacenar los datos del carrito
     
    if ($result->num_rows > 0) {
        // Itera a través de los productos en el carrito
        while ($row = $result->fetch_assoc()) {
            $idProducto = $row["id"];
            $nombreProducto = $row["nombre"];
            $precioProducto = $row["precio"];
            $imagenProducto = $row["imagen"];

            // Agregar los datos del producto al arreglo del carrito
            $productosCarrito[] = array(
                "idProducto" => $idProducto,
                "nombreProducto" => $nombreProducto,
                "precioProducto" => $precioProducto,
                "imagenProducto" => $imagenProducto

            );
        }
    }
    // Retornar el arreglo del carrito 
    return $productosCarrito;   
}

function borrarProductoDelCarrito($idUsuario, $idProducto, $con) {
    // Asegúrate de que el usuario y el producto existen en la base de datos
    // Aquí podrías realizar comprobaciones adicionales según tus necesidades
    $sql = "DELETE FROM carrito_has_producto
            WHERE carrito_id = $idUsuario
            AND producto_id = $idProducto";

    try{ 
        if ($con->query($sql) === TRUE) {
            // El producto se eliminó con éxito del carrito
            return true;
        } else {
            // Ocurrió un error al eliminar el producto del carrito
            return false;
        }
    }catch(Exception $e){
        die("ERROR AL BORAR PRODUCTO DAO");
    }
    
}



