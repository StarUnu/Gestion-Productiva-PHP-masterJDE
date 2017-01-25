
<?php foreach ($this->model->Listar($startFrom) as $r): ?>
    <?php
    if (isset($_SESSION['Unidad_Id']) ) {
        $unidadid=$_SESSION['Unidad_Id'];
        //$idequipo = $this->model->getInventarioId($unidadid);
        //aca faltaria la unidad productiva
    ?>
    <?php
        if( $r->Unidad_Id == $unidadid)
        {?>
        <tr> 
        <td><?php echo $this->model->ObtenerNombreUnidadProductiva($r->Unidad_Id)->Nombre; ?></td>
        <td><?php echo $r->Estado; ?></td>
        <td><?php echo $r->EstadoOperativo; ?></td>
        <td><?php echo $r->Observaciones; ?></td>
        <td class=" cell-actions">
        <div class="btn-group">
        <a class="btn btn-xs btn-warning buttonCrud"    href="<?php echo BASE_URL;?>InventarioBienes/Crud/<?php echo $r->Id; ?> "><span class="glyphicon glyphicon-pencil"></span></a>
        <a onclick="javascript:return confirm('¿Seguro de eliminar este registro?');" class="btn btn-xs btn-danger buttonCrud" href="<?php echo BASE_URL; ?>InventarioBienes/Eliminar/<?php echo $r->Id; ?>"><span class="glyphicon glyphicon-trash"></span></a>
        </div>
        </td>
        </tr>
        <?php
        } 
    }
    else{?>                   
    <tr>
        <td><?php echo $this->model->ObtenerNombreUnidadProductiva($r->Unidad_Id)->Nombre; ?></td>
        <td><?php echo $r->Estado; ?></td>
        <td><?php echo $r->EstadoOperativo; ?></td>
        <td><?php echo $r->Observaciones; ?></td>
        <td class=" cell-actions">
        <div class="btn-group">
        <?php if(isset($_SESSION['Unidad_Id'])) { ?>
        <a class="btn btn-xs btn-warning buttonCrud"    href="<?php echo BASE_URL;?>InventarioBienes/Crud/<?php echo $r->Id; ?> "><span class="glyphicon glyphicon-pencil"></span></a>
        <a onclick="javascript:return confirm('¿Seguro de eliminar este registro?');" class="btn btn-xs btn-danger buttonCrud" href="<?php echo BASE_URL; ?>InventarioBienes/Eliminar/<?php echo $r->Id; ?>"><span class="glyphicon glyphicon-trash"></span></a>
        <?php }?>
        </div>
        </td>   
    </tr>
    <?php
    }
    ?>
<?php endforeach; ?>
