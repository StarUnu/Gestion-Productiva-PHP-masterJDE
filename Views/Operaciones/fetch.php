 <?php 
 $found = false;
 $output = '<div class="content table-responsive table-full-width">
              <table class="table table-hover table-striped">
                  <thead>
                      <th>Unidad</th>
                      <th>Tipo</th>
                      <th>Monto</th>
                      <th>Mes</th>
                      <th>Periodo</th>
                      <th></th>
                  </thead>
                  <tbody id="target-content">';
 foreach($this->model->Buscar($startFrom,$periodo, $unidad)  as $r):
    $found = true;
        $output .= '<tr>
                      <td>'.$this->model->getUnidadById($r->Unidad_Id).'</td>
                      <td>'.($r->Tipo==1 ? "Ingreso" : "Egreso").'</td>
                      <td>'."S/.".($r->Monto==null ? "0" : $r->Monto).'</td>
                      <td>'.$this->getMesNombre($r->Mes).'</td>
                      <td>'.$r->Periodo.'</td>
                      <td class="cell-actions">
                          <div class="btn-group">
                              <a class="btn btn-xs btn-warning buttonCrud" href="'.BASE_URL.'Operaciones/Crud/'.$r->Id.'"'.'><span class="glyphicon glyphicon-pencil"></span></a>
                              <a '.(($r->Unidad_Id != $_SESSION['Unidad_Id'] && !EDICION_GENERAL_PERMITIDA) ? 'style="display: none;"' : '').' onclick="javascript:return confirm('."'¿Seguro de eliminar este registro?'".');"'.'class="btn btn-xs btn-danger buttonCrud" href="'.BASE_URL.'Operaciones/Eliminar/'.$r->Id.'"><span class="glyphicon glyphicon-trash"></span></a>
                          </div>
                      </td>
                    </tr>';
endforeach;
$output.=         '</tbody>
                </table>
            </div>
            <nav><ul class="pagination">';
                if(!empty($totalPages)):for($i=1; $i<=$totalPages; $i++):  
                  if($i == 1):
                    $output.= '<li class="active"  id="'.$i.'"><a href="'.BASE_URL.'Operaciones/Paginacion/Pagina/'.$i.'/Unidad/'.$unidad.'/Busqueda/'.$periodo.'">'.$i.'</a></li>'; 
                  else:
                    $output.= '<li id="'.$i.'"><a href="'.BASE_URL.'Operaciones/Paginacion/Pagina/'.$i.'/Unidad/'.$unidad.'/Busqueda/'.$periodo.'">'.$i.'</a></li>';
                  endif;
                endfor;endif;
            $output.= '</ul></nav>';
            $output.= '<input hidden type="text" id="totalRecords" value="'.$totalRecords.'">';
            $output.= '<p class="text-success"> Total: '.$totalRecords.'</p>';

if (!$found)
{
  echo 'No se encontraron coincidencias';
} else 
{
  echo $output;
}
?>
