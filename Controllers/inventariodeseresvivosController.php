<?php
    require_once("Core/Session.php");
    require_once 'Models/InventariodeSeresVivosModel.php';
    require_once 'Models/AnimalesModel.php';
    require_once 'Models/TipoAnimalesModel.php';
	class InventariodeSeresVivosController
	{
		private $model;
		public $modelanimales;
        public $auxTable;
        public $seresvivos;
        public $animales;
        public $modeltiporazanimal;
		public function __construct()
		{
			$this->model=new InventarioAnimales(); 
			$this->modelanimales=new Animales();
            $this->modeltipoanimal=new TipoAnimal();
            $this->auxTable = "InventarioSeresVivos"; 
		}

		public function Index()
        {
            $seresvivos = new InventarioAnimales();
            $animales=new Animales();

            $totalRecords = $this->model->getTotalRecords();//el grado el numero de filas
            $totalPages = ceil($totalRecords/resultsPerPage);
            
            if (isset($_GET["page"])) { $page  = $_GET["page"]; } else { $page=1; };  
                $startFrom = ($page-1) * resultsPerPage;
            
            require_once 'Views/header.php';
            require_once 'Views/sidebar.php';
            require_once 'Views/panel.php';
            require_once 'Views/InventarioSeresVivos/index.php';
            require_once 'Views/footer.php';
        }

        public function Crud(){//es donde se llena los datos
            $seresvivos = new InventarioAnimales();
            $animales=new Animales();
            $modeltiporazanimal = new TipoAnimal();
            if(isset($_REQUEST['Id']) ){
                $seresvivos = $this->model->Obtener($_REQUEST['Id']);
            }

            require_once 'Views/header.php';
            require_once 'Views/sidebar.php';
            require_once 'Views/panel.php';
            require_once 'Views/InventarioSeresVivos/update.php';
            require_once 'Views/footer.php';

           // header('Location:'.BASE_URL.'InventarioFisico');
        }

       public function Guardar(){
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
               $detallestemp[$contador][2]=$cadetalle;
               echo "las catnidaddes son $cadetalle ed";
               $contador++;               
            }
            $contador=0;
            foreach ($_REQUEST['Estado1'] as $obdetalle ) {
               $detallestemp[$contador][3]= $obdetalle;

               $contador++;               
            }
            $seresvivos = new InventarioAnimales();
            $seresvivos->Id = $_REQUEST['Id'];//porque el request rescato con otro
            $seresvivos->Descripcion = $_REQUEST['Descripcion']; 
            $seresvivos->Observaciones = $_REQUEST['Observaciones'];
            $seresvivos->FechaIngreso= $_REQUEST['FechaIngreso'];
            $seresvivos->tipoanimal_id= $_REQUEST['tipoanimal'];
            $seresvivos->Unidad_Id= $_SESSION['Unidad_Id']; 
            echo "fecha de ingrso $seresvivos->FechaIngreso ";
            if($seresvivos->Id > 0){
                $detallesTemporales = $this->modelanimales->obtenerporidinventarioarray($seresvivos->Id);
                 $lastIdInventarioAnimal = $this->model->Actualizar($seresvivos); 
                 $Inventariofisico = new InventarioAnimales();
                 $tamDT = count($detallesTemporales);
                 for ($i=0; $i < $contador; $i++) { 
                    for ($j=0; $j < $tamDT; $j++) { 
                        if ($detallestemp[$i][0] == $detallesTemporales[$j][0]){
                            $detalle = new Animales();
                            $detalle->Id = $detallestemp[$i][0];
                            $detalle->Animalmarca_id = $detallestemp[$i][1];
                            $detalle->Cantidad = $detallestemp[$i][2];
                            $detalle->Estado = $detallestemp[$i][3];
                            $detalle->Inventariodeseresvivos_Id =$seresvivos->Id ;
                            if($detalle->Cantidad != null )
                                $this->modelanimales->Actualizar($detalle);
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
                echo "cantidadtotal $canti";
              $detallestemp = array_values($detallestemp);
               for ($i=0; $i <$canti ; $i++) { 
                    $detalle = new Animales();
                    $detalle->Id = $detallestemp[$i][0];
                    $detalle->Animalmarca_id = $detallestemp[$i][1];
                    $detalle->Cantidad = $detallestemp[$i][2];
                    $detalle->Estado = $detallestemp[$i][3];
                    $detalle->Inventariodeseresvivos_Id =$seresvivos->Id ;
                    $this->modelanimales->Registrar($detalle);
                }
            }
            else{
                 $lastIdInventarioAnimal = $this->model->Registrar($seresvivos);
                 $cont=count($detallestemp);
                 echo "la cantidad de loop es $cont ";
                for ($i=0; $i < count($detallestemp); $i++) { 
                    $detalle = new Animales();
                    $detalle->Id = $detallestemp[$i][0];
                    $detalle->Animalmarca_id = $detallestemp[$i][1];
                    $detalle->Cantidad = $detallestemp[$i][2];
                    $detalle->Estado =$detallestemp[$i][3];
                    $detalle->Inventariodeseresvivos_Id =$lastIdInventarioAnimal;
                    $this->modelanimales->Registrar($detalle);
                }
            }
            header('Location:'.BASE_URL.'InventariodeSeresVivos');
        }

        public function Buscar(){
            $search = '';
            $unidad = -1;

            if (isset($_POST["search"])) { $search  = $_POST["search"]; } else { $search=''; };  
            if (isset($_POST["unidad"])) { $unidad  = $_POST["unidad"]; } else { $unidad=-1; };  
            if (isset($_GET["page"])) { $page  = $_GET["page"]; } else { $page=1; };  

            if ($unidad==-1 ) //quiere decir que esta seleccionado ----todos----
            {
                $Inventariofisico = new InventarioAnimales();
                $totalRecords = $this->model->getTotalRecordsBusqueda($search);
                $totalPages = ceil($totalRecords/resultsPerPage);
                $startFrom = ($page-1) * resultsPerPage;
                require_once 'Views/InventarioSeresVivos/fetch.php';    
            } else {
                $Inventariofisico = new InventarioAnimales();
                $totalRecords = $this->model->getTotalRecordsBusquedaByUnidad($unidad,$search);
                $totalPages = ceil($totalRecords/resultsPerPage);
                $startFrom = ($page-1) * resultsPerPage;
                require_once 'Views/InventarioSeresVivos/fetch.php';    
            }
        }

        public function Eliminar(){
            $this->model->Eliminar($_REQUEST['Id']);
            header('Location:'.BASE_URL.'Inventariofisico');
        }

        public function Pagination(){
            if (isset($_GET["page"])) { $page  = $_GET["page"]; } else { $page=1; };  
            $startFrom = ($page-1) * resultsPerPage;
            require_once 'Views/InventariodeSeresVivos/pagination.php';
        }

        public function Paginacion(){
            if (isset($_GET["search"])) { $search  = $_GET["search"]; } else { $search=''; };  //aca le da el texto
            if (isset($_GET["page"])) { $page  = $_GET["page"]; } else { $page=1; };  
            $startFrom = ($page-1) * resultsPerPage;
        require_once 'Views/InventarioSeresVivos/paginationsearch.php';
        }
	}
 ?>