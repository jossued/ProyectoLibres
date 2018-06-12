<?php
session_start();
if (@!$_SESSION['usuario']) {
    header("Location:../../index.php");
} elseif ($_SESSION['tipo_usuario'] == 'EST') {
    //header("Location:index2.php");
    echo "eres estudiante";
} elseif ($_SESSION['tipo_usuario'] == 'ADM') {
    echo "eres estudiante";
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

        /* Set black background color, white text and some padding */
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
                        <li><a href="../../aplicacion/desconectar_sesion.php"><span class="glyphicon glyphicon-log-out"></span> Salir</a></li>
                    </ul>
                </div>
            </div>
        </nav>
        <!--Inicio de formulario -->
        <?php
        require_once '../clases_negocio/clase_conexion.php';
        require '../clases_negocio/funciones_oa_profesor.php';
        $id_objeto_aprendizaje = filter_input(INPUT_GET, 'id');
        //extract($_GET);
        $objeto_de_aprendizaje = obtener_oa_como_arreglo($id_objeto_aprendizaje);
        ?>

        <div class="container-fluid text-center">    
            <div class="row content">
                <div class="col-sm-6 col-sm-offset-3"> 
                    <h2>Objeto de aprendizaje </h2>
                    <?php
                    echo '<table border ="1|1" class="table table-condensed";>';
                    echo '<tr class="warning">';
                    echo '<td>Nombre</td>';
                    echo '<td>Descripción</td>';
                    echo '<td>Institucion</td>';
                    echo '<td>FechaCreacion</td>';
                    echo '<td>palabras clave</td>';
                    echo '<td>Comentarios</td>';
                    echo '</tr>';
                    echo '<tr class="success">';
                    echo '<td>' . $objeto_de_aprendizaje['nombre'] . '</td>';
                    echo '<td>' . $objeto_de_aprendizaje['descripcion'] . '</td>';
                    echo '<td>' . $objeto_de_aprendizaje['institucion'] . '</td>';
                    echo '<td>' . $objeto_de_aprendizaje['fechaCreacion'] . '</td>';
                    echo '<td>' . $objeto_de_aprendizaje['palabras_clave'] . '</td>';
                    echo '<td>' . obtener_nro_comentarios_oa($id_objeto_aprendizaje) . '</td>';
                    echo '</tr>';
                    echo '</table>'
                    ?>

                    <h2>Comentarios</h2>
                    <?php
                    echo '<table border ="1|1" class="table table-condensed";>';
                    echo '<tr class="warning">';
                    echo '<td>Comentario</td>';
                    echo '<td>Comentado por:</td>';
                    echo '</tr>';
                    //inicio carga de comentarios
                    $statement = "select * from comentario where id_objeto_aprendizaje=?";
                    $conexion = new Conexion();
                    $consulta = $conexion->prepare($statement);
                    $consulta->setFetchMode(PDO::FETCH_ASSOC);
                    $consulta->execute([$id_objeto_aprendizaje]);



                    if ($consulta->rowCount() != 0) {
                        while ($comentario = $consulta->fetch()) {
                            echo '<tr class="success">';
                            echo '<td>' . $comentario['contenido'] . '</td>';
                            if (obtener_tipo_usuario_con_id($comentario['idusuario']) == 'ADM') {
                                echo '<td>ADMINISTRADOR</td>';
                            } else {
                                $profesor = obtener_profesor_como_arreglo(obtener_id_profesor_con_id_usuario($comentario['idusuario']));
                                echo '<td>' . $profesor['nombres'] . ' ' . $profesor['apellidos'] . '</td>';
                            }
                            echo '</tr>';
                        }
                    }
                    echo '</table>';
                    ?>
                    

                </div>
            </div>
        </div>

        <footer class="container-fluid text-center">
            <p>Diseño y programación: Elsa Vasco, Edison Tamayo, José Criollo</p>
        </footer>
    </body>

</html>


