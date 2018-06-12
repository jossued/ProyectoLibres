<?php

require_once 'funciones_oa_profesor.php';

function act_des_usuario($id_usuario, $activo) {
    $conexion = new Conexion();

    $statement = 'UPDATE  usuario SET activo ="' . $activo . '" WHERE idUsuario=' . $id_usuario;
    //echo $statement;
    $consulta = $conexion->prepare($statement);
    $consulta->execute();
}

function eliminar_usuario($id_usuario) {
    $statement_del = "DELETE FROM usuario WHERE idUsuario=?";
    $conexion_del = new Conexion();
    $consulta_del = $conexion_del->prepare($statement_del);
    if ($consulta_del->execute(array($id_usuario))) {
        return true;
    } else {
        return false;
    }
}

function eliminar_objetos_aprendizaje_asociados_a_id($id_usuario) {
    $conexion = new Conexion();
    $statement = 'select * from objeto_aprendizaje where id_usuario=?';
    $consulta = $conexion->prepare($statement);
    $consulta->setFetchMode(PDO::FETCH_ASSOC);
    $consulta->execute([$id_usuario]);
    $ids_oas=[];
    if ($consulta->rowCount() != 0) {
        while ($row = $consulta->fetch()) {
             array_push($ids_oas, $row['idobjeto_aprendizaje']);
        }
    }
    if(sizeof($ids_oas)!=0){
        foreach($ids_oas as $id){
            eliminar_objeto_aprendizaje($id);
        }
    }
    
    
}

function enviar_mail($mail, $usuario, $contrasenia) {
    $titulo = 'Credenciales de acceso al sistema SGOA';
    $cabeceras = 'MIME-Version: 1.0' . "\r\n";
    $cabeceras .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
    
    $mensaje = '<h3 align="center">Usuario:' . $usuario . '</h3><br><br>
            <h3 align="center">Password:' . $contrasenia . '</h3><br><br>';

    $tipocorreos = explode('@', $mail);

    if ($tipocorreos['1'] == 'gmail.com') {
        // Cabeceras adicionales para gmail
        $cabeceras .= "From: Administrador" . "\r\n";
    } else {
        // Cabeceras adicionales para hotmail y demas
        $cabeceras .= 'From: administrador <eprinss_02@hotmail.com>' . "\r\n";
    }
    mail($mail, $titulo, $mensaje, $cabeceras);
}

?>