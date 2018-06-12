<?php

require_once 'clase_conexion.php';
function obtener_tipo_usuario_con_id($id_usuario){
    $conexion = new Conexion();
    $statement = 'select * from usuario where idUsuario=?';
    $consulta = $conexion->prepare($statement);
    $consulta->setFetchMode(PDO::FETCH_ASSOC);
    $consulta->execute([$id_usuario]);
    if ($consulta->rowCount() != 0) {
        $fila = $consulta->fetch();
        $tipo_usuario = $fila['tipo_usuario'];
    }
    if (isset($tipo_usuario)) {
        return $tipo_usuario;
    } else {
        return null;
    }
}

function obtener_id_profesor_con_id_usuario($id_usuario) {
    $conexion = new Conexion();
    $statement = 'select pro.idprofesor from usuario as us, profesor as pro where us.idUsuario=pro.id_usuario and us.idUsuario=?';
    $consulta = $conexion->prepare($statement);
    $consulta->setFetchMode(PDO::FETCH_ASSOC);
    $consulta->execute([$id_usuario]);
    if ($consulta->rowCount() != 0) {
        $fila = $consulta->fetch();
        $id_profesor = $fila['idprofesor'];
    }
    if (isset($id_profesor)) {
        return $id_profesor;
    } else {
        return null;
    }
}

function obtener_profesor_como_arreglo($id_profesor) {
    $conexion = new Conexion();
    $statement = 'select * from profesor where idprofesor=?';
    $consulta = $conexion->prepare($statement);
    $consulta->setFetchMode(PDO::FETCH_ASSOC);
    $consulta->execute([$id_profesor]);
    if ($consulta->rowCount() != 0) {
        $fila = $consulta->fetch();
    }
    if (isset($fila)) {
        return $fila;
    } else {
        return null;
    }
}

function obtener_nro_comentarios_oa($id_objeto_aprendizaje) {
    $conexion = new Conexion();
    $statement = 'select count(*) as nro_comentarios from objeto_aprendizaje as oa, comentario as c where oa.idobjeto_aprendizaje=c.id_objeto_aprendizaje and c.id_objeto_aprendizaje=?';
    $consulta = $conexion->prepare($statement);
    $consulta->setFetchMode(PDO::FETCH_ASSOC);
    $consulta->execute([$id_objeto_aprendizaje]);
    if ($consulta->rowCount() != 0) {
        $fila = $consulta->fetch();
        $id_profesor = $fila['nro_comentarios'];
    }
    if (isset($id_profesor)) {
        return $id_profesor;
    } else {
        return null;
    }
}

function obtener_oa_como_arreglo($id_objeto_aprendizaje) {
    $conexion = new Conexion();
    $statement = 'select * from objeto_aprendizaje where idobjeto_aprendizaje=?';
    $consulta = $conexion->prepare($statement);
    $consulta->setFetchMode(PDO::FETCH_ASSOC);
    $consulta->execute([$id_objeto_aprendizaje]);
    if ($consulta->rowCount() != 0) {
        $fila = $consulta->fetch();
    }
    if (isset($fila)) {
        return $fila;
    } else {
        return null;
    }
}

function actualizar_oa($id_objeto_aprendizaje, $nombre, $descripcion, $institucion, $palabras_clave) {
    //actualiazcion en storage
    $nombre_original = obtener_oa_como_arreglo($id_objeto_aprendizaje)['nombre'];
    $ruta_inicial = obtener_oa_como_arreglo($id_objeto_aprendizaje)['ruta'];
    if ($nombre_original != $nombre) {
        $nueva_ruta = '../../storage/' . urlencode($nombre) . '.zip';
        rename($ruta_inicial, $nueva_ruta);
        $statement_del = 'UPDATE objeto_aprendizaje set nombre="' . $nombre . '", descripcion="' . $descripcion . '", institucion="' . $institucion . '",palabras_clave="' . $palabras_clave . '", ruta="' . $nueva_ruta . '" where idobjeto_aprendizaje=' . $id_objeto_aprendizaje;
    } else {
        $statement_del = 'UPDATE objeto_aprendizaje set nombre="' . $nombre . '", descripcion="' . $descripcion . '", institucion="' . $institucion . '",palabras_clave="' . $palabras_clave . '" where idobjeto_aprendizaje=' . $id_objeto_aprendizaje;
    }
    //echo $statement_del;
    $conexion_del = new Conexion();
    $consulta_del = $conexion_del->prepare($statement_del);
    $consulta_del->execute();
}

function eliminar_objeto_aprendizaje($id_objeto_aprendizaje) {
    //eliminacion de storage
    $ruta = obtener_oa_como_arreglo($id_objeto_aprendizaje)['ruta'];
    unlink($ruta);
    //eliminacion de base de datos
    $statement_del = "DELETE FROM objeto_aprendizaje WHERE idobjeto_aprendizaje=?";
    $conexion_del = new Conexion();
    $consulta_del = $conexion_del->prepare($statement_del);
    $consulta_del->execute(array($id_objeto_aprendizaje));
}

function obtener_lista_de_oas() {
    $conexion = new Conexion();
    $statement = 'select nombre from objeto_aprendizaje';
    $consulta = $conexion->prepare($statement);
    $consulta->setFetchMode(PDO::FETCH_ASSOC);
    $consulta->execute();
    $arreglo = [];
    if ($consulta->rowCount() != 0) {
        while ($row = $consulta->fetch()) {
            array_push($arreglo, $row['nombre']);
        }
    }
    return implode(',', $arreglo);
}

//inicio funciones de comentario
function insertar_comentario($contenido, $idusuario, $id_objeto_aprendizaje) {
    $conexion = new Conexion();
    $statement = 'INSERT INTO comentario (contenido,idusuario,id_objeto_aprendizaje) VALUES (?, ?, ?)';
    $consulta = $conexion->prepare($statement);
    if ($consulta->execute(array($contenido, $idusuario, $id_objeto_aprendizaje))) {
        return true;
    } else {
        return false;
    }
}

//incio funciones registro de estudiante 
function obtener_lista_de_usuarios() {
    $conexion = new Conexion();
    $statement = 'select usuario from usuario';
    $consulta = $conexion->prepare($statement);
    $consulta->setFetchMode(PDO::FETCH_ASSOC);
    $consulta->execute();
    $arreglo = [];
    if ($consulta->rowCount() != 0) {
        while ($row = $consulta->fetch()) {
            array_push($arreglo, $row['usuario']);
        }
    }
    return implode(',', $arreglo);
}

function insertar_usuario($usuario, $contrasenia, $tipo_usuario, $esta_activo) {
    $conexion = new Conexion();
    $statement = 'INSERT INTO usuario (usuario,contrasenia,tipo_usuario, activo) VALUES (?,?,?, ?)';
    $consulta = $conexion->prepare($statement);
    if ($consulta->execute(array($usuario, $contrasenia, $tipo_usuario, $esta_activo))) {
        return true;
    } else {
        return false;
    }
}

function recuperar_id_usuario_por_nombre($usuario) {
    $conexion = new Conexion();
    $statement = 'select idUsuario from usuario where usuario = "' . $usuario . '"';
    $consulta = $conexion->prepare($statement);
    $consulta->setFetchMode(PDO::FETCH_ASSOC);
    $consulta->execute();
    if ($consulta->rowCount() != 0) {
        $fila = $consulta->fetch();
        $id_usuario = $fila['idUsuario'];
    }
    if (isset($id_usuario)) {
        return $id_usuario;
    } else {
        return null;
    }
}

function insertar_estudiante($ci, $nombres, $apellidos, $carrera, $facultad, $mail, $id_usuario) {
    $conexion = new Conexion();
    $statement = 'INSERT INTO estudiante (ci,nombres,apellidos, carrera, id_facultad, mail, id_usuario) VALUES (?,?,?,?,?,?,?)';
    $consulta = $conexion->prepare($statement);
    if ($consulta->execute(array($ci, $nombres, $apellidos, $carrera, $facultad, $mail, $id_usuario))) {
        return true;
    } else {
        return false;
    }
}

function insertar_profesor($ci, $nombres, $apellidos, $id_departamento, $id_facultad, $mail, $id_usuario) {
    $conexion = new Conexion();
    $statement = 'INSERT INTO profesor (ci,nombres,apellidos,id_departamento, id_facultad, mail, id_usuario) VALUES (?,?,?,?,?,?,?)';
    $consulta = $conexion->prepare($statement);
    if ($consulta->execute(array($ci, $nombres, $apellidos, $id_departamento, $id_facultad, $mail, $id_usuario))) {
        return true;
    } else {
        return false;
    }
}

function generar_cadena_aleatoria($length = 5) {
    $caracteres = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $longitud_caracteres = strlen($caracteres);
    $cadena_aleatoria = '';
    for ($i = 0; $i < $length; $i++) {
        $cadena_aleatoria .= $caracteres[rand(0, $longitud_caracteres - 1)];
    }
    return $cadena_aleatoria;
} 

function generar_usuario_profesor($nombre, $apellido){
    $nombre1=explode(' ',$nombre)[0];
    $apellido1=explode(' ',$apellido)[0];
    $existe_usuario=true;
    $contador=0;
    while ($existe_usuario){
        if(obtener_nro_usuarios_con_usuario($nombre1.$apellido1.$contador)!=0){
            $contador=$contador+1;
            $existe_usuario=true;
        }else{
            $usuario=$nombre1.$apellido1.$contador;
            $existe_usuario=false;
        }
    }
    if(isset($usuario)){
        return $usuario;
    } else {
        return null;
        
    }
}

function obtener_nro_usuarios_con_usuario($usuario) {
    $conexion = new Conexion();
    $statement = 'select count(*) as nro_usuarios from usuario where usuario=?';
    $consulta = $conexion->prepare($statement);
    $consulta->setFetchMode(PDO::FETCH_ASSOC);
    $consulta->execute([$usuario]);
    if ($consulta->rowCount() != 0) {
        $fila = $consulta->fetch();
        $nro_usuarios = $fila['nro_usuarios'];
    }
    if (isset($nro_usuarios)) {
        return $nro_usuarios;
    } else {
        return null;
    }
}

?>