<?php foreach($this->model->Buscar($startFrom,$search) as $r): ?>
    <tr>
        <td><?php echo $r->Descripcion; ?></td>
        <td><?php echo $r->Observaciones; ?></td>
        <td><?php echo $r->FechaIngreso; ?> </td>
        <td><?php echo $this->model->ObtenerNombreUnidadProductiva($r->Unidad_Id)->Nombre; ?></td>
        <td class=" cell-actions"><!--</td> class="cell-actions">-->
            <div class="btn-group">
                <a class="btn btn-xs btn-warning buttonCrud"    href="<?php echo BASE_URL;?>InventariodeSeresVivos/Crud/<?php echo $r->Id; ?> "><span class="glyphicon glyphicon-pencil"></span></a>
                <!--e span de glyphicon-->
                <a onclick="javascript:return confirm('¿Seguro de eliminar este registro?');" class="btn btn-xs btn-danger buttonCrud" href="<?php echo BASE_URL; ?>InventariodeSeresVivos/Eliminar/<?php echo $r->Id; ?>"><span class="glyphicon glyphicon-trash"></span></a>
            </div>

        </td>
    </tr>
<?php endforeach; ?>