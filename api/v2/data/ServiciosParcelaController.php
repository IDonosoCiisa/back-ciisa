<?php
class ControladorServiciosParcela
{
    private $lista;

    public function __construct()
    {
        $this->lista = [];
    }

    public function getAllServiciosParcela()
    {

        $con = new Conexion();
        $sql = "select nombre, id, activo from parcela_servicio";
        $rs = mysqli_query($con->getConnection(), $sql);
        if ($rs) {
            while ($tupla = mysqli_fetch_assoc($rs)) {
                array_push($this->lista, $tupla);
            }
            mysqli_free_result($rs);
        }
        $con->closeConnection();
        return $this->lista;

    }
    public function newServicioParcela($nombre)
    {
        $con = new Conexion();
        $sql = "INSERT INTO parcela_servicio (nombre, activo) VALUES (?, false)";
        $stmt = mysqli_prepare($con->getConnection(), $sql);
        $stmt->bind_param("s", $nombre);
        $rs = $stmt->execute();
        $nrows = $stmt->affected_rows;
        if (!$nrows) {
            return false;
        }
        return $rs;
    }

    public function patchServicioParcela($body)
    {
        $con = new Conexion();
        $sql = "UPDATE parcela_servicio set activo = ? where id = ?";
        $stmt = mysqli_prepare($con->getConnection(), $sql);
        $stmt->bind_param("bi", $body->activo, $body->id);
        $rs = $stmt->execute();
        $nrows = $stmt->affected_rows;
        if ($nrows == 0) {
            return false;
        }
        return $rs;
    }
    public function putServicioParcela($body)
    {
        $con = new Conexion();
        $sql = "UPDATE parcela_servicio set nombre = ? where id = ?;";
        $stmt = mysqli_prepare($con->getConnection(), $sql);
        $stmt->bind_param("si", $body->nombre, $body->id);
        $rs = $stmt->execute();
        $nrows = $stmt->affected_rows;
        if (!$nrows) {
            return false;
        }
        return $rs;
    }
    public function deleteServicioParcela($idParcela)
    {
        $con = new Conexion();
        $sql = "DELETE FROM parcela_servicio where id = ?;";
        $stmt = mysqli_prepare($con->getConnection(), $sql);
        $stmt->bind_param("i", $idParcela);
        $rs = $stmt->execute();
        $nrows = $stmt->affected_rows;
        if (!$nrows) {
            return false;
        }
        return $rs;
    }

}