<?php 
	require_once("Core/Session.php");
	require_once ('Models/InventarioBienesModel.php');
	require_once("Models/BienesModel.php");
	require_once("Models/TipoMaterialModel.php");

	class InventarioBienesController
	{
		private $model;
		public $Inventariobienes;
    public $bienes;
		public $auxTable;
		public $modelbien;
		public $modeltipomaterial;
		public function __construct()
		{
			$this->model    = new InventarioBienes();
			$this->modelbien= new Bienes();
			$this->auxTable = "InventarioBienes"; 
			$this->modeltipomaterial = new TipoMaterial();
		}
		public function Index()
		{
			$Inventariobienes = new InventarioBienes();

				if( isset($_SESSION['Unidad_Id'])){
		            $unidadid=$_SESSION['Unidad_Id'];
		            $totalRecords=$this->model->ObtenerNombreUnidadProductivaAray($unidadid);
		        }
		        else
		        {
		            $totalRecords = $this->model->getTotalRecords();//el grado el numero de fila
		        }

		        $totalPages = ceil($totalRecords/resultsPerPage);//este da el màs alto valor es el techo
		        if (isset($_GET["page"])) { $page  = $_GET["page"]; } else { $page=1; }; 
		        
		        $startFrom = ($page-1) * resultsPerPage;

			    require_once 'Views/header.php';
			    require_once 'Views/sidebar.php';
			    require_once 'Views/panel.php';
			    require_once 'Views/InventarioBienes/index.php';
			    require_once 'Views/footer.php';
		}
		public function Crud()
		{
			$Inventariobienes = new InventarioBienes();
      $bienes = new Bienes();
			if(isset($_REQUEST['Id']))
			{
				$Inventariobienes = $this->model->Obtener($_REQUEST['Id']);
        $bienes =$this->modelbien->obtenerporidinventario($_REQUEST['Id']);
			}
		   require_once 'Views/header.php';
		   require_once 'Views/sidebar.php';
		   require_once 'Views/panel.php';
		   require_once 'Views/InventarioBienes/update.php';
		   require_once 'Views/footer.php';
		}

    public function Paginacion(){
        if (isset($_GET["search"])) { $search  = $_GET["search"]; } else { $search=''; };  //aca le da el texto
        if (isset($_GET["page"])) { $page  = $_GET["page"]; } else { $page=1; };  
        $startFrom = ($page-1) * resultsPerPage;
    require_once 'Views/InventarioBienes/paginationsearch.php';
    }

    public function Buscar()
    {
        $search = '';
        $unidad = -1;

        if (isset($_POST["search"])) { $search  = $_POST["search"]; } else { $search=''; };  
        if (isset($_POST["unidad"])) { $unidad  = $_POST["unidad"]; } else { $unidad=-1; };  
        if (isset($_GET["page"])) { $page  = $_GET["page"]; } else { $page=1; };  
        if ($unidad==-1 ) //quiere decir que esta seleccionado ----todos----
        {
            $Inventariobienes = new InventarioBienes();
            $totalRecords = $this->model->getTotalRecordsBusqueda($search);
            $totalPages = ceil($totalRecords/resultsPerPage);
            $startFrom = ($page-1) * resultsPerPage;
            require_once 'Views/InventarioBienes/fetch.php';    
        } else {
            $Inventariobienes = new InventarioBienes();
            $totalRecords = $this->model->getTotalRecordsBusquedaByUnidad($unidad,$search);
            $totalPages = ceil($totalRecords/resultsPerPage);
            $startFrom = ($page-1) * resultsPerPage;
            require_once 'Views/InventarioBienes/fetch.php';    
        }
    }

		public function Guardar()
		{
			
            $contador2 = 0;
            $bienestemporales = array();

            //recogiendo los datos tsp
            $bien='';
            $contador = 0;
            foreach ($_REQUEST['IdBien1'] as $detalleid ) {
               $bienestemporales[$contador]=array($detalleid, "");
               $string = var_dump($bienestemporales[$contador]);
               $contador++;
            }

            $contador=0;
            foreach ($_REQUEST['Cantidad1'] as $detalledes ) {
               $bienestemporales[$contador][1]=$detalledes;
               $contador++;               
            }
            $contador=0;
            foreach ($_REQUEST['Estado'] as $detalledes ) {
               $bienestemporales[$contador][2]=$detalledes;
               $contador++;               
            }

      $canti = count($bienestemporales);
			$Inventariobienes= new InventarioBienes();

			$Inventariobienes->Id=$_REQUEST['Id'];
      $Inventariobienes->Unidad_Id = $_SESSION['Unidad_Id']; 
      $Inventariobienes->Descripcion =$_REQUEST['Descripcion'];
      $Inventariobienes->EstadoOperativo =$_REQUEST['EstadoOperativo'];
      $Inventariobienes->Observaciones =$_REQUEST['Observaciones'];
      $Inventariobienes->TipoMaterial_Id =$_REQUEST['TipoMaterial_Id'];
			$idinventariobienes = $Inventariobienes->Id;
			if($Inventariobienes->Id > 0){
 				$bienestmp=$this->modelbien->obtenerporidinventarioarra($idinventariobienes);
 				$lastIdInventarioBienes = $this->model->Actualizar($Inventariobienes); 
 				$Inventariobienes = new InventarioBienes();
 				$tamDT = count($bienestmp);

 				//tres copias recuerda!!
				for ($i=0; $i < $contador; $i++) { 
                //actualizar los detalles que ya estan en la base de datos
                for ($j=0; $j < $tamDT; $j++) { 
                    if ($bienestemporales[$i][0] == $bienestmp[$j][0]){
                    	$id11 = var_dump($bienestmp[$j][0]);
                    	$bienestemp22 = var_dump($bienestemporales[$i][0]);
                        $detalle = new Bienes();
                        $detalle->Id = $bienestemporales[$i][0];
                        $detalle->Cantidad = $bienestemporales[$i][1];
                        $detalle->Estado = $bienestemporales[$i][2];
                        $detalle->InventarioBien_Id =$idinventariobienes ;
                        $this->modelbien->Actualizar($detalle);////////hasta aqui me quede
                        unset($bienestemporales[$i]); //Eliminar de los nuevos detalles
                        unset($bienestmp[$j]);
      					$bienestmp = array_values($bienestmp);
                        break;
                    }
                  } 
               		$tamDT =count($bienestmp);
           		}

           		$tamano =count($bienestmp);

               for ($i=0; $i < count($bienestmp); $i++) { 
                    $this->modelbien->Eliminar($bienestmp[$i][0]);    
                }

           		$canti = count($bienestemporales);
              	$bienestemporales = array_values($bienestemporales); 
              	for ($i=0; $i <$canti ; $i++) { 
                    $nuevo_bien = new Bienes();
                    $nuevo_bien->Id = $bienestemporales[$i][0];
                    $nuevo_bien->Cantidad = $bienestemporales[$i][1];
                    $nuevo_bien->Estado = $bienestemporales[$i][2];
                    $nuevo_bien->InventarioBien_Id =$idinventariobienes ;//esto estaba mal
                    $this->modelbien->Registrar($nuevo_bien);
                }
           	}
           	else
           	{
           		$lastIdInventarioBien2 = $this->model->Registrar($Inventariobienes);
           		$var =count($bienestemporales);
                for ($i=0; $i < $var; $i++) { 
                    $detalle = new Bienes();
                    $detalle->Id = $bienestemporales[$i][0];
                    $detalle->Cantidad = $bienestemporales[$i][1];
                    $detalle->Estado = $bienestemporales[$i][2];
                    $detalle->InventarioBien_Id =$lastIdInventarioBien2;
                    $this->modelbien->Registrar($detalle);
                }

           	}
    header('Location:'.BASE_URL.'InventarioBienes');
		}
		public function Eliminar()
		{
			$this->model->Eliminar($_REQUEST['Id']);
			header('Location:'.BASE_URL.'InventarioBienes');
		}

		public function Pagination(){
            if (isset($_GET["page"])) { $page  = $_GET["page"]; } else { $page=1; };  
            $startFrom = ($page-1) * resultsPerPage;
            require_once 'Views/InventarioBienes/pagination.php';
        }
 
	}
?>