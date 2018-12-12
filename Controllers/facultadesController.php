<?php
    require_once("Core/Session.php");
    require_once 'Models/FacultadModel.php';

    //Redireccionar a Home, los usuarios normales no pueden modificar las tablas auxiliares
    if ($_SESSION['TipoUsuario']==0){
        header('Location:'.BASE_URL.'Home');       
    }
    
    class FacultadesController{
        
        private $model;
        private $facultad;
        private $auxTable;
        public function __construct(){
            $this->model = new Facultad();
            $this->auxTable = "Facultades";
        }
        
        public function Index(){
            $facultad = new Facultad();
            $totalRecords = $this->model->getTotalRecords();
            $totalPages = ceil($totalRecords/resultsPerPage);
            if (isset($_GET["page"])) { $page  = $_GET["page"]; } else { $page=1; };  
            $startFrom = ($page-1) * resultsPerPage;
            require_once 'Views/header.php';
            require_once 'Views/sidebar.php';
            require_once 'Views/panel.php';
            require_once 'Views/Facultades/index.php';
            require_once 'Views/footer.php';
        }
        
        public function Crud(){
            $facultad = new Facultad();
            $totalRecords = $this->model->getTotalRecords();
            $totalPages = ceil($totalRecords/resultsPerPage);
            if (isset($_GET["page"])) { $page  = $_GET["page"]; } else { $page=1; };  
            $startFrom = ($page-1) * resultsPerPage;
            if(isset($_REQUEST['Id'])){
                $facultad = $this->model->Obtener($_REQUEST['Id']);
            }
            
            require_once 'Views/header.php';
            require_once 'Views/sidebar.php';
            require_once 'Views/panel.php';
            require_once 'Views/Facultades/index.php';
            require_once 'Views/footer.php';
        }
        
        public function Pagination(){
            if (isset($_GET["page"])) { $page  = $_GET["page"]; } else { $page=1; };  
            $startFrom = ($page-1) * resultsPerPage;
            require_once 'Views/Facultades/pagination.php';
        }

        public function Guardar(){
            $facultad = new Facultad();
            
            $facultad->Id = $_REQUEST['Id'];
            $facultad->Nombre = $_REQUEST['Nombre'];

            $facultad->Id > 0 
                ? $this->model->Actualizar($facultad)
                : $this->model->Registrar($facultad);
            
            header('Location:'.BASE_URL.'Facultades');
        }
        
        public function Eliminar(){
            $this->model->Eliminar($_REQUEST['Id']);
            header('Location:'.BASE_URL.'Facultades');
        }
    }
?>