<?php 
//OJO ACA FALTA DE LA TABLA 10 y LA TABLA 6
require_once('Core/pdf/mpdf.php');

/*$mpdf = new mPDF('c','A4');//tamaño de carta  y va hacer de hoja A4
$mpdf->writeHTML('<p> Para ser facil </p>');
$mpdf->Output('reporteinventariofisico.pdf','I');*/
global  $modelinventariofisicode ;
global  $modelunidadadmedida ; 
global  $modelMaterialInsumo;
global  $modelTipoExistencia;
global $modelinventariofisico;
global $unidad;
global $responsable;
global $nombreUnidad;
//suponiendo que en el selector no esta la unidadcentral 

$modelinventariofisico= new InventarioFisico();
$modelinventariofisicode = new InventarioFisicoDe();
$modelunidadadmedida = new UnidadMedida();
$modelMaterialInsumo = new MateriaL_Insumo();
$modelTipoExistencia = new TipoExistencia() ;
$unidad = $this->modelUnidadProductiva->Obtener($_GET['Unidad']);
$nombreUnidad = $unidad->Nombre;
$responsable = $this->modelUnidadProductiva->getResponsableByDni($unidad->Persona_Dni);

function comparar($array,$Id)
{
  foreach ($array as $r) {
    if($r == $Id)
      return 0;
  }
  return 1;
}

 function mostrarDocumento()
  {
  	global $modelinventariofisico, $modelinventariofisicode, $modelunidadadmedida, $responsable, $modelMaterialInsumo ,$ytoday ,$unidad ,$nombreUnidad,$responsable;
    $output = '<thead>
            <tr>
              <th rowspan="2" >Nº</th>
              <th rowspan="2" >Descripcion</th>
              <th rowspan="2" >Cantidad</th>
              <th rowspan="2" >Raza/Marca</th>
              <th colspan = "3"> Estado </th>
              <th rowspan="2" >Edad</th>
              <th rowspan="2">Observaciones</th>
            </tr>
            <tr>
            <th>B</th>
            <th>R</th>
            <th>M</th>
            </tr>
        </thead>
        <tbody>';
        $ytoday = date('Y');//el año actual
    foreach($modelinventariofisico->getAllbyunidad($unidad->Id) as $r):
      /*$fecha = $r->Edad;
      $año;
      for($fecha as $s):
        if($s=='-')
          $año.=$s
        else*/
      $arrayinventariofide = $modelinventariofisicode->getDetallesByIFisicoId($r->Id);
      echo "edad -> $r->Periodo hoye s $ytoday aaa $arrayinventariofide";
      $prueba2 = $modelinventariofisicode->Obtener($r->Codigo_Existencia);
      $cantidad = $modelinventariofisico->getCantidadDetalle($r->Id);
      //$marca = 
      $edad =$ytoday-floatval($r->Periodo);
      $found = true;
      $output .= '<tr>
              <td>'.$r->Id.'</td>
              <td>'.$r->Descripcion_Existencia.'</td>
              <td>'.$cantidad.'</td>
              <td>';
      $array = array();
      $cont=0;              
        foreach($arrayinventariofide as $t):
                if(comparar($array,$t->Material_Insumo_Id))
                  $output .= $modelMaterialInsumo->Obtener($t->Material_Insumo_Id)->Marca;
                   $output .=" ";

                $array[$cont] = $t->Material_Insumo_Id;
                $cont++;
        endforeach;
        $arraycanti = $modelinventariofisicode->cantidades_estado($r->Id);
        $output .='</td> 
                   <td>'.$arraycanti[0].'</td>
                   <td>'.$arraycanti[1].'</td>
                   <td>'.$arraycanti[2].'</td>
                   <td>'.$edad.'</td> <td> ';
        

        foreach ($arrayinventariofide as $t):
                $output .=$t->Observaciones ;
                $output .=" " ;
        endforeach;                             
        $output .=' </td> </tr>';
        $unidadmedida = $r->UnidadMedida_Id;//esto lo estoy dudando
        $codigo = $r->Codigo_Existencia;
    endforeach;
    $dos =22;
    $output.= '</tbody>';
    $html = '<div style="border:1px solid black;"> <header>
    <h1>'.UNIVERSIDAD.'</h1>
    <h2>INVENTARIO FISICO </h2>
    <div id="project">
      <table class="tableHeaderReport">
          <tr>
            <td><div><span>CENTRO DE PRODUCCIÓN: </span> '.$nombreUnidad.'</div></td>
          </tr> 
          <tr>
            <td><div><span>PERIODO: </span>'.$dos.'</div></td>
          </tr> 
          <tr>
            <td><div><span>DESCRIPCION DE LA EXISTENCIA: </span>'.$dos.'</div></td>
          </tr> 
          <tr> 
            <td><div><span>CODIGO DE EXISTENCIA :</span>'.$codigo .'</div></td>
          </tr> 
          <tr> 
            <td><div><span>CODIGO DE LA UNIDAD DE MEDIDA : </span> '.$unidadmedida .'</div></td>
            <td><div><span>RESPONSABLE: </span> '.$responsable.'</div></td>
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
 mostrarDocumento();
?>
