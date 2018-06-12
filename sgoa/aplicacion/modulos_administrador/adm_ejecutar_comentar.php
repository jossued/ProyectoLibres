<?php
session_start();
require '../clases_negocio/funciones_oa_profesor.php';

$id_objeto_aprendizaje = filter_input(INPUT_POST, 'id_objeto_aprendizaje');
$contenido = filter_input(INPUT_POST, 'contenido');
if (insertar_comentario($contenido, $_SESSION['id'], $id_objeto_aprendizaje)) {

    echo '<script charset="UTF-8">alert("Su comentario se inserto correctamente")</script> ';
    echo "<script>location.href='adm_buscar.php'</script>";
} else {
    echo '<script charset="UTF-8">alert("No se pudo insertar su comentario. ")</script> ';
    echo "<script>location.href='adm_buscar.php'</script>";
}
?>
