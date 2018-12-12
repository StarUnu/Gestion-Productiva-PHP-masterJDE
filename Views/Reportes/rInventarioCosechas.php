<?php 
//esta en la Fac. Cs. Biológicas y Agropecuarias tiene que haber un if y hay otro queu es de bienes
//como hacer para que me lo imprima una parte en el index
//posdata ya sale pero no lo logro todavia hacer funcionar
//falta dentrar los datos 
//esto incluye a las tablas de inventario de animales y el de inventario de cosechas y otros 
//sale error recuerdadadad
require_once('Core/pdf/mpdf.php');

global $modelinventariocosecha;
global $modeltipocosecha;
global $modelcosecha;
global $unidad;
global $responsable ; 
global $nombreUnidad;
global $direccion;
global $nomfacul;
global $actividad;
global $claseinventario;//1 Animales
global $nomfacul;

$modelinventariocosecha= new InventarioCosecha();
$modelcosecha = new Cosecha();
$modeltipocosecha = new TipoCosecha();

$unidad = $this->modelUnidadProductiva->Obtener($_GET['Unidad']);
$nombreUnidad = $unidad->Nombre;
$responsable = $this->modelUnidadProductiva->getResponsableByDni($unidad->Persona_Dni);
$direccion = $this->modelUnidadProductiva->getRespondable($unidad->Persona_Dni)->Direccion; 
$nomfacul = $this->modelUnidadProductiva->getRespondable($unidad->Persona_Dni)->Direccion; 
$claseinventario = $this->claseinventario;
$actividad =$this->modelUnidadProductiva->getRubroById($unidad->Rubro_Id);
$nomfacul="Fac. Cs. Biológicas y Agropecuarias";
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
  	global $modelinventariocosecha ,$modelcosecha,$unidad,$nombreUnidad,$responsable,$direccion,$claseinventario,$actividad,$modeltipocosecha,$nomfacul ;
    $dos =2;
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
    
    foreach($modelinventariocosecha->obtenerbyunidad($unidad->Id) as $r): 
      $cantidadt=0;
         $edad =$ytoday - floatval($r->FechaIngreso);
        $cantidadt=$modelinventariocosecha->getcantidadbyin($r->Id);
      $EstadoOperativo='';
    	$output .= '<tr>
              <td>'.$r->Id .'</td>
              <td>'.$r->Descripcion.'</td>
              <td>'.$cantidadt.'</td>
              <td>'.$modeltipocosecha->Obtener($r->TipoCosecha_Id)->Descripcion.'</td>';
      $good=$modelcosecha->getbyestadogood($r->Id);
      $bad=$modelcosecha->getbyestadobad($r->Id);
      $regular=$modelcosecha->getbyestadoregular($r->Id);
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
    <h1>'.UNIVERSIDAD.'</h1> <h2>INVENTARIO DE COSECHA Y OTROS</h2> <div id="project">
      <table class="tableHeaderReport">
          <tr>';
  $html .='<td><div><span>CENTRO DE PRODUCCIÓN: </span>'.$nombreUnidad.'</div></td>
          </tr> 
          <tr>
            <td><div><span>PERIODO: </span>'.$dos.'</div></td>
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