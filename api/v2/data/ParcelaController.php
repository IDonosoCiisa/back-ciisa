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