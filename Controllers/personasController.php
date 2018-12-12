<?php

require_once("Core/Session.php");
require_once 'Models/PersonaModel.php';

class PersonasController{
    
    private $model;
    private $persona;
    
    public function __construct(){
        $this->model = new Persona();

    }
    
    public function Index(){
        $persona = new Persona();
        $totalRecords = $this->model->getTotalRecords();
        $totalPages = ceil($totalRecords/resultsPerPage);
        if (isset($_GET["page"])) { $page  = $_GET["page"]; } else { $page=1; };  
        $startFrom = ($page-1) * resultsPerPage;
        require_once 'Views/header.php';
        require_once 'Views/sidebar.php';
        require_once 'Views/panel.php';
        require_once 'Views/Personas/index.php';
        require_once 'Views/footer.php';
    }
    
    public function Crud(){
        $persona = new Persona();
        $totalRecords = $this->model->getTotalRecords();
        $totalPages = ceil($totalRecords/resultsPerPage);
        if (isset($_GET["page"])) { $page  = $_GET["page"]; } else { $page=1; };  
        $startFrom = ($page-1) * resultsPerPage;

        if(isset($_REQUEST['Dni'])){
            $persona = $this->model->Obtener($_REQUEST['Dni']);
        }
        require_once 'Views/header.php';
        require_once 'Views/sidebar.php';
        require_once 'Views/panel.php';
        require_once 'Views/Personas/update.php';
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
        require_once 'Views/Personas/paginationsearch.php';
    }

    public function Verificar(){
        require_once 'Views/Personas/check_availability.php';
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
    
    public function Guardar(){
        $persona = new Persona();

        if($_FILES["Foto"]["error"] == 4) {
            //means there is no file uploaded
        }
        else{
            $path = $_FILES['Foto']['name'];
            $ext = pathinfo($path, PATHINFO_EXTENSION);
            $imagePath = 'imagenes/personas/'.$_REQUEST['Dni'].'.'.$ext;
            $persona->Foto = $imagePath;
        }
        $DniUpdate = $_REQUEST['DniUpdate'];
        $persona->Dni = $_REQUEST['Dni'];
        $persona->Username = $_REQUEST['Username'];
        $persona->Password = $_REQUEST['Password'];
        $persona->Nombres = $_REQUEST['Nombres'];
        $persona->Apellidos = $_REQUEST['Apellidos'];
        $persona->Direccion = $_REQUEST['Direccion'];
        $persona->Telefono = $_REQUEST['Telefono'];
        $persona->Email = $_REQUEST['Email'];
        $persona->Web = $_REQUEST['Web'];
        $persona->Nacimiento = $_REQUEST['Nacimiento'];
        $persona->Genero = $_REQUEST['Genero'];        
        $persona->Informacion = $_REQUEST['Informacion'];
        $persona->Fecha_Ingreso = $_REQUEST['FechaIngreso'];
        $persona->Condicion_Laboral = $_REQUEST['CondicionLaboral'];
        $persona->Especialidad = $_REQUEST['Especialidad'];
        $persona->Cargo_Id = $_REQUEST['Cargo'];
        $persona->Unidad_Id = $_REQUEST['Unidad'];
        $persona->GradosTitulos = $_REQUEST['GradosTitulos'];

        if ($DniUpdate!="-1"){
            $persona->Dni = $_REQUEST['DniUpdate'];
            $imagePath = 'imagenes/personas/'.$_REQUEST['DniUpdate'].'.'.$ext;
            $persona->Foto = $imagePath;
            $this->model->Actualizar($persona);
        }else{
            $this->model->Registrar($persona);
        }
        
        header('Location:'.BASE_URL.'Personas');
    }
    
    public function Eliminar(){
        if (!$this->model->Eliminar($_REQUEST['Dni']))
        {

            $persona = new Persona();
            $totalRecords = $this->model->getTotalRecords();
            $totalPages = ceil($totalRecords/resultsPerPage);
            if (isset($_GET["page"])) { $page  = $_GET["page"]; } else { $page=1; };  
            $startFrom = ($page-1) * resultsPerPage;
            require_once 'Views/header.php';
            require_once 'Views/sidebar.php';
            require_once 'Views/panel.php';
            require_once 'Views/Personas/index.php';
            require_once 'Views/footer.php';            
            $unidadResponsable = $this->model->getUnidadByResponsable($_REQUEST['Dni']);
            echo '<script type="text/javascript">'.'demo.showDangerNotification("top","center",'.'"La persona que intenta eliminar es actualmente responsable de la unidad '.$unidadResponsable.', asigne otro responsable a dicha unidad para poder eliminar esta persona."); </script>';
            
        }
        else
        {
            header('Location:'.BASE_URL.'Personas');    
        }
    }
}