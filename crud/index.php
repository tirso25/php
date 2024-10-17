<?php
require_once "Empleado.php";
require_once "conexdb.php";

function validarEntrada($data){
    return htmlspecialchars(stripslashes((trim($data))));
}

$emp_no = $apellido = $dir = $oficio = $dept_no = $salario = $comision = $fecha_alt = "";
$boton="alta";
$mensaje="Crear empleado nuevo";

if ($_SERVER['REQUEST_METHOD'] == "POST"){

    if (isset($_POST['eliminar'])){
        $emp_no = validarEntrada($_POST['emp_no']);
        $query = "DELETE FROM empleados WHERE emp_no = :emp_no";
        try{
            $resultado = $conexdb->prepare($query);
            $resultado->execute([":emp_no" => $emp_no]);
        }catch(PDOException $ex){
            echo "Error al eliminar: " . $ex->getMessage();
        }
        
    }
    elseif (isset($_POST['alta'])){

        $query = "SELECT max(emp_no) FROM empleados";
        try{
            $resultado = $conexdb->prepare($query);
            $resultado->execute();
            $ult_no = $resultado->fetch()[0];
            $emp_no = ++$ult_no;

            $apellido = validarEntrada($_POST['apellido']);
            $dir = validarEntrada($_POST['dir']);
            $oficio = validarEntrada($_POST['oficio']);
            $dept_no = validarEntrada($_POST['dept_no']);
            $salario = validarEntrada($_POST['salario']);
            $comision = validarEntrada($_POST['comision']);
            $fecha_alt = validarEntrada($_POST['fecha_alt']);

            $query = "INSERT INTO empleados (emp_no, apellido, dir, oficio, dept_no, salario, comision, fecha_alt)
            VALUES (:emp_no, :apellido, :dir, :oficio, :dept_no, :salario, :comision, :fecha_alt)";

            $resultado = $conexdb->prepare($query);
            $resultado->execute([":emp_no" => $emp_no,
                                ":apellido" => $apellido, 
                                ":dir" => $dir, 
                                ":oficio" => $oficio, 
                                ":dept_no" => $dept_no, 
                                ":salario" => $salario, 
                                ":comision" => $comision, 
                                ":fecha_alt" => $fecha_alt]);


        }catch(PDOException $ex){
            echo "Error al crear registro: " . $ex->getMessage();
        }
    }
    elseif(isset($_POST['actualizar'])){
        $emp_no = validarEntrada($_POST['emp_no']);
        $query = "SELECT emp_no, apellido, dir, oficio, dept_no, salario, comision, fecha_alt 
        FROM empleados WHERE emp_no = :emp_no";

        try{
            $resultado = $conexdb->prepare($query);
            $resultado->execute([":emp_no" => $emp_no]);
            $resultado->setFetchMode(PDO::FETCH_ASSOC);
            $empleado = $resultado->fetch();
            extract($empleado);
            $mensaje="Introduzca nuevos datos";
            $boton="envact";
        }catch(PDOException $ex){
            echo "Error en selección de empleados: " . $ex->getMessage();
        }
    }
    elseif(isset($_POST['envact'])){

        $emp_no = validarEntrada($_POST["emp_no"]);
        $apellido = validarEntrada($_POST['apellido']);
        $dir = validarEntrada($_POST['dir']);
        $oficio = validarEntrada($_POST['oficio']);
        $dept_no = validarEntrada($_POST['dept_no']);
        $salario = validarEntrada($_POST['salario']);
        $comision = validarEntrada($_POST['comision']);
        $fecha_alt = validarEntrada($_POST['fecha_alt']);

        $query = "UPDATE empleados SET apellido = :apellido, dir = :dir, 
                oficio = :oficio, dept_no = :dept_no, salario = :salario, 
                comision = :comision, fecha_alt = :fecha_alt WHERE emp_no = :emp_no";
        try{
            $resultado = $conexdb->prepare($query);
            $resultado->bindValue(":emp_no", $emp_no);
            $resultado->bindValue(":apellido", $apellido);
            $resultado->bindValue(":dir", $dir);
            $resultado->bindValue(":oficio", $oficio); 
            $resultado->bindValue(":dept_no", $dept_no); 
            $resultado->bindValue(":salario", $salario);
            $resultado->bindValue(":comision", $comision); 
            $resultado->bindValue(":fecha_alt", $fecha_alt);
            $resultado->execute();

            $emp_no = $apellido = $dir = $oficio = $dept_no = $salario = $comision = $fecha_alt = "";
            $boton="alta";
            $mensaje="Crear empleado nuevo";
        }catch(PDOException $ex){
            echo "Error al actualizar: " . $ex->getMessage();
        }
    }

}



$query = "SELECT emp_no, apellido, dir, oficio, dept_no, salario, comision, fecha_alt FROM empleados";

try{
    $resultado = $conexdb->prepare($query);
    $resultado->execute();
    $empleados = $resultado->fetchAll(PDO::FETCH_CLASS, 'Empleado');
}catch(PDOException $ex){
    echo "Error en selección de empleados: " . $ex->getMessage();
}



?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <table border="1">
        <tr>
            <th colspan="10">LISTADO EMPLEADOS</th>
        </tr>
        <tr>
            <td>Número</td>
            <td>Apellido</td>
            <td>Dirección</td>
            <td>Oficio</td>
            <td>Departamento</td>
            <td>Salario</td>
            <td>Comisión</td>
            <td>Fecha alta</td>
            <td></td>
            <td></td>
        </tr>
        <?php foreach ($empleados as $empleado):?>
            <tr>
                <?=$empleado->toTable();?>
                <td>
                    <form method="post">
                        <input type="hidden" name="emp_no" value="<?=$empleado->getEmp_no();?>">
                        <input type="submit" name="eliminar" value="Eliminar">
                    </form>
                </td>
                <td>
                <form method="post">
                        <input type="hidden" name="emp_no" value="<?=$empleado->getEmp_no();?>">
                        <input type="submit" name="actualizar" value="Actualizar">
                    </form>
                </td>
            </tr>
        <?php endforeach;?>
    </table>

    <h3><?=$mensaje?></h3>
    <form method="post">
        <input type="hidden" name="emp_no" value="<?=$emp_no;?>">
        <p>
            <label for="apellido">Apellido:</label>
            <input type="text" name="apellido" id="apellido" value="<?=$apellido;?>">
        </p>
        <p>
            <label for="direccion">Dirección:</label>
            <input type="text" name="dir" id="dir" value="<?=$dir;?>">
        </p>
        <p>
            <label for="oficio">Oficio:</label>
            <input type="text" name="oficio" id="oficio" value="<?=$oficio;?>">
        </p>
        <p>
            <label for="dept_no">Departamento:</label>
            <input type="text" name="dept_no" id="dept_no" value="<?=$dept_no;?>">
        </p>
        <p>
            <label for="salario">Salario:</label>
            <input type="text" name="salario" id="salario" value="<?=$salario;?>">
        </p>
        <p>
            <label for="comision">Comisión:</label>
            <input type="text" name="comision" id="comision" value="<?=$comision;?>">
        </p>
        <p>
            <label for="fecha_alt">Fecha de alta:</label>
            <input type="text" name="fecha_alt" id="fecha_alt" value="<?=$fecha_alt;?>">
        </p>
        <p>
            <input type="submit" name="<?=$boton;?>" value="Enviar">
        </p>
    </form>
</body>
</html>