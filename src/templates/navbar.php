<nav class="navbar navbar-expand-lg navbar-light" style="background-color: #6c757d;">
    <div class="container">    
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>    
        <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
            <form class="form-inline" id="search-form">
                <input class="form-control mr-sm-2" type="search" placeholder="Buscar" aria-label="Buscar" id="inputBuscar" name="inputBuscar"value="">
                <button class="btn btn-outline-light" type="submit">Buscar</button>
            </form>
            <script>
            document.getElementById("search-form").addEventListener("submit", function (e) {
                e.preventDefault(); // Evita que el formulario se envíe automáticamente.
            
                var searchTerm = document.getElementById("inputBuscar").value;
                // Escapar el término de búsqueda en caso de espacios u otros caracteres especiales.
                searchTerm = encodeURIComponent(searchTerm);
            
                // Actualizar la URL con el término de búsqueda
                window.location.href = "inicio.php?search=" + searchTerm;
            });
            </script>
            <div class="navLinks" id="navbaNav">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="inicio.php">Productos</a>
                    </li>
                    <li class="nav-item">
                        <div class="menu-desplegable">
                          <a class="nav-link" >Iniciar sesión</a>
                          <div class="opciones">
                            <?php
                            if ($_SESSION["idUsuario"] > 0){
                                echo '<a href="javascript:void(0);"style="color: white;" id="cerrarSesion">Cerrar Sesión</a>';
                                echo '<a href="registro.php" style="color: white;">Regístrate</a>';
                            }else{
                                if (isset($_SESSION['carrito']) && !empty($_SESSION['carrito'])){
                                    echo '<a href="login.php?logCheck=1" style="color: white;">Inicia Sesión</a>';
                                    echo '<a href="registro.php" style="color: white;">Regístrate</a>';
                                }else{
                                    echo '<a href="login.php" style="color: white;">Inicia Sesión</a>';
                                    echo '<a href="registro.php" style="color: white;">Regístrate</a>';
                                }
                            }
                            ?>
                          </div>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center" href="carrito.php">Carrito
                        <?php               
                        $idUsuario = $_SESSION["idUsuario"];
                        if ($idUsuario === 0){
                            //SESION NO INICIADA
                            if (isset($_SESSION['carrito']) && !empty($_SESSION['carrito'])){
                                $arCarrito = $_SESSION['carrito'];
                                $cantidadProductos = 0;
                                foreach($arCarrito as $producto){
                                    $cantidadProductos += 1;
                                }
                                if($cantidadProductos > 0){
                                    echo '<span class="badge rounded-pill bg-info" style="margin-top: 4.7%; margin-left: 3%;">';
                                    echo ''.$cantidadProductos.'';
                                    echo '</span>'; 
                                }
                            }
                        }else{
                            //SESION INICIADA
                            $cantidadProductos = obtenerCantidadProdCarrito($idUsuario, $con);
                            if ($cantidadProductos > 0){
                                echo '<span class="badge rounded-pill bg-info" style="margin-top: 4.7%; margin-left: 3%;">';
                                echo ''.$cantidadProductos.'';
                                echo '</span>';  
                            }
                        }
                        ?>
                        </a>               
                    </li>         
                </ul>
            </div>
        </div>
    </div>
</nav>
<script>
document.getElementById("cerrarSesion").onclick = function() {
 
    // Preguntar al usuario si está seguro de cerrar la sesión
    var confirmar = confirm("¿Seguro que deseas cerrar la sesión?");
    
    // Si el usuario confirma, recarga la pagina
    if (confirmar) {
        // Usar AJAX para cerrar la sesión
        var xhr = new XMLHttpRequest();
        xhr.open('GET', 'handlers/logout.php', true);
        xhr.send();
        
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                // Recargar la página después de cerrar la sesión
                location.reload();
            }
        };
    }
    // Si el usuario cancela, no se realizará la redirección.
};
</script>


