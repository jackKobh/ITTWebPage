<?php
	require_once('../BaseDeDatos/Conexion.php');
    require_once('../ManejoDatos/Sesion.php');
    require_once('../ManejoDatos/StringBuilder.php');
    require_once('../BaseDeDatos/IniciaConexion.php');
50luci0n1c
    $query = new StringBuilder();
    $salida = new StringBuilder();

    $id = $_GET['idBtn'];
    $query->appendFormat('SELECT NC_DESCRIPCION FROM NAV_CONF WHERE NC_ID_ELEMENTO = {0}', [$id]);
    $descripcion = $poConexion->ejecutarEscalar($query->toString());
    //echo $data->fetch_row()[0];

    $salida->appendFormat('<table class="table  table-bordered table-hover">
                                        <tr class="danger">
                                            <th colspan="5">Botones hijos Nav-Bar {0}</th>
                                            <th colspan="1" style="texi-align:center;">
                                                <button class="btn btn-default" id="btn-add-t2-{1}">
                                                    <i class="fa fa-plus" aria-hidden="true" ></i>
                                                </button>
                                            </th>
                                        </tr>
                                        <tr class="danger">
                                            <th>ID</th>
                                            <th>Desripción</th>
                                            <th>Link</th>
                                            <th>Padre</th>
                                            <th></th>
                                            <th></th>
                                        </tr>',[$descripcion,$id]);

    $query = new StringBuilder();
    $query->appendFormat('SELECT * FROM NAV_CONF WHERE NC_ID_PADRE = "{0}"',[$id]);

    foreach($poConexion->ejecutarQuery($query->toString()) as $data) 
    {
        $salida->appendFormat('         <tr>
                                            <td>{0}</td>
                                            <td>{1}</td>
                                            <td>{2}</td>
                                            <td>{3}</td>
                                            <td style="width:40px;">
                                                <button class="btn btn-primary" id="btn-update-t2-{0}" onclick="update(this.id)">
                                                    <i class="fa fa-refresh" aria-hidden="true"></i>
                                                </button>
                                            </td>
                                            <td style="width:40px;">
                                                <button class="btn btn-danger" id="btn-delete-t2-{0}" onclick="deleteRH({0},{4})">
                                                    <i class="fa fa-times" aria-hidden="true"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        
                                    ', [$data['NC_ID_ELEMENTO'], $data['NC_DESCRIPCION'], $data['NC_HREF'], $descripcion, $id]);
    }
    
    $salida->append('</table>');
    echo $salida->toString();

?>