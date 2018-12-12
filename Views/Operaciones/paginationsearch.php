<?php foreach($this->model->Buscar($startFrom, $periodo, $unidad) as $r): ?>
    <tr>
        <td><?php echo $this->model->getUnidadById($r->Unidad_Id); ?></td>
        <td><?php echo $r->Tipo==1 ? "Ingreso" : "Egreso" ; ?></td>
        <td><?php echo "S/.".($r->Monto==null ? "0" : $r->Monto); ?></td>
        <td><?php echo $this->getMesNombre($r->Mes); ?></td>
        <td><?php echo $r->Periodo; ?></td>

        <td class="cell-actions">
            <div class="btn-group">
                <a class="btn btn-xs btn-warning buttonCrud" href="<?php echo BASE_URL; ?>Operaciones/Crud/<?php echo $r->Id; ?>"><span class="glyphicon glyphicon-pencil"></span></a>
                <a <?php echo ($r->Unidad_Id != $_SESSION['Unidad_Id'] && !EDICION_GENERAL_PERMITIDA) ? 'style="display: none;"' : '' ?> onclick="javascript:return confirm('¿Seguro de eliminar este registro?');" class="btn btn-xs btn-danger buttonCrud" href="<?php echo BASE_URL; ?>Operaciones/Eliminar/<?php echo $r->Id; ?>"><span class="glyphicon glyphicon-trash"></span></a>
            </div>
        </td>
    </tr>
<?php endforeach; ?>