<?php
 $found = false;
 $output = '<div class="content table-responsive table-full-width">
                <table id="tableUnidades" class="table table-hover table-striped">
                    <thead>
                    <th>Descripcion </th>
                    <th>Estado de Operatividad</th>
                    <th>Cantidad</th>
                    <th>UnidadProductiva</th>
                    <th>Observaciones</th>
                    </thead>
                    <tbody id="target-content">';

foreach( ($unidad==-1 ? $this->model->Buscar($startFrom,$search) : $this->model->BuscarByUnidadId($startFrom, $search, $unidad) )  as $r):
	$found = true;
    $paestadooperativo="no operativo";
    if ($r->EstadoOperativo==1 )
        $paestadooperativo="equipo con reparaciones";                
    if ($r->EstadoOperativo==2 )
        $paestadooperativo="Operativo";         
    $output .='<tr>
                <td>'.$r->Descripcion.'</td>
                <td>'.$paestadooperativo.'</td>';
    $cantidad=0;
    if($this->model->getcantidadbyin($r->Id) > 0 )
        $cantidad=$this->model->getcantidadbyin($r->Id);
    $output .='<td>'.$cantidad.'</td> 
                <td>'.$this->model->ObtenerNombreUnidadProductiva($r->Unidad_Id)->Nombre.'</td>
                <td>'.$r->Observaciones.'</td>
                <td class=" cell-actions"><!--</td> class="cell-actions">-->
                <div class="btn-group">
                    <a class="btn btn-xs btn-warning buttonCrud"    href="'.BASE_URL.'InventarioBienes/Crud/'.$r->Id.'"'.'><span class="glyphicon glyphicon-pencil"></span></a>
                </div>';

if ( $r->Unidad_Id==$_SESSION["Unidad_Id"] ) 
  {$output.= '<a onclick="javascript:return confirm('."'¿Seguro de eliminar este registro?'".');"'.'class="btn btn-xs btn-danger buttonCrud" href="<?php echo BASE_URL; ?>InventarioBienes/Eliminar/<?php echo $r->Id; ?>"><span class="glyphicon glyphicon-trash"></span> </a>
    </td> 
    </tr>';
}
else
{
  $output.='</td> 
    </tr>';
}
endforeach;

$output.=   '</tbody>
                </table>
            </div>
            <nav><ul class="pagination">';
                if(!empty($totalPages)):for($i=1; $i<=$totalPages; $i++):  
                  if($i == 1):
                    $output.= '<li class="active"  id="'.$i.'"><a href="'.BASE_URL.'InventarioBienes/Paginacion/Pagina/'.$i.'/Busqueda/'.$search.'">'.$i.'</a></li>'; 
                  else:
                    $output.= '<li id="'.$i.'"><a href="'.BASE_URL.'InventarioBienes/Paginacion/Pagina/'.$i.'/Busqueda/'.$search.'">'.$i.'</a></li>';
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