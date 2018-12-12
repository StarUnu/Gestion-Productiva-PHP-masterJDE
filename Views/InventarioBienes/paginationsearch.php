<?php foreach($this->model->Buscar($startFrom,$search) as $r): ?>
   <tr>
        <?php $cantidad=0;
        if($this->model->getcantidadbyin($r->Id) > 0 )
            $cantidad=$this->model->getcantidadbyin($r->Id); ?>
        <td><?php echo $r->Descripcion ?></td>
        <td><?php echo ($r->EstadoOperativo==0 ? "no operativo" :            ($r->EstadoOperativo==1 ? "equipo con reparaciones" : "operativo")   ) ?></td>
        <td><?php echo $cantidad ?></td> 
        <td><?php echo $this->model->ObtenerNombreUnidadProductiva($r->Unidad_Id)->Nombre; ?></td>
        <td><?php echo $r->Descripcion; ?></td>
            <td class=" cell-actions">
             <div class="btn-group">
              <a class="btn btn-xs btn-warning buttonCrud"    href="<?php echo BASE_URL;?>InventarioBienes/Crud/<?php echo $r->Id; ?> "><span class="glyphicon glyphicon-pencil"></span></a>
        <?php if($r->Unidad_Id ==$_SESSION["Unidad_Id"]  ) { ?>                              
              <a onclick="javascript:return confirm('¿Seguro de eliminar este registro?');" class="btn btn-xs btn-danger buttonCrud" href="<?php echo BASE_URL; ?>InventarioBienes/Eliminar/<?php echo $r->Id; ?>"><span class="glyphicon glyphicon-trash"></span></a>
             </div>
            </td>
        <?php } ?>
    </tr><!--ya se para que es esto es para las columnas-->
<?php endforeach; ?>