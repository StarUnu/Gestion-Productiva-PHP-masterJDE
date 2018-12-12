<?php
	require_once("Core/Session.php");
    require_once 'Models/MaterialInsumoModel.php';
    //aqui solo traigo el modelo ya definido
    $contador=0;
    class MaterialinsumoController
    {
    	private $model;//no son lo mismo
    	private $materialinsumo;
    	private $auxTable;//esto para mostrar la seleccion 
        private $contador;

    	function __construct()
    	{
    		$this->auxTable = "MaterialInsumo";//esto es muy importante
    		$this->model = new Material_Insumo();
            $this->contador =0;
    	}

    	public function index()//este lo màs principal
    	{
    		$materialinsumo = new Material_Insumo();//lo demas es para el pied de pagina
    		$totalRecords = $this->model->getTotalRecords();
    		$totalPages = ceil($totalRecords/resultsPerPage);
    		//resultsPerPagees una vatiable de javasscript var resultsPerPage
            if (isset($_GET["page"])) { $page  = $_GET["page"]; } else { $page=1; };  
            	$startFrom = ($page-1) * resultsPerPage;
    		require_once 'Views/header.php';
            require_once 'Views/sidebar.php';
            require_once 'Views/panel.php';
            require_once 'Views/MaterialInsumo/index.php';
            require_once 'Views/footer.php';
    	}

        public function Crud()
        {
            $materialinsumo = new Material_Insumo();

            $totalRecords = $this->model->getTotalRecords();
            $totalPages = ceil($totalRecords/resultsPerPage);
            if (isset($_GET["page"])) { $page  = $_GET["page"]; } else { $page=1; };  
            $startFrom = ($page-1) * resultsPerPage;

            if(isset($_REQUEST['Id'])){
                $materialinsumo = $this->model->Obtener($_REQUEST['Id']);
            }
            
            require_once 'Views/header.php';
            require_once 'Views/sidebar.php';
            require_once 'Views/panel.php';
            require_once 'Views/MaterialInsumo/index.php';
            require_once 'Views/footer.php';
        }

        public function setcontador($nuevocontador) {
            $this->contador= $nuevocontador;
        }

        public function __set($var,$val) {
         $this->$var=$val;
        }   

    	public function Guardar()
    	{
    		$materialinsumo = new Material_Insumo();
    		$materialinsumo->Id = $_REQUEST["Id"];
    		$materialinsumo->Descripcion = $_REQUEST["Descripcion"];

            $string =(string) $this->contador ;
            $string .= '.-';
            $string .= $_REQUEST["Marca"];
            echo "$string";
    		$materialinsumo->Marca = $string  ;
    		$nuevo= $this->contador +1;
            $contador =(int) $nuevo;
            //__set($contador,$nuevo);
            echo "$this->contador";
    		if ($materialinsumo->Id > 0 )
               { $this->model->Actualizar($materialinsumo);
               	//obserba actualizar
               }
            else
                {   
            		//acà va a dentrar cuando es nuevo pues no tiene ID su Id es igual ""
                    $this->model->Registrar($materialinsumo);
                }
           header('Location:'.BASE_URL.'MaterialInsumo');
    	}

        public function Eliminar(){
            $this->model->Eliminar($_REQUEST['Id']);
            header('Location:'.BASE_URL.'MaterialInsumo');
        }

        public function Pagination(){
            if (isset($_GET["page"])) { $page  = $_GET["page"]; } else { $page=1; };  
            $startFrom = ($page-1) * resultsPerPage;
            require_once 'Views/MaterialInsumo/pagination.php';
        } 
    }
?>