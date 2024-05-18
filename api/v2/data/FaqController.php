<?php
class ControladorFaq
{
    private $lista;

    public function __construct()
    {
        $this->lista = [];
    }

    public function getFaq()
    {
        $con = new Conexion();
        $sql = "SELECT id, pregunta, respuesta, activo FROM pregunta_frecuente";
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
    public function newFaq($body)
    {
        $con = new Conexion();
        $sql = "INSERT INTO pregunta_frecuente (pregunta, respuesta, activo) VALUES (?,?,false)";
        $stmt = mysqli_prepare($con->getConnection(), $sql);
        $rs = $stmt->execute(array($body->pregunta, $body->respuesta));
        $nrows = $stmt->affected_rows;
        if (!$nrows) {
            return false;
        }
        return $rs;
    }

    public function patchFaq($body)
    {
        $con = new Conexion();
        $sql = "UPDATE pregunta_frecuente set activo = ? where id = ?";
        $stmt = mysqli_prepare($con->getConnection(), $sql);
        $rs = $stmt->execute([$body->activo, $body->id]);
        $nrows = $stmt->affected_rows;
        if ($nrows == 0) {
            return false;
        }
        return $rs;
    }
    public function putFaq($body)
    {
        $con = new Conexion();
        $sql = "UPDATE pregunta_frecuente set pregunta = ?, respuesta = ? where id = ?;";
        $stmt = mysqli_prepare($con->getConnection(), $sql);
        $rs = $stmt->execute(array($body->pregunta, $body->respuesta, $body->id));
        $nrows = $stmt->affected_rows;
        if (!$nrows) {
            return false;
        }
        return $rs;
    }
    public function deleteFaq($idFaq)
    {
        $con = new Conexion();
        $sql = "DELETE FROM pregunta_frecuente where id = ?;";
        $stmt = mysqli_prepare($con->getConnection(), $sql);
        $stmt->bind_param("i", $idFaq);
        $rs = $stmt->execute();
        $nrows = $stmt->affected_rows;
        if (!$nrows) {
            return false;
        }
        return $rs;
    }

}