 <?php 
 $found = false;
  $output = '<div class="content table-responsive table-full-width">
                <table id="tableUnidades" class="table table-hover table-striped">
                    <thead>
						<th>Fecha de Ingreso </th>
                        <th>Descripcion</th>
                        <th>Condicion</th>
                        <th>Estado de Operatividad</th>
                        <!--<th>Unidad Productiva</th>-->
                    </thead>
                    <tbody id="target-content">';
foreach( ($unidad==-1 ? $this->model->Buscar($startFrom,$search) : $this->model->BuscarByUnidadId($startFrom, $search, $unidad) )  as $r):
    //<th> Observaciones</th>
	$found = true;
    $paestadooperativo="no operativo";
    if ($r->EstadoOperativo==1 )
        $paestadooperativo="equipo con reparaciones";                
    if ($r->EstadoOperativo==2 )
        $paestadooperativo="Operativo"; 
    $condicion ="bueno";
    if($r->Condicion == 0) 
        $condicion ="malo";
    if($r->Condicion == 1) 
        $condicion ="regular";
    $output .='<tr> 
                    <td>'.$r->Fecha_Ingreso.'</td>
                    <td>'.$r->Descripcion.'</td>
                    <td>'.$condicion.'</td>
                    <td>'.$paestadooperativo.'</td>
                <td class=" cell-actions"><!--</td> class="cell-actions">-->
                <div class="btn-group">
                    <a class="btn btn-xs btn-warning buttonCrud"    href="'.BASE_URL.'InventarioEquipos/Crud/'.$r->Id.'"'.'><span class="glyphicon glyphicon-pencil"></span></a>
                    <!--e span de glyphicon-->
                </div>';
if ( $r->Unidad_Id==$_SESSION["Unidad_Id"] ) //el admin no puede modificar a menos eque es  la suya
{	$output.= '<a onclick="javascript:return confirm('."'¿Seguro de eliminar este registro?'".');"'.'class="btn btn-xs btn-danger buttonCrud" href="<?php echo BASE_URL; ?>InventarioEquipos/Eliminar/<?php echo $r->Id; ?>"><span class="glyphicon glyphicon-trash"></span> </a>
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
                    $output.= '<li class="active"  id="'.$i.'"><a href="'.BASE_URL.'InventarioEquipos/Paginacion/Pagina/'.$i.'/Busqueda/'.$search.'">'.$i.'</a></li>'; 
                  else:
                    $output.= '<li id="'.$i.'"><a href="'.BASE_URL.'InventarioEquipos/Paginacion/Pagina/'.$i.'/Busqueda/'.$search.'">'.$i.'</a></li>';
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