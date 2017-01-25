<?php foreach($this->model->Buscar($startFrom,$search) as $r): ?>
    <tr>
        <td><?php echo $r->Fechaingreso; ?> </td>
        <td><?php echo $r->Descripcion; ?> </td>
        <td><?php echo $r->Observaciones; ?> </td>
        <!--los %20son los espacios-->
        <td class=" cell-actions"><!--</td> class="cell-actions">-->
            <div class="btn-group">
                <a class="btn btn-xs btn-warning buttonCrud"    href="<?php echo BASE_URL;?>InventarioCosecha/Crud/<?php echo $r->Id; ?> "><span class="glyphicon glyphicon-pencil"></span></a>
                <!--e span de glyphicon-->
                <a onclick="javascript:return confirm('¿Seguro de eliminar este registro?');" class="btn btn-xs btn-danger buttonCrud" href="<?php echo BASE_URL;?>InventarioCosecha/Eliminar/<?php echo $r->Id; ?>"><span class="glyphicon glyphicon-trash"></span></a>
            </div>
        </td>
    </tr>
<?php endforeach; ?>
