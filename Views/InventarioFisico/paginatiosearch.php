<?php foreach($this->model->Buscar($startFrom,$search) as $r): ?>
    <tr>
        <td><?php echo $r->Descripcion_Existencia; ?></td>
        <td><?php echo $r->Periodo; ?></td>
        <td><?php echo ($this->model->getCantidadDetalle($r->Id)=='' ? "0" : $this->model->getCantidadDetalle($r->Id)); ?> </td>
        <?php $unidadpro =$this->model->getunidadByid(     $r->Unidad_Id)  ?>
        <td><?php if($unidadpro!=null)echo $unidadpro->Nombre;?> </td>
        <!--los %20son los espacios-->
        <td class=" cell-actions"><!--</td> class="cell-actions">-->
            <div class="btn-group">
                <a class="btn btn-xs btn-warning buttonCrud"    href="<?php echo BASE_URL;?>InventarioFisico/Crud/<?php echo $r->Id; ?> "><span class="glyphicon glyphicon-pencil"></span></a>
                <!--e span de glyphicon-->
                <a onclick="javascript:return confirm('¿Seguro de eliminar este registro?');" class="btn btn-xs btn-danger buttonCrud" href="<?php echo BASE_URL; ?>InventarioFisico/Eliminar/<?php echo $r->Id; ?>"><span class="glyphicon glyphicon-trash"></span></a>
            </div>

        </td>
        
    </tr>
<?php endforeach; ?>