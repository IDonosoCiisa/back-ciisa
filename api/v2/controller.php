<?php
include "../conexion.php";
class ControladorInfoContacto
{
    private $lista;

    public function __construct()
    {
        $this->lista = [];
    }

    public function getInfoContacto()
    {
        $con = new Conexion();
        $sql = "SELECT nombre, icono, texto, texto_adicional FROM info_contacto;";
        $rs = mysqli_query($con->getConnection(), $sql);
        if ($rs) {
            while ($tupla = mysqli_fetch_assoc($rs)) {
                array_push($this->lista, $tupla);
            }
            mysqli_free_result($rs);
        }
        $con->closeConnection();
        return $this->lista;
        ;
    }


}