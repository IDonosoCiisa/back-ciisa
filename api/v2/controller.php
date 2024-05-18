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
        $sql = "SELECT nombre, icono, texto, texto_adicional, activo FROM info_contacto;";
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

class ControladorParcela
{
    private $lista;
    private $services;

    public function __construct()
    {
        $this->lista = [];
        $this->services = [];
    }

    public function getAllParcelas()
    {
        $con = new Conexion();
        $sql = "select p.nombre, p.parcela_lote_id, pl.nombre as 'nombre_parcela', p.parcela_tipo_id, pt.nombre as 'tipo_parcela', p.numeracion_interna, p.terreno_ancho, p.terreno_largo, p.terreno_despejado_arboles, p.ubicacion_latitud_gm, p.ubicacion_longitud_gm, p.activo from parcela as p inner join parcela_tipo as pt on p.parcela_tipo_id = pt.id inner join parcela_lote as pl on p.parcela_lote_id = pl.id";
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

    public function getParcelaById($idParcela)
    {
        $con = new Conexion();
        $sql = "select p.nombre, p.parcela_lote_id, pl.nombre as 'nombre_parcela', p.parcela_tipo_id, pt.nombre as 'tipo_parcela', p.numeracion_interna, p.terreno_ancho, p.terreno_largo, p.terreno_despejado_arboles, p.ubicacion_latitud_gm, p.ubicacion_longitud_gm, p.activo from parcela as p inner join parcela_tipo as pt on p.parcela_tipo_id = pt.id inner join parcela_lote as pl on p.parcela_lote_id = pl.id where p.id = $idParcela";
        $rs = mysqli_query($con->getConnection(), $sql);
        if ($rs) {
            while ($tupla = mysqli_fetch_assoc($rs)) {
                array_push($this->lista, $tupla);
            }
            mysqli_free_result($rs);
        }
        $sql = "SELECT ps.nombre FROM parcela p inner join parcela_servicio_parcela psp on p.id = psp.parcela_id inner join parcela_servicio ps on ps.id = psp.id where p.id = $idParcela";
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

}

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