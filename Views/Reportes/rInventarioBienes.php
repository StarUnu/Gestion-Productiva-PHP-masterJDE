<?php 
//esta en la Fac. Cs. Biológicas y Agropecuarias tiene que haber un if y hay otro queu es de bienes
//como hacer para que me lo imprima una parte en el index
//posdata ya sale pero no lo logro todavia hacer funcionar
//falta dentrar los datos 
//esto incluye a las tablas de inventario de animales y el de inventario de cosechas y otros 
//sale error recuerdadadad
require_once('Core/pdf/mpdf.php');

global $modelinventariobienes;
global $modelbien ;
global $unidad;
global $responsable ; 
global $nombreUnidad;
global $direccion;
global $nomfacul;
global $actividad;
global $claseinventario;
global $modeltipomaterial;
global $nomfacul;
$modelinventariobienes= new InventarioBienes();
$modelbien = new Bienes();
$modeltipomaterial = new TipoMaterial();
$unidad = $this->modelUnidadProductiva->Obtener($_GET['Unidad']);
$nombreUnidad = $unidad->Nombre;
$responsable = $this->modelUnidadProductiva->getResponsableByDni($unidad->Persona_Dni);
$direccion = $this->modelUnidadProductiva->getRespondable($unidad->Persona_Dni)->Direccion; 
$nomfacul = $this->modelUnidadProductiva->getRespondable($unidad->Persona_Dni)->Direccion; 
$claseinventario = $this->claseinventario;
$actividad =$this->modelUnidadProductiva->getRubroById($unidad->Rubro_Id);
$nomfacul =$this->modelUnidadProductiva->getFacultadbyId($unidad->Facultad_Id)->Nombre;
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
  	global $modelinventariobienes ,$modelbien,$unidad,$nombreUnidad,$responsable,$direccion,$claseinventario,$actividad,$modeltipomaterial,$nomfacul ;
    $dos =2;
    $output = '<thead>
                <tr>
                <th rowspan="2" >Nº</th>
                <th rowspan="2" >Descripcion</th>
                <th rowspan="2" >CANTIDAD</th> 
                <th rowspan="2" >TIPO DE MATERIAL</th>
                <th colspan ="3" >ESTADO</th>
                <th rowspan="2" >ESTADOOPERATIVO</th>
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
    foreach($modelinventariobienes->getunidadByidAll($unidad->Id) as $r): 
    	//$marcamodelo.=$modelequipo->getEquipoporId($r->Id);
    	//$marcamodelo.=$r->Modelo;
      //esto es de calidad
      echo "asamsoalsmals";
      $EstadoOperativo='';
    	$output .= '<tr>
              <td>'.$r->Id .'</td>
              <td>'.$r->Descripcion.'</td>
              <td>'.$modelinventariobienes->getcantidadbyin($r->Id).'</td>
              <td>'.$modeltipomaterial->Obtenertipomaterialid($r->TipoMaterial_Id)->Descripcion.'</td>';
    echo "asjanksnakaerikasasTSP";
    $good=$modelbien->getbyestadogood($r->Id);
    $bad=$modelbien->getbyestadobad($r->Id);
    $regular=$modelbien->getbyestadoregular($r->Id);
    $output .= '<td>'. $good.'</td>
                <td>'.$bad.'</td>
                <td>'.$regular.'</td>';              
      Estados($r->EstadoOperativo,$EstadoOperativo);
      $output .='<td>'.$EstadoOperativo.'</td>
                <td>'.$r->Observaciones.'</td>	 
                </tr> ';
	endforeach; 
	$dos=2016;
	$codigo=2223;
	$output.= '</tbody>';
	$html = '<div style="border:1px solid black;"> <header>
    <h1>'.UNIVERSIDAD.'</h1> <h2>INVENTARIO DE BIENES MUEBLES E INMUEBLES </h2> <div id="project">
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