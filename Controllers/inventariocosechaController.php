<?php 
	require_once("Core/Session.php");
    require_once ('Models/InventarioCosechaModel.php');
    require_once ('Models/CosechaModel.php');
    require_once ('Models/TipoCosechaModel.php');
	
	class InventarioCosechaController
	{
        public $Inventariocosecha;
        public $modeltipocosecha;
		function __construct()
		{
			$this->auxTable    = "InventarioCosecha";    
            $this->model       = new InventarioCosecha();
            $this->modelcosecha = new Cosecha();
            $this->modeltipocosecha = new TipoCosecha();
		}
		public function Index()
		{
			$Inventariocosecha = new InventarioCosecha();
			if(isset($_SESSION['Unidad_Id'])) 
			{
				$unidadid=$_SESSION['Unidad_Id'];
                $totalRecords=$this->model->getTotalRecordsporUnidad($unidadid);
            }
            else
            {
                $totalRecords = $this->model->getTotalRecords();//el grado el numero de fila
            }
            $totalPages = ceil($totalRecords/resultsPerPage);//este da el màs alto valor es el techo
            if (isset($_GET["page"])) 
                { $page  = $_GET["page"]; } else { $page=1; }; 
            $startFrom = ($page-1) * resultsPerPage;
            
            require_once 'Views/header.php';
            require_once 'Views/sidebar.php';
            require_once 'Views/panel.php';
            require_once 'Views/InventarioCosecha/index.php';
            require_once 'Views/footer.php';
		}
		public function Crud(){//es donde se llena los datos
            $Inventariocosecha = new InventarioCosecha();
            $modeltipocosecha = new TipoCosecha();///aca sale error
            if(isset($_REQUEST['Id'])){
                $Inventariocosecha= $this->model->Obtener($_REQUEST['Id']);
            }

            $incremento=0;
            require_once 'Views/header.php';
            require_once 'Views/sidebar.php';
            require_once 'Views/panel.php';
            require_once 'Views/InventarioCosecha/update.php';
            require_once 'Views/footer.php';
        }

        public function Guardar(){
            $Inventariocosecha = new InventarioCosecha();
            $contador = 0;
            $contador2 = 0;
            $detallestemp = array();

            //recogiendo los datos tsp
            $detalle='';
            foreach ($_REQUEST['IdAnimal1'] as $detalle ) {
               $detallestemp[$contador]=array($detalle, "");
               $contador++;
            }

            $contador=0;
            foreach ($_REQUEST['Cantidad1'] as $cadetalle ) {
               $detallestemp[$contador][1]=$cadetalle;
               echo "las catnidaddes son $cadetalle ed";
               $contador++;               
            }
            $contador=0;
            foreach ($_REQUEST['Estado1'] as $obdetalle ) {
               $detallestemp[$contador][2]= $obdetalle;
               $contador++;               
            }
            $Inventariocosecha = new InventarioCosecha();
            $Inventariocosecha->Id = $_REQUEST['Id'];//porque el request rescato con otro
            $Inventariocosecha->Descripcion = $_REQUEST['Descripcion']; 
            $Inventariocosecha->Observaciones = $_REQUEST['Observaciones'];
            $Inventariocosecha->Fechaingreso= $_REQUEST['FechaIngreso'];
            $Inventariocosecha->TipoCosecha_Id= $_REQUEST['tipoanimal'];
            $Inventariocosecha->Unidad_Id= $_SESSION['Unidad_Id']; 
            if($Inventariocosecha->Id > 0){
                $detallesTemporales = $this->modelcosecha->obtenerporidinventarioarray($Inventariocosecha->Id);
                 $lastIdInventariocosecha = $this->model->Actualizar($Inventariocosecha); 
                 //$Inventariocosecha = new InventarioCosecha();
                 $tamDT = count($detallesTemporales);
                 for ($i=0; $i < $contador; $i++) { 
                    for ($j=0; $j < $tamDT; $j++) { 
                        if ($detallestemp[$i][0] == $detallesTemporales[$j][0]){
                            $detalle = new Cosecha();
                            $detalle->Id = $detallestemp[$i][0];
                            $detalle->Cantidad = $detallestemp[$i][1];
                            $detalle->Estado = $detallestemp[$i][2];
                            $detalle->Inventariocosecha_Id =$Inventariocosecha->Id ;
                            if($detalle->Cantidad != null )
                                $this->modelcosecha->Actualizar($detalle);
                            unset($detallestemp[$i]); //Eliminar de los nuevos detalles
                            unset($detallesTemporales[$j]);
                            $detallesTemporales = array_values($detallesTemporales);
                            break;
                        }
                    } 
                   $tamDT =count($detallesTemporales);
                }  

                //Eliminamos los detalles que ya no esten en la base de datos
               for ($i=0; $i < count($detallesTemporales); $i++) { 
                    $this->modelanimales->Eliminar($detallesTemporales[$i][0]);    
                }

                //inserto los detalles  a la base de datos
                $canti = count($detallestemp);
              $detallestemp = array_values($detallestemp);
               for ($i=0; $i <$canti ; $i++) { 
                    $detalle = new Cosecha();
                    $detalle->Id = $detallestemp[$i][0];
                    $detalle->Cantidad = $detallestemp[$i][1];
                    $detalle->Estado = $detallestemp[$i][2];
                    $detalle->Inventariocosecha_Id =$Inventariocosecha->Id ;
                    $this->modelcosecha->Registrar($detalle);
                }
            }
            else{
                 $lastIdInventariocosecha = $this->model->Registrar($Inventariocosecha);
                 $cont=count($detallestemp);
                 echo "la cantidad de loop es $cont ";
                for ($i=0; $i < count($detallestemp); $i++) { 
                    $detalle = new Cosecha();
                    $detalle->Id = $detallestemp[$i][0];
                    $detalle->Cantidad = $detallestemp[$i][1];
                    $detalle->Estado =$detallestemp[$i][2];
                    $detalle->Inventariocosecha_Id =$lastIdInventariocosecha;
                    $this->modelcosecha->Registrar($detalle);
                }
            }
            header('Location:'.BASE_URL.'InventarioCosecha');
        }


        public function Paginacion(){
            if (isset($_GET["search"])) { $search  = $_GET["search"]; } else { $search=''; };  //aca le da el texto
            if (isset($_GET["page"])) { $page  = $_GET["page"]; } else { $page=1; };  
            $startFrom = ($page-1) * resultsPerPage;
        require_once 'Views/Inventariocosecha/paginationsearch.php';
        }
	}	
?>