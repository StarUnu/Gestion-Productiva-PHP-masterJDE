<?php
    require_once("Core/Session.php");
    require_once 'Models/DocumentoModel.php';
    require_once 'Models/TipoComprobantePagoModel.php';
    require_once 'Models/UnidadProductivaModel.php';

    class DocumentosController{
        
        private $model;
        private $modelTipoDocumento;
        private $modelUnidadProductiva;
        private $documento;
        
        public function __construct(){
            $this->model = new Documento();
            $this->modelTipoDocumento = new TipoComprobantePago();
            $this->modelUnidadProductiva = new UnidadProductiva();
        }
        
        public function Index(){
            $documento = new Documento();
            $totalRecords = $this->model->getTotalRecords();
            $totalPages = ceil($totalRecords/resultsPerPage);
            if (isset($_GET["page"])) { $page  = $_GET["page"]; } else { $page=1; };  
            $startFrom = ($page-1) * resultsPerPage;
            require_once 'Views/header.php';
            require_once 'Views/sidebar.php';
            require_once 'Views/panel.php';
            require_once 'Views/Documentos/index.php';
            require_once 'Views/footer.php';
        }
        
        public function Crud(){
            $documento = new Documento();
            $totalRecords = $this->model->getTotalRecords();
            $totalPages = ceil($totalRecords/resultsPerPage);
            if (isset($_GET["page"])) { $page  = $_GET["page"]; } else { $page=1; };  
            $startFrom = ($page-1) * resultsPerPage;

            if(isset($_REQUEST['Id'])){
                $documento = $this->model->Obtener($_REQUEST['Id']);
            }
            
            require_once 'Views/header.php';
            require_once 'Views/sidebar.php';
            require_once 'Views/panel.php';
            require_once 'Views/Documentos/update.php';
            require_once 'Views/footer.php';
        }

        public function Paginacion(){
            if (isset($_GET["search"])) { $search  = $_GET["search"]; } else { $search=''; };  
            if (isset($_GET["unidad"])) { $unidad  = $_GET["unidad"]; } else { $unidad=-1; };  
            if ($unidad == "NoUnidad")
            {
                $unidad = -1;
            }
            if (isset($_GET["page"])) { $page  = $_GET["page"]; } else { $page=1; };  
            $startFrom = ($page-1) * resultsPerPage;
            require_once 'Views/Documentos/paginationsearch.php';
        }

        public function Descargar()
        {
            if (isset($_GET['Id']) && basename($_GET['Id']) == $_GET['Id'])
            {
                $doc = $this->model->Obtener($_GET['Id']);
                $filename = $doc->EnlaceDigital; // of course find the exact filename....        
                $contentType = mime_content_type($filename);
                header('Pragma: public');
                header('Expires: 0');
                header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
                header('Cache-Control: private', false); // required for certain browsers 
                //header('Content-Type: application/'.$contentType);
                header('Content-Disposition: attachment; filename="'. basename($filename) . '";');
                header('Content-Transfer-Encoding: binary');
                header('Content-Length: ' . filesize($filename));
                readfile($filename);
                exit;
            }            
        }

        public function Buscar(){
            $search = '';
            $unidad = -1;
            if (isset($_POST["search"])) { $search  = $_POST["search"]; } else { $search=''; };  
            if (isset($_POST["unidad"])) { $unidad  = $_POST["unidad"]; } else { $unidad=-1; };  
            if ($unidad=="NoUnidad")
            {
                $unidad = -1;
            }
            if (isset($_GET["page"])) { $page  = $_GET["page"]; } else { $page=1; };  

            if ($unidad==-1) //quiere decir que esta seleccionado ----todos----
            {
                $documento = new Documento();
                $totalRecords = $this->model->getTotalRecordsBusqueda($search);
                $totalPages = ceil($totalRecords/resultsPerPage);
                $startFrom = ($page-1) * resultsPerPage;
                require_once 'Views/Documentos/fetch.php';    
            } else {
                $documento = new Documento();
                $totalRecords = $this->model->getTotalRecordsBusquedaByUnidad($unidad,$search);
                $totalPages = ceil($totalRecords/resultsPerPage);
                $startFrom = ($page-1) * resultsPerPage;
                require_once 'Views/Documentos/fetch.php';    
            }
        }
        
        public function Verificar(){
            require_once 'Views/Documentos/check_availability.php';
        }

        public function Guardar(){
            //Manejar imagen organigrama
            $documento = new Documento();

            if($_FILES["DocumentoDigital"]["error"] == 4) {
                //means there is no file uploaded
            }
            else{
                $path = $_FILES['DocumentoDigital']['name'];
                $ext = pathinfo($path, PATHINFO_EXTENSION);
                $documentPath = 'documentos/'.$_REQUEST['Id'].'.'.$ext;
                $documento->EnlaceDigital = $documentPath;
            }
            
            $documento->Id = $_REQUEST['Id'];
            $documento->Descripcion = $_REQUEST['Descripcion'];
            $documento->Tipo_Documento_Id = $_REQUEST['Tipo_Documento'];
            $documento->Numero = $_REQUEST['Numero_Documento'];
            $documento->Fecha_Legalizacion = $_REQUEST['Fecha_Legalizacion'];
            $documento->Numero_Folios = $_REQUEST['Numero_Folios'];
            $documento->EstadoOperativo = $_REQUEST['EstadoOperativo'];
            $documento->Observaciones = $_REQUEST['Observaciones'];
            $documento->Unidad_Id = $_REQUEST['Unidad'];    
            $documento->Periodo = $_REQUEST['Periodo'];
            $documento->Id > 0 
                ? $this->model->Actualizar($documento)
                : $this->model->Registrar($documento);
            
            header('Location:'.BASE_URL.'Documentos');
        }
        
        public function Eliminar(){
            $this->model->Eliminar($_REQUEST['Id']);
            header('Location:'.BASE_URL.'Documentos');
        }
    }
?>