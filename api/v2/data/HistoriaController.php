<?php
class ControladorHistoria
{

    private $lista;
    private $services;

    public function __construct()
    {
        $this->lista = [];
        $this->imagenes = [];
    }
    public function getAllHistorias()
    {
        $con = new Conexion();
        $sql = "SELECT h.texto, h.tipo, h.activo FROM historia h";
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

    public function getHistoriaById($idHistoria)
    {
        $con = new Conexion();
        $sql = "select h.texto, h.tipo from historia h where h.id = $idHistoria";
        $rs = mysqli_query($con->getConnection(), $sql);
        if ($rs) {
            while ($tupla = mysqli_fetch_assoc($rs)) {
                array_push($this->lista, $tupla);
            }
            mysqli_free_result($rs);
        }
        $sql = "SELECT i.activo, i.imagen, i.nombre FROM historia_imagen hi inner join imagen i on hi.imagen_id = i.id where hi.historia_id = $idHistoria";
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


    public function newHistoria($body)
    {
        $con = new Conexion();
        $sql = "INSERT INTO historia (texto, tipo, activo) VALUES (?,?,false)";
        $stmt = mysqli_prepare($con->getConnection(), $sql);
        $rs = $stmt->execute(array($body->texto, $body->tipo));
        $new_id = $stmt->insert_id;
        $nrows = $stmt->affected_rows;
        $sql = "INSERT INTO historia_imagen (historia_id, imagen_id) VALUES (?,?)";
        $stmt = mysqli_prepare($con->getConnection(), $sql);
        $rs1 = $stmt->execute(array($new_id, $body->imagen_id));
        $nrows1 = $stmt->affected_rows;
        if (!$nrows && !$nrows1) {
            return false;
        }
        return $rs || $rs1;
    }

    public function patchHistoria($body)
    {
        $con = new Conexion();
        $sql = "UPDATE historia set activo = ? where id = ?";
        $stmt = mysqli_prepare($con->getConnection(), $sql);
        $rs = $stmt->execute([$body->activo, $body->id]);
        $nrows = $stmt->affected_rows;
        if ($nrows == 0) {
            return false;
        }
        return $rs;
    }
    public function putHistoria($body)
    {
        $con = new Conexion();
        $sql = "UPDATE historia set texto = ?, tipo = ? where id = ?;";
        $stmt = mysqli_prepare($con->getConnection(), $sql);
        $rs = $stmt->execute(array($body->texto, $body->tipo, $body->id));
        $nrows = $stmt->affected_rows;
        $sql = "update historia_imagen set imagen_id = ? where historia_id = ?;";
        $stmt = mysqli_prepare($con->getConnection(), $sql);
        $rs1 = $stmt->execute(array($body->imagen_id, $body->id));
        $nrows1 = $stmt->affected_rows;
        if (!$nrows && !$nrows1) {
            return false;
        }
        return $rs || $rs1;
    }
    public function deleteHistoria($idHistoria)
    {
        $con = new Conexion();
        $sql = "DELETE FROM historia_imagen where historia_id = ?;";
        $stmt = mysqli_prepare($con->getConnection(), $sql);
        $stmt->bind_param("i", $idHistoria);
        $rs1 = $stmt->execute();
        $nrows1 = $stmt->affected_rows;
        $sql = "DELETE FROM historia where id = ?;";
        $stmt = mysqli_prepare($con->getConnection(), $sql);
        $stmt->bind_param("i", $idHistoria);
        $rs = $stmt->execute();
        $nrows = $stmt->affected_rows;
        if (!$nrows && !$nrows1) {
            return false;
        }
        return $rs1 || $rs;
    }

}