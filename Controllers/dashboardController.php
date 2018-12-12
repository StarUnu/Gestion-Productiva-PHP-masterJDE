<?php

require_once("Core/Session.php");
require_once 'Models/DocumentoModel.php';
require_once 'Models/TipoComprobantePagoModel.php';
require_once 'Models/UnidadProductivaModel.php';
require_once 'Models/PersonaModel.php';
require_once 'Models/CargoModel.php';
require_once 'Models/OperacionModel.php';
require_once 'Models/RubroModel.php';
require_once 'Models/FacultadModel.php';

require_once 'Models/UnidadMedidadModel.php';
require_once 'Models/InventarioFisicoDetalleModel.php';
require_once 'Models/MaterialInsumoModel.php';
require_once 'Models/InventarioFisicoModel.php';
require_once 'Models/TipoExistenciaModel.php';
require_once 'Models/InventarioEquiposModel.php';
require_once 'Models/EquiposModel.php';
require_once 'Models/BienesModel.php';
require_once 'Models/InventarioBienesModel.php';
require_once 'Models/InventariodeSeresVivosModel.php';
require_once 'Models/AnimalesModel.php';
require_once 'Models/TipoAnimalesModel.php';
require_once 'Models/TipoMaterialModel.php';

require_once 'Models/CosechaModel.php';
require_once 'Models/TipoCosechaModel.php';
require_once 'Models/InventarioCosechaModel.php';


class DashboardController{
    
    private $modelDocumento;
    private $modelTipoDocumento;
    private $modelUnidadProductiva;
    private $modelOperacion;
    private $documento;
    private $dashboard;
    private $inventariofisico;

    private $modelMaterialInsumo;
    private $modelTipoExistencia;
    private $modelinventariofisicode;
    private $claseinventario;

    private $modelinventarioanimales;
    private $modelanimales;
    private $modeltipoanimal;
    private $modeltipomaterial;

    private $modelinventariocosecha;
    private $modeltipocosecha;
    private $modelcosecha;

    
    public function __construct(){
        $dashboard = new Documento();
        $this->modelDocumento = new Documento();
        $this->modelTipoDocumento = new TipoComprobantePago();
        $this->modelUnidadProductiva = new UnidadProductiva();
        $this->modelOperacion = new Operacion();

        $this->modelMaterialInsumo = new MateriaL_Insumo();
        $this->modelTipoExistencia = new TipoExistencia() ;
        $this->modelinventariofisicode = new InventarioFisicoDe();
        $this->modelinventarioanimales=new InventarioAnimales();
        $this->modelanimales = new Animales();
        $this->modeltipoanimal = new TipoAnimal();
        $this->modeltipomaterial = new TipoMaterial();

        $this->modeltipocosecha = new TipoCosecha();
        $this->modelinventariocosecha = new InventarioCosecha();
        $this->modelcosecha = new Cosecha();

    }
    
    public function Index(){
        $dashboard = new Documento();
        require_once 'Views/header.php';
        require_once 'Views/sidebar.php';
        require_once 'Views/panel.php';
        require_once 'Views/Dashboard/index.php';
        require_once 'Views/footer.php';
    }

    public function ReporteInventarioFisico()
    {
     require_once 'Views/Reportes/rInventarioFisico.php';   
    }

    public function ReporteInventarioEquipos()
    {
     require_once 'Views/Reportes/rInventarioEquipos.php';
    }

    public function ReporteInventarioBienesMEI()
    {
     $this->claseinventario=0;
     require_once 'Views/Reportes/rInventarioBienes.php';   
    }

    public function ReporteInventarioBienesA()
    {
     $this->claseinventario=1;
     require_once 'Views/Reportes/rInventarioAnimales.php';   
    }

    public function ReporteInventarioBienesCYO()
    {
     $this->claseinventario=2;
     require_once 'Views/Reportes/rInventarioCosechas.php';   
    }


    public function Ingresos()
    {
        require_once 'Views/Dashboard/ingresos.php';
    }

    public function Egresos()
    {
        require_once 'Views/Dashboard/egresos.php';
    }

    public function ReporteAnual()
    {
        require_once 'Views/Reportes/anual.php';
    }

    public function ReporteDocumentos()
    {
        require_once 'Views/Reportes/documentos.php';
    }

    public function ReporteTrabajadores()
    {
        require_once 'Views/Reportes/trabajadores.php';
    }

    public function ReporteIngresosEgresos()
    {
        require_once 'Views/Reportes/ingresosegresos.php';
    }
}