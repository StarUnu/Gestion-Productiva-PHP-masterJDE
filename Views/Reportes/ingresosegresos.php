<?php 
  require_once('Core/pdf/mpdf.php');

  global $modelDocumento;
  global $modelTipoDocumento;
  global $modelUnidadProductiva;
  global $modelOperacion;
  global $modelFacultad;
  global $modelRubro;
  $modelRubro = new Rubro();
  $modelFacultad = new Facultad();
  $modelDocumento = new Documento();
  $modelTipoDocumento = new TipoComprobantePago();
  $modelUnidadProductiva = new UnidadProductiva();
  $modelOperacion = new Operacion();
  global $unidad;
  global $responsable;
  global $nombreUnidad;
  global $periodo;
  $unidad = $this->modelUnidadProductiva->Obtener($_GET['Unidad']);
  $periodo = $_GET['Periodo'];
  $responsable = $this->modelUnidadProductiva->getResponsableByDni($unidad->Persona_Dni);
  $nombreUnidad = $unidad->Nombre;

  function mostrarDocumento()
  {
    global $modelDocumento, $modelTipoDocumento, $modelUnidadProductiva, $modelOperacion, $unidad, $periodo , $responsable, $nombreUnidad, $modelRubro, $modelFacultad;
    $output = '<thead>
                <tr>
                  <th>CONCEPTO</th>
                  <th>ENE</th>
                  <th>FEB</th>
                  <th>MAR</th>
                  <th>ABR</th>
                  <th>MAY</th>
                  <th>JUN</th>
                  <th>JUL</th>
                  <th>AGO</th>
                  <th>SEP</th>
                  <th>OCT</th>
                  <th>NOV</th>
                  <th>DIC</th>
                  <th>TOTAL</th>
                </tr>
            </thead>
            <tbody>';
            //Recorrer los conceptos (5 conceptos, 2 ingresos y 3 egresos)
            $output .= '<tr><td><strong>INGRESOS (A)</strong></td>';
            for ($i = 1; $i <= 13; $i++) {
                $output.='<td></td>';
            }
            $output.='</tr>';
            for ($concepto = 1; $concepto <= 2; $concepto++) { //recorrer los ingresos
                $output.='<tr>';
                $output.='<td>'.$modelOperacion->getConceptoById($concepto).'</td>';
                $total = 0;
                for ($i = 1; $i <= 12; $i++) { //Recorrer los meses
                  $tmp = $modelOperacion->getIngresosAgrupadosMesPeriodoUnidadConcepto($unidad->Id, $periodo, $i, $concepto);
                  $total += $tmp;
                  $output.='<td>'.$tmp.'</td>';
                }
                $output.='<td>'.$total.'</td>';
                $output.='</tr>';
            }


            $output .= '<tr><td><strong>EGRESOS (B)</strong></td>';
            for ($i = 1; $i <= 13; $i++) {
                $output.='<td></td>';
            }
            $output.='</tr>';
            for ($concepto = 3; $concepto <= 5; $concepto++) { //recorrer los ingresos
                $output.='<tr>';
                $output.='<td>'.$modelOperacion->getConceptoById($concepto).'</td>';
                $total = 0;
                for ($i = 1; $i <= 12; $i++) { //Recorrer los meses
                  $tmp = $modelOperacion->getIngresosAgrupadosMesPeriodoUnidadConcepto($unidad->Id, $periodo, $i, $concepto);
                  $total +=$tmp;
                  $output.='<td>'.$tmp.'</td>';  
                }
                $output.='<td>'.$total.'</td>';
                $output.='</tr>';
            }
          
    $output.= '</tbody>';

    $html = '<div style="border:1px solid black;"> <header>
        <h1>'.UNIVERSIDAD.'</h1>
        <h2>INGRESOS Y GASTOS</h2>
        <div id="project">

          <table class="tableHeaderReport">
            
              <tr>
                <td><div><span>CENTRO DE PRODUCCIÓN: </span> '.$nombreUnidad.'</div></td>
              </tr>
              <tr>
                <td><div><span>PERIODO: </span>'.$periodo.'</div></td>
                <td><div><span>FACULTAD: </span>'.$modelFacultad->Obtener($unidad->Facultad_Id)->Nombre.'</div></td>
                <td><div><span>RESPONSABLE: </span>'.$responsable.'</div></td>
                
              </tr>          
              <tr>
                <td><div><span>(Expresado en soles)</span></div></td>
                <td><div><span>ACTIVIDAD: </span>'.$modelRubro->Obtener($unidad->Rubro_Id)->Descripcion.'</div></td>
                <td><div><span>DIRECCIÓN: </span> '.$unidad->Ubicacion.'</div></td>
              </tr>
          </table>
        </div>
      </header>
      <br>
      <main>
        <table border="1">'.$output.'</table>
      </main></div>';

    $mpdf = new mPdf('c', 'A4-L');
    $css = file_get_contents('Views/Reportes/css/style.css');
    $mpdf->writeHTML($css, 1);
    $mpdf->writeHTML($html);
    $mpdf->Output('reporte.pdf', 'I');
  }

  if (!isset($_SESSION['TipoUsuario']))
  {
    echo 'No tienes permisos para acceder al contenido, logeate primero';
  }
  else
  {
    if ($_SESSION['TipoUsuario']==0 )
    {
        if (isset($_SESSION['Unidad_Id'])) {
          if ($_SESSION['Unidad_Id'] == $_REQUEST['Unidad'])
          {
            mostrarDocumento();
          } else {
            echo "No tienes permisos para ver este documento";
          }
        } else {
          echo "No tienes permisos para acceder al contenido, logeate primero";
        }
    } else {
        mostrarDocumento();
    }
  }
?>