<?php
    
    require_once("Core/Session.php");
    require_once 'Models/TipoCosechaModel.php';

    class TipoCosechaController
    {
        private $tipocosecha;
        private $auxTable;
        private $model;
        public function __construct()
        {
            $this->auxTable = "TipoCosecha";//esto es muy importante
            $this->model= new TipoCosecha();
        }

        public function index()
        {
            //$model= new UnidadMedida();
            $tipocosecha = new TipoCosecha(); 

            $totalRecords = $this->model->getTotalRecords();

            $totalPages = ceil($totalRecords/resultsPerPage);

            if (isset($_GET["page"])) { $page  = $_GET["page"]; } else { $page=1; };  
            //creo el get es para averiguar si esta funcionando corectamente page
            $startFrom = ($page-1) * resultsPerPage;
           
            
            require_once 'Views/header.php';
            require_once 'Views/sidebar.php';
            require_once 'Views/panel.php';
            require_once 'Views/TipoCosecha/index.php';
            require_once 'Views/footer.php';
        }

        public function Crud()
        {
            $tipocosecha = new TipoCosecha();

            $totalRecords = $this->model->getTotalRecords();
            $totalPages = ceil($totalRecords/resultsPerPage);
            if (isset($_GET["page"])) { $page  = $_GET["page"]; } else { $page=1; };  
                $startFrom = ($page-1) * resultsPerPage;

            if(isset($_REQUEST['Id'])){
                $tipocosecha = $this->model->Obtener($_REQUEST['Id']);
            }
            
            require_once 'Views/header.php';
            require_once 'Views/sidebar.php';
            require_once 'Views/panel.php';
            require_once 'Views/TipoCosecha/index.php';
            require_once 'Views/footer.php';
        }

        public function Guardar(){
            $tipocosecha = new TipoCosecha();
            
            $tipocosecha->Id = $_REQUEST['Id'];
            $tipocosecha->Descripcion = $_REQUEST['Descripcion'];

            if ($tipocosecha->Id > 0 )
               { $this->model->Actualizar($tipocosecha);}
            else
            {   
                $this->model->Registrar($tipocosecha); 
            }
            
           header('Location:'.BASE_URL.'TipoCosecha');
        }
        
        public function Eliminar(){
            $this->model->Eliminar($_REQUEST['Id']);
            header('Location:'.BASE_URL.'TipoCosecha');
        }

        public function Pagination(){
            if (isset($_GET["page"])) { $page  = $_GET["page"]; } else { $page=1; };  
            $startFrom = ($page-1) * resultsPerPage;
            require_once 'Views/TipoCosecha/pagination.php';
        } 
    } 

?>