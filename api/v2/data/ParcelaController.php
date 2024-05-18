<?php
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
        $sql = "select p.id, p.nombre, p.parcela_lote_id, pl.nombre as 'nombre_parcela', p.parcela_tipo_id, pt.nombre as 'tipo_parcela', p.numeracion_interna, p.terreno_ancho, p.terreno_largo, p.terreno_despejado_arboles, p.ubicacion_latitud_gm, p.ubicacion_longitud_gm, p.activo from parcela as p inner join parcela_tipo as pt on p.parcela_tipo_id = pt.id inner join parcela_lote as pl on p.parcela_lote_id = pl.id";
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
        $sql = "SELECT ps.nombre FROM parcela_servicio ps inner join parcela_servicio_parcela psp on ps.id = psp.parcela_servicio_id where psp.parcela_id = $idParcela";
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

    public function newParcela($body)
    {
        $con = new Conexion();
        $sql = "INSERT INTO parcela (nombre, parcela_lote_id, parcela_tipo_id, numeracion_interna, terreno_ancho, terreno_largo, terreno_despejado_arboles, ubicacion_latitud_gm, ubicacion_longitud_gm, pie, valor, activo) VALUES (?,?,?,?,?,?,?,?,?,?,?, false)";
        $stmt = mysqli_prepare($con->getConnection(), $sql);
        $rs = $stmt->execute(array($body->nombre, $body->parcela_lote_id, $body->parcela_tipo_id, $body->numeracion_interna, $body->terreno_ancho, $body->terreno_largo, $body->terreno_despejado_arboles, $body->ubicacion_latitud_gm, $body->ubicacion_longitud_gm, $body->pie, $body->valor));
        $new_id = $stmt->insert_id;
        $nrows = $stmt->affected_rows;
        foreach ($body->servicios_parcela as $servicio) {
            $sql = "INSERT INTO parcela_servicio_parcela (parcela_id, parcela_servicio_id) VALUES (?,?)";
            $stmt = mysqli_prepare($con->getConnection(), $sql);
            $rs1 = $stmt->execute(array($new_id, $servicio));
        }
        $nrows1 = $stmt->affected_rows;
        if (!$nrows && !$nrows1) {
            return false;
        }
        return $rs || $rs1;
    }

    public function patchParcela($body)
    {
        $con = new Conexion();
        $sql = "UPDATE parcela set activo = ? where id = ?";
        $stmt = mysqli_prepare($con->getConnection(), $sql);
        $rs = $stmt->execute([$body->activo, $body->id]);
        $nrows = $stmt->affected_rows;
        if ($nrows == 0) {
            return false;
        }
        return $rs;
    }
    public function putParcela($body)
    {
        $con = new Conexion();
        $sql = "UPDATE parcela set nombre = ?, parcela_lote_id = ?, parcela_tipo_id = ?, numeracion_interna = ?, terreno_ancho = ?, terreno_largo = ?, terreno_despejado_arboles = ?, ubicacion_latitud_gm = ?, ubicacion_longitud_gm = ?, pie = ?, valor = ?  where id = ?;";
        $stmt = mysqli_prepare($con->getConnection(), $sql);
        $rs = $stmt->execute(array($body->nombre, $body->parcela_lote_id, $body->parcela_tipo_id, $body->numeracion_interna, $body->terreno_ancho, $body->terreno_largo, $body->terreno_despejado_arboles, $body->ubicacion_latitud_gm, $body->ubicacion_longitud_gm, $body->pie, $body->valor, $body->id));
        $nrows = $stmt->affected_rows;
        $sql = "DELETE FROM parcela_servicio_parcela where parcela_id = ?;";
        $stmt = mysqli_prepare($con->getConnection(), $sql);
        $rs1 = $stmt->execute(array($body->id));
        $nrows1 = $stmt->affected_rows;
        foreach ($body->servicios_parcela as $servicio) {
            $sql = "INSERT INTO parcela_servicio_parcela (parcela_id, parcela_servicio_id) VALUES (?,?)";
            $stmt = mysqli_prepare($con->getConnection(), $sql);
            $rs1 = $stmt->execute(array($body->id, $servicio));
        }
        if (!$nrows && !$nrows1) {
            return false;
        }
        return $rs || $rs1;
    }
    public function deleteParcela($idHistoria)
    {
        $con = new Conexion();
        $sql = "DELETE FROM parcela_servicio_parcela where parcela_id = ?;";
        $stmt = mysqli_prepare($con->getConnection(), $sql);
        $stmt->bind_param("i", $idHistoria);
        $rs1 = $stmt->execute();
        $nrows1 = $stmt->affected_rows;
        $sql = "DELETE FROM parcela where id = ?;";
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