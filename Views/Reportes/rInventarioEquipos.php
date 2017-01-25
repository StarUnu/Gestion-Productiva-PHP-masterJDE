<?php 
//como hacer para que me lo imprima una parte en el index
//posdata ya sale pero no lo logro todavia hacer funcionar
//falta dentrar los datos 
require_once('Core/pdf/mpdf.php');
global $modelinventarioequipo;
global $modelequipo;
global $unidad;
global $nombreUnidad;
global $responsable;
global $ytoday;
global $direccion;
global $nomfa;
global $actividad;
global $nomfacul;
$modelinventarioequipo= new InventarioEquipos();
$modelequipo = new Equipos();
$unidad = $this->modelUnidadProductiva->Obtener($_GET['Unidad']);
//$facultad = $_GET['facultad'];
$nombreUnidad = $unidad->Nombre;
$responsable = $this->modelUnidadProductiva->getResponsableByDni($unidad->Persona_Dni);
$direccion = $this->modelUnidadProductiva->getRespondable($unidad->Persona_Dni)->Direccion; 
$nomfacul=$this->modelUnidadProductiva->getFacultadbyId($unidad->Facultad_Id)->Nombre;
$actividad =$this->modelUnidadProductiva->getRubroById($unidad->Rubro_Id);

function numerosastring($Condicion,$EstadoOperativo,& $scondicion, & $sestadoO)
{
  if($Condicion== 0)
    $scondicion = "Alquiler";
  else
    $scondicion = "Propio";

  if($EstadoOperativo == 0)
    $sestadoO = "Malo";
  if($EstadoOperativo == 1)
    $sestadoO = "Regular";
  if($EstadoOperativo == 2)
    $sestadoO = "Bueno";
}

 function mostrarEquipo()
  {
  	global $modelinventarioequipo ,$modelequipo,$unidad,$nombreUnidad,$responsable,$direccion,$nomfacul,$actividad;
  	 $output = '<thead>
  	              <tr>
  	              <th>Nº</th>
                  <th>Articulo/Descripcion</th>
                  <th>Marca/Modelo</th>
                  <th>Nº DE SERIE</th>
                  <th>AÑO DE FABRICACION </th>
                  <th>Fecha de Ingreso</th>
                  <th>CONDICION(Alquiler-Propio)</th>
                  <th>ESTADO OPERATIVO</th>
                  <th>OBSERVACIONES</th>
                </thead>
        <tbody>';
        $dos=33;
//una sentencia mejor de sql me evitaria esto en getInventarioOd
    foreach($modelinventarioequipo->getInventarioId($unidad->Id) as $r): 
      $output .='nosde';
      $observaciones=$r->Observaciones;
      $fechaingreso = $r->Fecha_Ingreso;
      if($r->Condicion == 1)
        $Condicion='Propio';
      else
        $Condicion='Alquiler';
      $SCondicion="";
      $SEstadoO="";
      numerosastring($r->Condicion,$r->EstadoOperativo,$SCondicion,$SEstadoO);
      $marcamodelo =$r->Marca;
      $marcamodelo .=" - ";
      $marcamodelo .= $r->Modelo;
      $output .= '<tr>
                <td>'.$r->Id.'</td>
                <td>'.$r->Descripcion.'</td>
                <td>'.$marcamodelo.'</td>
                <td>'.$r->NumeroSerie.'</td>
                <td>'.$r->Fecha_Fabricacion.'</td>
                <td>'.$r->Fecha_Ingreso.'</td>
                <td>'.$SCondicion.'</td>
                <td>'.$SEstadoO.'</td>
                <td>'.$r->Observaciones.'</td>
                </tr> ';
	  endforeach;
	$dos=2016;
	$codigo=2223;
	$output.= '</tbody>';
	$html = '<div style="border:1px solid black;"> <header>
    <h1>'.UNIVERSIDAD.'</h1>
    <h2>INVENTARIO MAQUINARIA Y EQUIPOS</h2>
    <div id="project">
      <table class="tableHeaderReport">
          <tr>
            <td><div><span>CENTRO DE PRODUCCIÓN: </span> '.$nombreUnidad.'</div></td>
          </tr> 
          <tr>
            <td><div><span>PERIODO: </span>'.$dos.'</div></td>
            <td><div><span>FACULTAD : </span>'.$nomfacul.'</div></td>
            <td><div><span>RESPONSABLE: </span> '.$responsable .'</div></td>
          </tr> 
          <tr>
            <td></td>
            <td><div><span>ACTIVIDAD :</span>'.$actividad .'</div></td>
            <td><div><span>DIRECCION: </span> '.$direccion.'</div></td>
          </tr> 
      </table>
    </div>
    </header>
  <main>
    <table border="1">'.$output.'</table>
  </main></div>';

    $mpdf = new mPdf('c', 'A4-L');
    $css = file_get_contents('Views/Reportes/css/style.css');
    $mpdf->writeHTML($css, 1);
    $mpdf->writeHTML($html);
    $mpdf->Output('reporte.pdf', 'I');
 }
 mostrarEquipo();
?>