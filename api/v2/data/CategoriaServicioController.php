<?php
class ControladorCategoriaServicio
{
    private $lista;

    public function __construct()
    {
        $this->lista = [];
    }

    public function getCategoriaServicio()
    {
        $con = new Conexion();
        $sql = "SELECT nombre, imagen, texto, activo FROM categoria_servicio";
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
    public function newCategoriaServicio($body)
    {
        $con = new Conexion();
        $sql = "INSERT INTO categoria_servicio (nombre, imagen, texto, activo) VALUES (?,?,?,false)";
        $stmt = mysqli_prepare($con->getConnection(), $sql);
        $rs = $stmt->execute(array($body->nombre, $body->imagen, $body->texto));
        $nrows = $stmt->affected_rows;
        if (!$nrows) {
            return false;
        }
        return $rs;
    }

    public function patchCategoriaServicio($body)
    {
        $con = new Conexion();
        $sql = "UPDATE categoria_servicio set activo = ? where id = ?";
        $stmt = mysqli_prepare($con->getConnection(), $sql);
        $rs = $stmt->execute([$body->activo, $body->id]);
        $nrows = $stmt->affected_rows;
        if ($nrows == 0) {
            return false;
        }
        return $rs;
    }
    public function putCategoriaServicio($body)
    {
        $con = new Conexion();
        $sql = "UPDATE categoria_servicio set nombre = ?, texto = ?, imagen = ? where id = ?;";
        $stmt = mysqli_prepare($con->getConnection(), $sql);
        $rs = $stmt->execute(array($body->nombre, $body->texto, $body->imagen, $body->id));
        $nrows = $stmt->affected_rows;
        if (!$nrows) {
            return false;
        }
        return $rs;
    }
    public function deleteCategoriaServicio($idCategoriaServicio)
    {
        $con = new Conexion();
        $sql = "DELETE FROM categoria_servicio where id = ?;";
        $stmt = mysqli_prepare($con->getConnection(), $sql);
        $stmt->bind_param("i", $idCategoriaServicio);
        $rs = $stmt->execute();
        $nrows = $stmt->affected_rows;
        if (!$nrows) {
            return false;
        }
        return $rs;
    }

}