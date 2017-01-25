<?php 
//esta en la Fac. Cs. Biológicas y Agropecuarias tiene que haber un if y hay otro queu es de bienes
//como hacer para que me lo imprima una parte en el index
//posdata ya sale pero no lo logro todavia hacer funcionar
//falta dentrar los datos 
//esto incluye a las tablas de inventario de animales y el de inventario de cosechas y otros 
//sale error recuerdadadad
require_once('Core/pdf/mpdf.php');

global $modelinventarioanimales;
global $modeltipoanimal;
global $modelanimales;
global $unidad;
global $responsable ; 
global $nombreUnidad;
global $direccion;
global $nomfacul;
global $actividad;
global $claseinventario;//1 Animales
global $periodo;
global $nomfacul;
global $periodo;
$modelinventarioanimales= new InventarioAnimales();
$modelanimales = new Animales();
$modeltipoanimal = new TipoAnimal();

$unidad = $this->modelUnidadProductiva->Obtener($_GET['Unidad']);
$nombreUnidad = $unidad->Nombre;
$responsable = $this->modelUnidadProductiva->getResponsableByDni($unidad->Persona_Dni);
$direccion = $this->modelUnidadProductiva->getRespondable($unidad->Persona_Dni)->Direccion; 
//$nomfacul = $unidad->Facultad_id;  
$nomfacul=$this->modelUnidadProductiva->getFacultadbyId($unidad->Facultad_Id)->Nombre;
$claseinventario = $this->claseinventario;
$actividad =$this->modelUnidadProductiva->getRubroById($unidad->Rubro_Id);
$periodo = $_GET['Periodo1'];
echo "ahra loas imprime $periodo sdsd";
function Estados($EstadoOperativoO,& $EstadoOperativo)
{ 
  if($EstadoOperativoO==0 )
    $EstadoOperativo.='Malo';
  if($EstadoOperativoO==1 )
    $EstadoOperativo.='Regular';
  if($EstadoOperativoO==2 )
    $EstadoOperativo.='Bueno';
}

 function mostrarDocumento()
  {
  	global $modelinventarioanimales ,$modelanimales,$unidad,$nombreUnidad,$responsable,$direccion,$claseinventario,$actividad,$modeltipoanimal,$periodo,$nomfacul ;
    $dos =2016;
    $output = '<thead>
                <tr>
                <th rowspan="2" >Nº</th>
                <th rowspan="2" >Descripcion</th>
                <th rowspan="2" >CANTIDAD</th> 
                <th rowspan="2" >RAZA/MARCA</th>
                <th colspan ="3" >ESTADO</th>
                <th rowspan="2" >Edad </th>
                <th rowspan="2" >OBSERVACIONES</th>
                </tr>
                <tr>
                <th >B</th>
                <th >R</th>
                <th >M</th>
                </tr>
              </thead>
        <tbody>';                
    // completo la dudas como yo pienso la descripcion debe ser de cada inventariode bien
    $ytoday = date('Y'); 
    
    foreach($modelinventarioanimales->obtenerbyunidad($unidad->Id,$periodo) as $r): 
      $cantidadt=0;
         $edad =$ytoday - floatval($r->FechaIngreso);
        $cantidadt=$modelinventarioanimales->getcantidadbyin($r->Id);
      $EstadoOperativo='';
    	$output .= '<tr>
              <td>'.$r->Id .'</td>
              <td>'.$r->Descripcion.'</td>
              <td>'.$cantidadt.'</td>
              <td>'.$modeltipoanimal->Obtener($r->tipoanimal_id)->Descripcion.'</td>';
      $good=$modelanimales->getbyestadogood($r->Id);
      $bad=$modelanimales->getbyestadobad($r->Id);
      $regular=$modelanimales->getbyestadoregular($r->Id);
      $output .= '<td>'. $good.'</td>
                  <td>'.$bad.'</td>
                  <td>'.$regular.'</td>';              
        Estados($r->EstadoOperativo,$EstadoOperativo);
        $output .='<td>'.$edad.'</td>
                  <td>'. $r->Observaciones.'</td>	 
                  </tr> ';
	endforeach; 
	$dos=2016;
	$codigo=2223;
	$output.= '</tbody>';
	$html = '<div style="border:1px solid black;"> <header>
    <h1>'.UNIVERSIDAD.'</h1> <h2>INVENTARIO DE ANIMALES </h2> <div id="project">
      <table class="tableHeaderReport">
          <tr>';
  $html .='<td><div><span>CENTRO DE PRODUCCIÓN: </span>'.$nombreUnidad.'</div></td>
          </tr> 
          <tr>
            <td><div><span>PERIODO: </span>'.$periodo.'</div></td>
          </tr> 
          <tr>
            <td><div><span>FACULTAD : </span>'.$nomfacul.'</div></td> 
            <td><div><span>ACTIVIDAD :</span>'.$actividad .'</div></td>
          </tr> 
          <tr> 
            <td><div><span>RESPONSABLE: </span> '.$responsable .'</div></td>
            <td><div><span>DIRECCION: </span> '.$direccion .'</div></td>
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

	mostrarDocumento()
?>