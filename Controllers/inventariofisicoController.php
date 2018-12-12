<?php 
	require_once("Core/Session.php");
    require_once 'Models/InventarioFisicoModel.php';
    require_once 'Models/InventarioFisicoDetalleModel.php';
    require_once 'Models/MaterialInsumoModel.php';
    //require_once 'Models/UnidadMedidaModel.php';
    
   /* if ($_SESSION['TipoUsuario']==0){
        header('Location:'.BASE_URL.'Home');       
    } si es usuario u otro */


    class InventarioFisicoController{
        
        private $model;
        public $Inventariofisico;
        public $Unidadmedidadmodel;
        public $Inventariofdetalle;
        public $Materialinsumo;
        private $modeldetalle;
        public $auxTable;

        public function __construct(){
            
            $this->model = new InventarioFisico();
            $this->Materialinsumo= new Material_Insumo();
            $this->modeldetalle = new  InventarioFisicoDe();
            $this->auxTable="InventarioFisico";
        }

        public function Index()
        {
            $Inventariofdetalle = new Inventariofisicode();
        	$Inventariofisico = new InventarioFisico();

            if( isset($_SESSION['Unidad_Id'])){
                $unidadid=$_SESSION['Unidad_Id'];
                $totalRecords=$this->model->getTotalRecordsporUnnidad($unidadid);
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
            require_once 'Views/InventarioFisico/index.php';
            require_once 'Views/footer.php';
        }
        


        public function Crud(){//es donde se llena los datos
            $Inventariofisico = new InventarioFisico();

            if(isset($_REQUEST['Id'])){
                
                $Inventariofisico = $this->model->Obtener($_REQUEST['Id']);
            }

            $incremento=0;
            require_once 'Views/header.php';
            require_once 'Views/sidebar.php';
            require_once 'Views/panel.php';
            require_once 'Views/InventarioFisico/update.php';
            require_once 'Views/footer.php';
        }

        public function Guardar(){
            $contador = 0;
            $contador2 = 0;
            $detallestemp = array();

            //recogiendo los datos tsp
            $detalle='';
            foreach ($_REQUEST['IdDetalle'] as $detalle ) {
               $detallestemp[$contador]=array($detalle, "");
               $contador++;
            }

            $contador=0;
            foreach ($_REQUEST['Estado1'] as $detallestado ) {
               $detallestemp[$contador][1]=$detallestado;
               $contador++;               
            }

            $contador=0;
            foreach ($_REQUEST['CantidadDetalle'] as $cadetalle ) {
               $detallestemp[$contador][2]=$cadetalle;
               $contador++;               
            }

            $Inventariofisico=new InventarioFisico();
            $Inventariofisico->Id = $_REQUEST['Id'];
            $Inventariofisico->Unidad_Id = $_SESSION['Unidad_Id'];

            $Inventariofisico->TipoExistencia_Id = $_REQUEST['TipoInventario2'];
            $Inventariofisico->UnidadMedida_Id = $_REQUEST['UnidadMedida'];
            $Inventariofisico->Descripcion_Existencia = $_REQUEST['Descripcion'];
            $Inventariofisico->Observaciones = $_REQUEST['Observaciones'];
            $Inventariofisico->Codigo_Existencia =0;
            $Inventariofisico->FechaIngreso =$_REQUEST['Periodo1'];
            $Inventariofisico->Material_Insumo_Id =$_REQUEST['Marca33'];
            $id_inventariofisico = $Inventariofisico->Id;

            if($Inventariofisico->Id > 0){
                 $detallesTemporales = $this->modeldetalle->getporUnidadID($Inventariofisico->Id);
                 $lastIdInventarioFisico = $this->model->Actualizar($Inventariofisico); 
                 $Inventariofisico = new InventarioFisico();//no se porque lo creo
                 $tamDT = count($detallesTemporales);
                 for ($i=0; $i < $contador; $i++) { 
                    //actualizar los detalles que ya estan en la base de datos
                    for ($j=0; $j < $tamDT; $j++) { 
                        if ($detallestemp[$i][0] == $detallesTemporales[$j][0]){
                            $detalle = new InventarioFisicoDe();
                            $detalle->Id = $detallestemp[$i][0];
                            $detalle->Estado = $detallestemp[$i][1];
                            $prueba= var_dump($detallestemp[$i][1]);
                            $detalle->Cantidad = $detallestemp[$i][2];
                            $detalle->Observaciones = $detallestemp[$i][3];
                            $detalle->Edad = $detallestemp[$i][4];
                            $detalle->Material_Insumo_Id  = $detallestemp[$i][5];
                            $nuevo2 = var_dump($detallesTemporales[$j][0]);
                            $detalle->InventarioFisico_Id =$id_inventariofisico ;
                            if($detalle->Cantidad != null )
                                $this->modeldetalle->Actualizar($detalle);
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
                    $this->modeldetalle->Eliminar($detallesTemporales[$i][0]);    
                }

                //inserto los detalles  a la base de datos
                $canti = count($detallestemp);
              //  $uno = $detallestemp[1][3];
              $detallestemp = array_values($detallestemp);
               for ($i=0; $i <$canti ; $i++) { 
                    $detalle = new InventarioFisicoDe();
                    $detalle->Id = $detallestemp[$i][0];
                    $detalle->Estado = $detallestemp[$i][1];
                    $detalle->Cantidad = $detallestemp[$i][2];
                    $detalle->Observaciones =$detallestemp[$i][3];//esto estaba mal
                    //$año =floatval($detallestemp[$i][4]);
                    $detalle->Edad =$detallestemp[$i][4];                  
                    $detalle->Material_Insumo_Id  = $detallestemp[$i][5];                    
                    $detalle->InventarioFisico_Id = $id_inventariofisico;
                    $this->modeldetalle->Registrar($detalle);
                }
            }   
            else
            {

                $lastIdInventarioFisico = $this->model->Registrar($Inventariofisico);
                

                for ($i=0; $i < count($detallestemp); $i++) { 
                    $detalle = new InventarioFisicoDe();
                    $detalle->Id = $detallestemp[$i][0];
                    $detalle->Cantidad = $detallestemp[$i][2];
                    $detalle->Estado = $detallestemp[$i][1];
                    $detalle->Observaciones =$detallestemp[$i][3];
                    $detalle->Edad =$detallestemp[$i][4];

                    $detalle->InventarioFisico_Id = $lastIdInventarioFisico;
                    $detalle->Material_Insumo_Id  = $detallestemp[$i][5];                    
                    $this->modeldetalle->Registrar($detalle);
                }
            }
            
<<<<<<< HEAD
        header('Location:'.BASE_URL.'InventarioFisico');
=======
        // header('Location:'.BASE_URL.'InventarioFisico');
>>>>>>> becfce837fc73e48b7cf76d61112550f72f6bbb1
        }

        public function Eliminar(){
            $this->model->Eliminar($_REQUEST['Id']);
            header('Location:'.BASE_URL.'InventarioFisico');
        }

        /*public function Pagination(){
            if (isset($_GET["page"])) { $page  = $_GET["page"]; } else { $page=1; };  
            $startFrom = ($page-1) * resultsPerPage;
            require_once 'Views/InventarioFisico/pagination.php';
        }   */     

        public function Paginacion(){
            if (isset($_GET["search"])) { $search  = $_GET["search"]; } else { $search=''; };  //aca le da el texto
            if (isset($_GET["page"])) { $page  = $_GET["page"]; } else { $page=1; };  
            $startFrom = ($page-1) * resultsPerPage;
        require_once 'Views/InventarioFisico/paginationsearch.php';
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
                $Inventariofisico = new InventarioFisico();
                $totalRecords = $this->model->getTotalRecordsBusqueda($search);
                $totalPages = ceil($totalRecords/resultsPerPage);
                $startFrom = ($page-1) * resultsPerPage;
                require_once 'Views/InventarioFisico/fetch.php';    
            } else {
                $Inventariofisico = new InventarioFisico();
                $totalRecords = $this->model->getTotalRecordsBusquedaByUnidad($unidad,$search);
                $totalPages = ceil($totalRecords/resultsPerPage);
                $startFrom = ($page-1) * resultsPerPage;
                require_once 'Views/InventarioFisico/fetch.php';    
            }
        }
    
    }
?>