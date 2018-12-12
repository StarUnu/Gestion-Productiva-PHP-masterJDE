<?php
    
    require_once("Core/Session.php");
    require_once 'Models/TipoAnimalesModel.php';

    class TipoAnimalesController
    {
        private $tipoexistencia;
        private $auxTable;
        private $model;
        public function __construct()
        {
            $this->auxTable = "TipoAnimales";//esto es muy importante
            $this->model= new TipoAnimal();
        }

        public function index()
        {
            //$model= new UnidadMedida();
            $tipoanimales = new TipoAnimal(); 

            $totalRecords = $this->model->getTotalRecords();

            $totalPages = ceil($totalRecords/resultsPerPage);

            if (isset($_GET["page"])) { $page  = $_GET["page"]; } else { $page=1; };  
            //creo el get es para averiguar si esta funcionando corectamente page
            $startFrom = ($page-1) * resultsPerPage;
           
            
            require_once 'Views/header.php';
            require_once 'Views/sidebar.php';
            require_once 'Views/panel.php';
            require_once 'Views/TipoAnimales/index.php';
            require_once 'Views/footer.php';
        }

        public function Crud()
        {
            $tipoanimales = new TipoAnimal();

            $totalRecords = $this->model->getTotalRecords();
            $totalPages = ceil($totalRecords/resultsPerPage);
            if (isset($_GET["page"])) { $page  = $_GET["page"]; } else { $page=1; };  
                $startFrom = ($page-1) * resultsPerPage;

            if(isset($_REQUEST['Id'])){
                $tipoanimales = $this->model->Obtener($_REQUEST['Id']);
            }
            
            require_once 'Views/header.php';
            require_once 'Views/sidebar.php';
            require_once 'Views/panel.php';
            require_once 'Views/TipoAnimales/index.php';
            require_once 'Views/footer.php';
        }

        public function Guardar(){
            $tipoanimales = new TipoAnimal();
            
            $tipoanimales->Id = $_REQUEST['Id'];
            $tipoanimales->Descripcion = $_REQUEST['Descripcion'];

            if ($Unidadmedida->Id > 0 )
               { $this->model->Actualizar($tipoanimales);}
            else
            {   
                $this->model->Registrar($tipoanimales); 
            }
            
           header('Location:'.BASE_URL.'TipoAnimales');
        }
        
        public function Eliminar(){
            $this->model->Eliminar($_REQUEST['Id']);
            header('Location:'.BASE_URL.'TipoAnimales');
        }

        public function Pagination(){
            if (isset($_GET["page"])) { $page  = $_GET["page"]; } else { $page=1; };  
            $startFrom = ($page-1) * resultsPerPage;
            require_once 'Views/TipoAnimales/pagination.php';
        } 
    } 

?>