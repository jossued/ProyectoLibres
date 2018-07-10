<?php
session_start();
if (@!$_SESSION['usuario']) {
    header("Location:../../index2.php");
} elseif ($_SESSION['tipo_usuario'] == 'PRO') {
} elseif ($_SESSION['tipo_usuario'] == 'ADM') {

}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="es">
    <head>

        <meta charset="utf-8"></meta>
        <link rel="stylesheet" href="../../plugins/bootstrap/css/bootstrap.min.css"></link>
        <script type="text/javascript" src="../../plugins/bootstrap/js/jquery-3.3.1.js"></script>
        <script type="text/javascript" src="../../plugins/bootstrap/js/bootstrap.min.js"></script>
        <title>Proyecto SGOA</title>
    </head>
    <style>
        /* Remove the navbar's default margin-bottom and rounded borders */ 
        .navbar {
            margin-bottom: 0;
            border-radius: 0;
        }

        /* Set height of the grid so .sidenav can be 100% (adjust as needed) */
        .row.content {height: 390px}

        /* Set gray background color and 100% height */
        .sidenav {
            padding-top: 20px;
            background-color: #f1f1f1;
            height: 100%;
        }

        html{
            min-height: 100%;
            position: relative;
        }
        body{
            margin:0;
            margin-bottom: 40px;
        }
        /* Set black background color, white text and some padding */
        footer {
            background-color: #555;
            color: white;
            padding: 15px;
            position: absolute;
            bottom: 0;
            width: 100%;
        }

        /* On small screens, set height to 'auto' for sidenav and grid */
        @media screen and (max-width: 767px) {
            .sidenav {
                height: auto;
                padding: 15px;
            }
            .row.content {height:auto;} 
        }

        .table > tbody > tr > td {
            vertical-align: middle;
        }

    </style>


    <body>
        <nav class="navbar navbar-inverse">
            <div class="container-fluid">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>                        
                    </button>
                    <a class="navbar-brand" href="#">Bienvenid@: <strong><?php echo $_SESSION['usuario'] ?></strong></a>
                </div>
                <div class="collapse navbar-collapse" id="myNavbar">
                    <ul class="nav navbar-nav">
                        <li class="active"><a href="../modulos_estudiante/est_buscar.php">Buscar</a></li>
                        <li><a href="../modulos_estudiante/est_herramientas.php">Herramientas</a></li>
                    </ul>
                    <ul class="nav navbar-nav navbar-right">
                        <li><a href="../desconectar_sesion.php"><span class="glyphicon glyphicon-log-out"></span> Salir</a></li>
                    </ul>
                </div>
            </div>
        </nav>
        <!-- Inicio formulario de búsqueda -->

        <!-- presentacion de objetos de aprendizaje-->
        <div class="container-fluid text-center">    
            <div class="row content">
                <!-- --------------------------------------------- -->
                <div class="col-sm-12 text-center"> 
                        <h2> Objetos de aprendizaje</h2>
                        <form action="../modulos_estudiante/est_ejecutar_buscar.php" method="post" enctype="multipart/form-data">
                            <div class="col-md-3">

                            </div>
                            <div class="col-md-3 text-left ">
                                <select class= "form-control" name="tipo_criterio" dir="ltr" required>
                                    <option value="">Filtrar por:</option>
                                    <option value="autor">autor</option>
                                    <option value="nombre">nombre</option>
                                    <option value="descripcion">descripcion</option>
                                    <option value="institucion">institucion</option>
                                    <option value="palabras_clave">palabra clave</option>
                                </select></br>
                            </div>
                            <div class="col-md-3 text-center">
                                <input type="text" class="form-control" id="criterio_busqueda" placeholder="Buscar...." name="criterio_busqueda" required></br>
                            </div>
                            <div class="col-md-3 text-left">
                                <button id="registrar" type="submit" class="btn btn-danger">Buscar</button>
                                </br></br>
                            </div>
                        </form>

                    <?php
                    require_once '../clases_negocio/clase_conexion.php';
                    require '../clases_negocio/funciones_oa_estudiante.php';
                    require '../clases_negocio/funciones_oa_profesor.php';
                    $statement = ("select * from objeto_aprendizaje");
                    $conexion = new Conexion();
                    $consulta = $conexion->prepare($statement);
                    $consulta->setFetchMode(PDO::FETCH_ASSOC);
                    $consulta->execute();

                    echo '<table border ="1|1" class="table table-condensed";>';
                    echo '<tr class="warning">';
                    echo '<td>Nombre</td>';
                    echo '<td>Descripción</td>';
                    echo '<td>Institucion</td>';
                    echo '<td>FechaCreacion</td>';
                    echo '<td>palabras clave</td>';
                    echo '<td>Tamaño</td>';
                    echo '<td>Autor</td>';
                    echo '<td>Comentarios</td>';
                    echo "</tr>";

                    if ($consulta->rowCount() != 0) {
                        while ($row = $consulta->fetch()) {
                            echo '<tr class="success">';
                            echo '<td>' . $row['nombre'] . '</td>';
                            echo '<td>' . $row['descripcion'] . '</td>';
                            echo '<td>' . $row['institucion'] . '</td>';
                            echo '<td>' . $row['fechaCreacion'] . '</td>';
                            echo '<td>' . $row['palabras_clave'] . '</td>';
                            echo '<td>' . number_format($row['tamanio'] / 1e6, 2, '.', '') . ' MB' . '</td>';
                           
                            $estudiante = obtener_estudiante_como_arreglo(obtener_id_estudiante_con_id_usuario($row['id_usuario']));
                            echo '<td>' . $estudiante['nombres'] . ' ' . $estudiante['apellidos'] .     '</td>';
                            echo '<td><a href="est_comentarios.php?id='.$row['idobjeto_aprendizaje'].'">'. obtener_nro_comentarios_oa($row['idobjeto_aprendizaje']) .       '</a></td>';                          
                            echo '<td><a href="' . $row['ruta'] . '">Descargar</a></td>';
                            echo "<td><a href='#' onmouseover=\"hacer_hover('".$row['ruta']."');\"><span class='glyphicon glyphicon-eye-open'></a></td>";
                            
                        }
                    }

                    echo '</table>';
                    $conexion = null;
                    ?>

            <script>
                function hacer_hover($x)
                {
                    myPopup = window.open('../modulos_administrador/previsualizar.php?vs='+$x,'popupWindow','width=640,height=480');
                    myPopup.opener = self;
                }
            </script>

                </div>
            </div>
        </div>
        <footer class="label-default container-fluid text-center">
            <p class="copyright small">Copyright &copy; Jaime Crespin, Jossué Dután, Alexis Maldonado 2018</p>
        </footer>
    </body>

</html>

