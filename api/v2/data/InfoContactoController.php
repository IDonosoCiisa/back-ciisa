<?php
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
        $sql = "SELECT id, nombre, icono, texto, texto_adicional, activo FROM info_contacto;";
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
    public function newInfoContacto($body)
    {
        $con = new Conexion();
        $sql = "INSERT INTO info_contacto (icono, nombre, texto, texto_adicional, activo) VALUES (?,?,?,?,false)";
        $stmt = mysqli_prepare($con->getConnection(), $sql);
        $rs = $stmt->execute(array($body->icono, $body->nombre, $body->texto, $body->texto_adicional));
        $nrows = $stmt->affected_rows;
        if (!$nrows) {
            return false;
        }
        return $rs;
    }

    public function patchInfoContacto($body)
    {
        $con = new Conexion();
        $sql = "UPDATE info_contacto set activo = ? where id = ?";
        $stmt = mysqli_prepare($con->getConnection(), $sql);
        $rs = $stmt->execute([$body->activo, $body->id]);
        $nrows = $stmt->affected_rows;
        if ($nrows == 0) {
            return false;
        }
        return $rs;
    }
    public function putInfoContacto($body)
    {
        $con = new Conexion();
        $sql = "UPDATE info_contacto set icono = ?, nombre = ?, texto = ?, texto_adicional = ? where id = ?;";
        $stmt = mysqli_prepare($con->getConnection(), $sql);
        $rs = $stmt->execute(array($body->icono, $body->nombre, $body->texto, $body->texto_adicional, $body->id));
        $nrows = $stmt->affected_rows;
        if (!$nrows) {
            return false;
        }
        return $rs;
    }
    public function deleteInfoContacto($idInfoContacto)
    {
        $con = new Conexion();
        $sql = "DELETE FROM info_contacto where id = ?;";
        $stmt = mysqli_prepare($con->getConnection(), $sql);
        $stmt->bind_param("i", $idInfoContacto);
        $rs = $stmt->execute();
        $nrows = $stmt->affected_rows;
        if (!$nrows) {
            return false;
        }
        return $rs;
    }

}