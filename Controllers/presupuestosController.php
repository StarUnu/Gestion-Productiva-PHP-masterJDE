<?php

require_once("Core/Session.php");
require_once 'Models/PresupuestoModel.php';
require_once 'Models/UnidadProductivaModel.php';
require_once 'Models/EjecucionPresupuestoModel.php';

class PresupuestosController{
    
    private $model;
    private $presupuesto;
    private $ejecucionPresupuesto;
    private $modelUnidadProductiva;
    private $modelEjecucionPresupuesto;
    
    public function __construct(){
        $this->model = new Presupuesto();
        $this->modelUnidadProductiva = new UnidadProductiva();
        $this->modelEjecucionPresupuesto = new EjecucionPresupuesto();
    }
    
    public function Index(){
        $presupuesto = new Presupuesto();
        $totalRecords = $this->model->getTotalRecords();
        $totalPages = ceil($totalRecords/resultsPerPage);
        if (isset($_GET["page"])) { $page  = $_GET["page"]; } else { $page=1; };  
        $startFrom = ($page-1) * resultsPerPage;
        require_once 'Views/header.php';
        require_once 'Views/sidebar.php';
        require_once 'Views/panel.php';
        require_once 'Views/Presupuestos/index.php';
        require_once 'Views/footer.php';
    }

    public function Pagination(){
        if (isset($_GET["page"])) { $page  = $_GET["page"]; } else { $page=1; };  
        $startFrom = ($page-1) * resultsPerPage;
        require_once 'Views/Presupuestos/pagination.php';
    }

    public function Paginacion(){
        if (isset($_GET["search"])) { $search  = $_GET["search"]; } else { $search=''; };  
        if (isset($_GET["page"])) { $page  = $_GET["page"]; } else { $page=1; };  
        $startFrom = ($page-1) * resultsPerPage;
        require_once 'Views/Presupuestos/paginationsearch.php';
    }

    /*
    public function Buscar(){
        $search = '';
        $unidad = -1;
        if (isset($_POST["search"])) { $search  = $_POST["search"]; } else { $search=''; };  
        if (isset($_POST["unidad"])) { $unidad  = $_POST["unidad"]; } else { $unidad=-1; };  
        if (isset($_GET["page"])) { $page  = $_GET["page"]; } else { $page=1; };  

        if ($unidad==-1) //quiere decir que esta seleccionado ----todos----
        {
            $persona = new Persona();
            $totalRecords = $this->model->getTotalRecordsBusqueda($search);
            $totalPages = ceil($totalRecords/resultsPerPage);
            $startFrom = ($page-1) * resultsPerPage;
            require_once 'Views/Personas/fetch.php';    
        } else {
            $persona = new Persona();
            $totalRecords = $this->model->getTotalRecordsBusquedaByUnidad($unidad,$search);
            $totalPages = ceil($totalRecords/resultsPerPage);
            $startFrom = ($page-1) * resultsPerPage;
            require_once 'Views/Personas/fetch.php';    
        }
    }
    */
    
    public function Guardar(){
        $presupuesto = new Presupuesto();
        $presupuesto->Id = isset($_REQUEST['Id']) ? $_REQUEST['Id'] : NULL;
        $presupuesto->Periodo = $_REQUEST['Periodo'];
        $presupuesto->Asignado = $_REQUEST['Asignado'];
        $presupuesto->Unidad_Id = $_REQUEST['Unidad'];
        //$presupuesto->Unidad_Id = $_SESSION['Unidad_Id'];
 
        if ($this->model->periodoUnidadExists($presupuesto->Periodo, $presupuesto->Unidad_Id))
        {
            $this->model->Actualizar($presupuesto);
        } else 
        {
            $this->model->Registrar($presupuesto);   
        }

        /*
        $presupuesto->Id > 0 
            ? $this->model->Actualizar($presupuesto)
            : $this->model->Registrar($presupuesto);
        */
        header('Location:'.BASE_URL.'Presupuestos');
    }

    public function Ejecucion()
    {
        $this->modelEjecucionPresupuesto = new EjecucionPresupuesto();
        $presupuesto_id = $_GET['Presupuesto'];
        $concepto_id = $_GET['Concepto'];
        if( isset($_GET['Presupuesto']) && isset($_GET['Concepto']) ) {
            //Aqui va todo el codigo de la funcion getIngresos
            file_put_contents('php://stderr', print_r("LLEGO A ESTE PUNTO1", TRUE));
            $jsondata = $this->modelEjecucionPresupuesto->getEjecucionByPresupuestoConcepto($presupuesto_id, $concepto_id);
            $jsondata["success"] = true;
            header('Content-type: application/json; charset=utf-8');
            file_put_contents('php://stderr', print_r("LLEGO A ESTE PUNTO", TRUE));
            echo json_encode($jsondata, JSON_FORCE_OBJECT);    
        } else {
            die("Solicitud no válida.");
        }
    }

    public function Ejecutar(){
        $ejecucionPresupuesto = new EjecucionPresupuesto();
        //$ejecucionPresupuesto->Id = $_REQUEST['Id'];
        $ejecucionPresupuesto->FuenteFinanciamiento = $_REQUEST['FuenteFinanciamiento'];
        $ejecucionPresupuesto->Monto = $_REQUEST['Monto'];
        $ejecucionPresupuesto->Presupuesto_Id = $_REQUEST['Presupuesto'];
        $ejecucionPresupuesto->IdentificadorConcepto = $_REQUEST['IdentificadorConcepto'];

        if ($this->modelEjecucionPresupuesto->presupuestoConceptoExists($ejecucionPresupuesto->Presupuesto_Id, $ejecucionPresupuesto->IdentificadorConcepto))
        {
            $this->modelEjecucionPresupuesto->Actualizar($ejecucionPresupuesto);
        } else
        {
            $this->modelEjecucionPresupuesto->Registrar($ejecucionPresupuesto);
        }
        /*
        $rubro->Id > 0 
            ? $this->model->Actualizar($ejecucionPresupuesto)
            : $this->model->Registrar($ejecucionPresupuesto);
        */
        //$this->modelEjecucionPresupuesto->Registrar($ejecucionPresupuesto);
        
        header('Location:'.BASE_URL.'Presupuestos');
    }
    
    public function Eliminar(){
        $this->model->Eliminar($_REQUEST['Id']);
        header('Location:'.BASE_URL.'Presupuestos');
    }
}