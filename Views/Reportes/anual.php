<!doctype>
<html>
<head>
</head>
<body>

<?php
require_once 'Core/PHPExcel/PHPExcel.php';
require_once 'Core/PHPExcel/PHPExcel/IOFactory.php';

date_default_timezone_set('America/Mexico_City');

if (PHP_SAPI == 'cli')
	die('Este archivo solo se puede ver desde un navegador web');

/** Se agrega la libreria PHPExcel */
require_once 'lib/PHPExcel/PHPExcel.php';

// Se crea el objeto PHPExcel
$objPHPExcel = new PHPExcel();

// Se asignan las propiedades del libro
$objPHPExcel->getProperties()->setCreator("Gestion Productiva") //Autor
					 ->setLastModifiedBy("GestionProductiva") //Ultimo usuario que lo modificó
					 ->setTitle("Reporte Anual de Presupuestos")
					 ->setSubject("Reporte Anual de Presupuestos")
					 ->setDescription("Reporte de Presupuestos")
					 ->setKeywords("reporte Presupuestos ingresos")
					 ->setCategory("Reporte excel");

$tituloReporte = "Reporte Anual de Presupuestos";
$titulosColumnas = array('NRO. ITEM', 'CENTRO / INSTITUTO / UNIDAD (*)', 'Cuenta con información financiera? Sí/No', 'Indique los tipos de estados financieros de manejo interno', '');

$objPHPExcel->setActiveSheetIndex(0)
		    ->mergeCells('B2:L2');
$objPHPExcel->mergeCells('B2:B3')
$objPHPExcel
				
// Se agregan los titulos del reporte
$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('A1',$tituloReporte)
		    ->setCellValue('A3',  $titulosColumnas[0])
            ->setCellValue('B3',  $titulosColumnas[1])
		    ->setCellValue('C3',  $titulosColumnas[2])
    		->setCellValue('D3',  $titulosColumnas[3]);

//Se agregan los datos de los alumnos
$i = 4;
while ($fila = $resultado->fetch_array()) {
	$objPHPExcel->setActiveSheetIndex(0)
		    ->setCellValue('A'.$i,  $fila['Nombres'])
            ->setCellValue('B'.$i,  $fila['Nacimiento'])
		    ->setCellValue('C'.$i,  $fila['Genero'])
    		->setCellValue('D'.$i, utf8_encode($fila['Telefono']));
			$i++;
}

$estiloTituloReporte = array(
	'font' => array(
    	'name'      => 'Verdana',
        'bold'      => true,
	    'italic'    => false,
        'strike'    => false,
       	'size' =>16,
        	'color'     => array(
            	'rgb' => 'FFFFFF'
	       	)
    ),
    'fill' => array(
		'type'	=> PHPExcel_Style_Fill::FILL_SOLID,
		'color'	=> array('argb' => 'FF220835')
	),
    'borders' => array(
       	'allborders' => array(
        	'style' => PHPExcel_Style_Border::BORDER_NONE                    
       	)
    ), 
    'alignment' =>  array(
			'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
			'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
			'rotation'   => 0,
			'wrap'          => TRUE
	)
);

$estiloTituloColumnas = array(
    'font' => array(
        'name'      => 'Arial',
        'bold'      => true,                          
        'color'     => array(
            'rgb' => 'FFFFFF'
        )
    ),
    'fill' 	=> array(
		'type'		=> PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,
		'rotation'   => 90,
		'startcolor' => array(
    		'rgb' => 'c47cf2'
		),
		'endcolor'   => array(
    		'argb' => 'FF431a5d'
		)
	),
    'borders' => array(
    	'top'     => array(
            'style' => PHPExcel_Style_Border::BORDER_MEDIUM ,
            'color' => array(
                'rgb' => '143860'
            )
        ),
        'bottom'     => array(
            'style' => PHPExcel_Style_Border::BORDER_MEDIUM ,
            'color' => array(
                'rgb' => '143860'
            )
        )
    ),
	'alignment' =>  array(
			'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
			'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
			'wrap'          => TRUE
	));
	
$estiloInformacion = new PHPExcel_Style();
$estiloInformacion->applyFromArray(
	array(
   		'font' => array(
       	'name'      => 'Arial',               
       	'color'     => array(
           	'rgb' => '000000'
       	)
   	),
   	'fill' 	=> array(
		'type'		=> PHPExcel_Style_Fill::FILL_SOLID,
		'color'		=> array('argb' => 'FFd9b7f4')
	),
   	'borders' => array(
       	'left'     => array(
           	'style' => PHPExcel_Style_Border::BORDER_THIN ,
            'color' => array(
            	'rgb' => '3a2a47'
           	)
       	)             
   	)
));
 
$objPHPExcel->getActiveSheet()->getStyle('A1:D1')->applyFromArray($estiloTituloReporte);
$objPHPExcel->getActiveSheet()->getStyle('A3:D3')->applyFromArray($estiloTituloColumnas);		
$objPHPExcel->getActiveSheet()->setSharedStyle($estiloInformacion, "A4:D".($i-1));
		
for($i = 'A'; $i <= 'D'; $i++){
	$objPHPExcel->setActiveSheetIndex(0)			
		->getColumnDimension($i)->setAutoSize(TRUE);
}

// Se asigna el nombre a la hoja
$objPHPExcel->getActiveSheet()->setTitle('Alumnos');

// Se activa la hoja para que sea la que se muestre cuando el archivo se abre
$objPHPExcel->setActiveSheetIndex(0);
// Inmovilizar paneles 
//$objPHPExcel->getActiveSheet(0)->freezePane('A4');
$objPHPExcel->getActiveSheet(0)->freezePaneByColumnAndRow(0,4);

// Se manda el archivo al navegador web, con el nombre que se indica (Excel2007)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Reportedealumnos.xlsx"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');
exit;

?>
</body>
</html>