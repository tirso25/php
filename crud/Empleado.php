<?php

class Empleado{

    private $emp_no;
    private $apellido;
    private $dir;
    private $oficio;
    private $dept_no;
    private $salario;
    private $comision;
    private $fecha_alt;

    public function getEmp_no(){
        return $this->emp_no;
    }

    public function getApellido(){
        return $this->apellido;
    }

    public function getDir(){
        return $this->dir;
    }

    public function getDept_no(){
        return $this->dept_no;
    }

    public function getSalario(){
        return $this->salario;
    }

    public function getComision(){
        return $this->comision;
    }

    public function getFecha_alt(){
        return $this->fecha_alt;
    }

    public function __toString(){
        $cadena = "";

        $propiedades = get_object_vars($this);
        foreach($propiedades as $propiedad){
            $cadena .= $propiedad . " ";
        }

        return $cadena;
    }


    public function toTable(){
        $cadena = "";

        $propiedades = get_object_vars($this);
        foreach($propiedades as $propiedad){
            $cadena .= "<td>" . $propiedad . "</td>";
        }

        return $cadena;
    }

    public function toList(){
        $cadena = "";

        $propiedades = get_object_vars($this);
        foreach($propiedades as $propiedad){
            $cadena .= "<li>" . $propiedad . "</li>";
        }

        return $cadena;
    }

    public function toArray(){
        $array = [];

        $propiedades = get_object_vars($this);
        foreach($propiedades as $nombre => $propiedad){
            $array[$nombre] = $propiedad;
        }

        return $array;
    }

}