<?php foreach($this->model->Buscar($startFrom) as $r ) : ?>
    <tr>
        <td><?php echo $r->Fecha_Ingreso; ?> </td>
        <td><?php echo $r->Descripcion; ?> </td>
        <td><?php echo ($r->Condicion==0 ? "Alquiler" : "Propio") ;?> </td>
        <td><?php echo ($r->EstadoOperativo==0 ? "Malo ":($r->EstadoOperativo == 1 ? "Regular": "Bueno")); ?> </td>

        <td><?php echo 
        $this->model->getnombreUnidadId($r->Unidad_Id)->Nombre ?> </td>
        <!--los %20son los espacios-->
        <td class=" cell-actions">
             <div class="btn-group">
                <a class="btn btn-xs btn-warning buttonCrud"    href="<?php echo BASE_URL;?>InventarioEquipos/Crud/<?php echo $r->Id; ?> "><span class="glyphicon glyphicon-pencil"></span></a>
                <?php if ($_SESSION["Unidad_Id"] == $r->Unidad_Id) { ?>
                <a onclick="javascript:return confirm('¿Seguro de eliminar este registro?');" class="btn btn-xs btn-danger buttonCrud" href="<?php echo BASE_URL; ?>InventarioEquipos/Eliminar/<?php echo $r->Id; ?>"><span class="glyphicon glyphicon-trash"></span></a>
                <?php } ?>
            </div>

        </td>
        
    </tr>
<?php endforeach; ?>
