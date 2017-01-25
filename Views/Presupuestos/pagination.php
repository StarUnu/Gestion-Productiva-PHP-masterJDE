<?php foreach($this->model->Listar($startFrom) as $r): ?>
    <tr>
        <td><?php echo $r->Periodo; ?></td>
        <td><?php echo $r->Asignado; ?></td>
        <td><?php echo $this->modelEjecucionPresupuesto->getMontoEjecutadoAgrupadoPeriodoUnidadTipo($r->Periodo, $_SESSION['Unidad_Id'], 1);?></td>
        <td><?php echo $this->modelEjecucionPresupuesto->getMontoEjecutadoAgrupadoPeriodoUnidadTipo($r->Periodo, $_SESSION['Unidad_Id'], 2);?></td>
        <td><?php echo $this->modelEjecucionPresupuesto->getMontoEjecutadoAgrupadoPeriodoUnidadTipo($r->Periodo, $_SESSION['Unidad_Id'], 3);?></td>
        <td><?php echo $this->modelEjecucionPresupuesto->getMontoEjecutadoAgrupadoPeriodoUnidadTipo($r->Periodo, $_SESSION['Unidad_Id'], 4);?></td>

        <td class="cell-actions">
            <div class="btn-group">
                <!--
                <a class="btn btn-xs btn-warning buttonCrud" href="<?php echo BASE_URL; ?>Presupuestos/Crud/<?php echo $r->Id; ?>"><span class="glyphicon glyphicon-pencil"></span></a>-->
                <a onclick="javascript:return confirm('¿Seguro de eliminar este registro?, Se eliminaran todas las ejecuciones relacionadas a este presupuesto');" class="btn btn-xs btn-danger buttonCrud" href="<?php echo BASE_URL; ?>Presupuestos/Eliminar/<?php echo $r->Id; ?>"><span class="glyphicon glyphicon-trash"></span></a>
            </div>
        </td>
    </tr>
<?php endforeach; ?>