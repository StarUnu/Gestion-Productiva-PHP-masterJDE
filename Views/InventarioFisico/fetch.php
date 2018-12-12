 <?php 
 $found = false;
 $output = '<div class="content table-responsive table-full-width">
                            <table id="tableUnidades" class="table table-hover table-striped">
                                <thead>
                                    <th>Descripcion </th>
                                    <th>FechadeIngreso</th>
                                    <th>Cantidad</th>
                                    <th>Unidad Productiva</th>
                                </thead>

                                <tbody id="target-content">';

foreach( ($unidad==-1 ? $this->model->Buscar($startFrom,$search) : $this->model->BuscarByUnidadId($startFrom, $search, $unidad) )  as $r):
    $found = true;
    $output .='<tr>
              <td>'.$r->Descripcion_Existencia.'</td>
              <td>'.$r->FechaIngreso.'</td>
              <td>'.($this->model->getCantidadDetalle($r->Id)=='' ? "0" : $this->model->getCantidadDetalle($r->Id)).'</td>
              <td>'.$this->model->getunidadByid($r->Unidad_Id)->Nombre.'</td>
              <td class=" cell-actions"><!--</td> class="cell-actions">-->
                <div class="btn-group">
                    <a class="btn btn-xs btn-warning buttonCrud"    href="'.BASE_URL.'InventarioFisico/Crud/'.$r->Id.'"'.'><span class="glyphicon glyphicon-pencil"></span></a>
                </div>';

              
//".($_SESSION["TipoUsuario"] ==0||$r->Unidad_Id==$_SESSION["Unidad_Id"] ? "lal")."
if ( $r->Unidad_Id==$_SESSION["Unidad_Id"] ) 
  {$output.= '<a onclick="javascript:return confirm('."'¿Seguro de eliminar este registro?'".');"'.'class="btn btn-xs btn-danger buttonCrud" href="<?php echo BASE_URL; ?>InventarioFisico/Eliminar/<?php echo $r->Id; ?>"><span class="glyphicon glyphicon-trash"></span> </a>
    </td> 
    </tr>';
}
else
{
  $output.='</td> 
    </tr>';
}
//el admin no puede modificar 
endforeach;

$output.=   '</tbody>
                </table>
            </div>

            <nav><ul class="pagination">';
                if(!empty($totalPages)):for($i=1; $i<=$totalPages; $i++):  
                  if($i == 1):
                    $output.= '<li class="active"  id="'.$i.'"><a href="'.BASE_URL.'InventarioFisico/Paginacion/Pagina/'.$i.'/Busqueda/'.$search.'">'.$i.'</a></li>'; 
                  else:
                    $output.= '<li id="'.$i.'"><a href="'.BASE_URL.'InventarioFisico/Paginacion/Pagina/'.$i.'/Busqueda/'.$search.'">'.$i.'</a></li>';
                  endif;
                endfor;endif;
            $output.= '</ul></nav>';
            $output.= '<input hidden type="text" id="totalRecords" value="'.$totalRecords.'">';
            $output.= '<p class="text-success"> Total: '.$totalRecords.'</p>';

if(!$found)
{
   echo 'No se encontraron coincidencia!!';
}
else
{
  echo $output;
}

?>
