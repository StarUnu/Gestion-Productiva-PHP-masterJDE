<?php
    require_once("Core/Session.php");
    require_once 'Models/OperacionModel.php';
    require_once 'Models/DetalleOperacionModel.php';
    require_once 'Models/TipoComprobantePagoModel.php';
    require_once 'Models/UnidadProductivaModel.php';
    
    class OperacionesController{
        
        private $model;
        private $operacion;
        private $modelDetalleOperacion;
        private $modelTipoComprobante;
        private $modelUnidadProductiva;
        private $detallesTemporales;

        public function __construct(){
            $this->model = new Operacion();
            $this->modelDetalleOperacion = new DetalleOperacion();
            $this->modelTipoComprobante = new TipoComprobantePago();
            $this->modelUnidadProductiva = new UnidadProductiva();
        }

        public function getMesNombre($mes)
        {
            if ($mes==1)
                return 'Enero (1)';
            if ($mes==2)
                return 'Febrero (2)';
            if ($mes==3)
                return 'Marzo (3)';
            if ($mes==4)
                return 'Abril (4)';
            if ($mes==5)
                return 'Mayo (5)';
            if ($mes==6)
                return 'Junio (6)';
            if ($mes==7)
                return 'Julio (7)';
            if ($mes==8)
                return 'Agosto (8)';
            if ($mes==9)
                return 'Septiembre (9)';
            if ($mes==10)
                return 'Octubre (10)';
            if ($mes==11)
                return 'Noviembre (11)';
            if ($mes==12)
                return 'Diciembre (12)';
        }
        
        public function Index(){
            $operacion = new Operacion();
            $totalRecords = $this->model->getTotalRecords();
            $totalPages = ceil($totalRecords/resultsPerPage);
            if (isset($_GET["page"])) { $page  = $_GET["page"]; } else { $page=1; };  
            $startFrom = ($page-1) * resultsPerPage;
            require_once 'Views/header.php';
            require_once 'Views/sidebar.php';
            require_once 'Views/panel.php';
            require_once 'Views/Operaciones/index.php';
            require_once 'Views/footer.php';
        }
        
        public function Crud(){
            $operacion = new Operacion();
            $totalRecords = $this->model->getTotalRecords();
            $totalPages = ceil($totalRecords/resultsPerPage);
            if (isset($_GET["page"])) { $page  = $_GET["page"]; } else { $page=1; };  
            $startFrom = ($page-1) * resultsPerPage;
            if(isset($_REQUEST['Id'])){
                $operacion = $this->model->Obtener($_REQUEST['Id']);
            }
            require_once 'Views/header.php';
            require_once 'Views/sidebar.php';
            require_once 'Views/panel.php';
            require_once 'Views/Operaciones/update.php';
            require_once 'Views/footer.php';
        }

        public function Paginacion(){
            if (isset($_GET["periodo"])) { $periodo  = $_GET["periodo"]; } else { $periodo=date('Y'); };
            if (isset($_GET["unidad"])) { $unidad  = $_GET["unidad"]; } else { $unidad=-1; };  
            if ($unidad == "NoUnidad")
            {
                $unidad = -1;
            }
            if (isset($_GET["page"])) { $page  = $_GET["page"]; } else { $page=1; };  
            $startFrom = ($page-1) * resultsPerPage;
            require_once 'Views/Operaciones/paginationsearch.php';
        }

        public function Buscar(){
            $periodo = '';
            $unidad = -1;
            if (isset($_POST["periodo"])) { $periodo  = $_POST["periodo"]; } else { $periodo=''; };  
            if (isset($_POST["unidad"])) { $unidad  = $_POST["unidad"]; } else { $unidad=-1; };  
            if ($unidad=="NoUnidad")
            {
                $unidad = -1;
            }
            if (isset($_GET["page"])) { $page  = $_GET["page"]; } else { $page=1; };  

            if ($unidad==-1)
            {
                $operacion = new Operacion();
                $totalRecords = $this->model->getTotalRecordsBusqueda($periodo);
                $totalPages = ceil($totalRecords/resultsPerPage);
                $startFrom = ($page-1) * resultsPerPage;
                require_once 'Views/Operaciones/fetch.php';    
            } else {
                $operacion = new Operacion();
                $totalRecords = $this->model->getTotalRecordsBusquedaByUnidad($unidad,$periodo);
                $totalPages = ceil($totalRecords/resultsPerPage);
                $startFrom = ($page-1) * resultsPerPage;
                require_once 'Views/Operaciones/fetch.php';    
            }
        }


        public function Guardar(){
            $operacion = new Operacion();
            $operacion->Id = $_REQUEST['Id'];
            $operacion->Tipo = $_REQUEST['Tipo'];
            $operacion->Unidad_Id = $_REQUEST['Unidad'];
            $operacion->Tipo_Comprobante_Documento_Id = $_REQUEST['TipoComprobateDocumento'];
            $operacion->Concepto = $_REQUEST['Concepto'];
            $operacion->Monto = $_REQUEST['Monto'];
            $operacion->Mes = $_REQUEST['Mes'];
            $operacion->Periodo = $_REQUEST['Periodo'];

            if ($operacion->Id > 0){
                $this->model->Actualizar($operacion);
            }else {
                $this->model->Registrar($operacion);
            }
            header('Location:'.BASE_URL.'Operaciones');
        }
        
        public function Eliminar(){
            $this->model->Eliminar($_REQUEST['Id']);
            header('Location:'.BASE_URL.'Operaciones');
        }
    }
?>