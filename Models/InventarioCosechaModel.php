<?php 
	class InventarioCosecha
	{
		private $pdo;
		public $Id;
		public $Descripcion;
		public $Fechaingreso;
		public $Observaciones;
		public $Unidad_Id;
		public $TipoCosecha_Id;
		public function __construct()
		{
			try
			{
				$this->pdo = Database::Conectar();
			}
			catch(Exception $e)
			{
				die($e->getMessage());
			}
		}
		public function Listar($startFrom)
		{
			try
			{
				$limit = resultsPerPage;//acas lo guarda la variable
				$start = $startFrom;
			    $stmt = $this->pdo->prepare("SELECT * FROM inventariocosecha ORDER BY Fechaingreso ASC LIMIT :startFrom,:resultsPerPage" );
				$stmt->bindValue(":startFrom", (int)$start, PDO::PARAM_INT);//lo pone el valor a la variable
				$stmt->bindValue(":resultsPerPage", (int)$limit, PDO::PARAM_INT);
				$stmt->execute();
				return $stmt->fetchAll(PDO::FETCH_OBJ);
			}
			catch(Exception $e)
			{
				die($e->getMessage());
			}
		}
		public function Registrar(InventarioCosecha $InventarioCosecha)
		{
			try 
			{
			$sql = "INSERT INTO inventariocosecha (Id,Descripcion, Fechaingreso, Observaciones,TipoCosecha_Id,Unidad_Id) 
			        VALUES (?,?,?,?,?,?)";

			$this->pdo->prepare($sql)
			     ->execute(
					array(
						$InventarioCosecha->Id,
						$InventarioCosecha->Descripcion,
						$InventarioCosecha->Fechaingreso,
						$InventarioCosecha->Observaciones,
						$InventarioCosecha->TipoCosecha_Id,
						$InventarioCosecha->Unidad_Id
	                )
				);
			   return $this->pdo->lastInsertId();
			} catch (Exception $e) 
			{
				die($e->getMessage());
			}
		}

	public function Actualizar($inventariocosecha)
		{

			try 
			{
				$sql = "UPDATE inventariocosecha SET 
						Descripcion             = ?, 
						Observaciones     	    = ?,
                        FechaIngreso 			= ?,
						Unidad_Id       		= ?,
						TipoCosecha_Id			= ?
				   		WHERE Id = ?";

				$this->pdo->prepare($sql)
			     ->execute(
				    array(
                        $inventariocosecha->Descripcion,
                        $inventariocosecha->Observaciones,
                        $inventariocosecha->Fechaingreso,
                        $inventariocosecha->Unidad_Id,
                        $inventariocosecha->TipoCosecha_Id,
                        $inventariocosecha->Id
					)
				 );	
			} catch (Exception $e) 
			{
				die($e->getMessage());
			}
		}

		public function getTotalRecordsporUnidad($Id){//esto es para la paginacion
			try 
			{
				$stm = $this->pdo->prepare("SELECT * FROM  inventariocosecha Where 
					Unidad_Id =?");
				$stm->execute(array($Id));
				return $stm->rowCount();
				
			} catch (Exception $e) {
				die($e->getMessage());
			}
		}
		public function getTotalRecords(){//esto es para la paginacion
			try {
				$stm = $this->pdo->prepare("SELECT * FROM  inventariocosecha");
				$stm->execute();
				return $stm->rowCount();
				
			} catch (Exception $e) {
				die($e->getMessage());
			}
		}
public function ObtenerNombreUnidadProductivaAray($Id)
		{
			try {
				$stm = $this->pdo->prepare("SELECT * FROM inventariocosecha WHERE Unidad_Id =?");
					$stm->execute(array($Id));
					return count($stm->fetch());	
			} catch (Exception $e) {
					die($e->getMessage());
			}
		}

		public function getUnidades()
  		{
			try
			{
				$stmt = $this->pdo->prepare("SELECT * FROM UnidadesProductivas ORDER BY Nombre ASC");
				$stmt->execute();
				return $stmt->fetchAll(PDO::FETCH_OBJ);

			}
			catch(Exception $e)
			{
				die($e->getMessage());
			}
		}

	public function ObtenerNombreUnidadProductiva($Id)
	{
		try {
			$stm = $this->pdo->prepare("SELECT * FROM UnidadesProductivas WHERE Id =?");
				$stm->execute(array($Id));
				return $stm->fetch(pdo::FETCH_OBJ);	
		} catch (Exception $e) {
				die($e->getMessage());
		}
	}

	public function Obtener($Id)
	{
		try 
		{
			$stm = $this->pdo->prepare("SELECT * FROM inventariocosecha WHERE Id = ?");
			$stm->execute(array($Id));
			return $stm->fetch(PDO::FETCH_OBJ);
		} catch (Exception $e) 
		{
			die($e->getMessage());
		}
	}

	public function getcantidadbyin($Id)
    {
    	try
    	{
    		$stmt = $this->pdo->prepare("SELECT sum(Cantidad) FROM cosecha Where inventariocosecha_id = ?" );
			$stmt->execute(array($Id) );
			$row = $stmt->fetch(PDO::FETCH_NUM);
			if( $row[0] <= 0)
				return 0 ;
			else
				return $row[0];
    	}
    	catch(Exception $e){
    		die($e->getMessage());	
    	}
    }

	public function obtenerbyunidad($Id)
	{
		try
		{
			$stm = $this->pdo->prepare("SELECT * FROM inventariocosecha WHERE Unidad_Id = ? ");
			$stm->execute( array($Id) );
			return $stm->fetchAll(PDO::FETCH_OBJ);//tiene que obtener con todas las coincidencias
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}	
	}

	public function getnombreUnidadId($Id)
	{
		try
		{
			$stm = $this->pdo->prepare("SELECT * FROM UnidadesProductivas WHERE Id = ?");
			$stm->execute( array($Id) );
			return $stm->fetch(PDO::FETCH_OBJ);//tiene que obtener con todas las coincidencias
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}	
	}

	public function getTotalRecordsBusquedaByUnidad($Id, $busqueda)
	{
		if ($busqueda!='')
		{
			$busqueda = '%'.$busqueda.'%';
			//el '%',hay cualquier cosa a la izquierda y hay otra cosa a la derecha
			$stmt = $this->pdo->prepare("SELECT * FROM inventariocosecha where Unidad_Id=:unidad_Id and Descripcion LIKE :busqueda ");
			$stmt->bindparam(":busqueda", $busqueda);
			$stmt->bindValue(":unidad_Id", $Id, PDO::PARAM_INT);
			$stmt->execute();
			return $stmt->rowCount();
		} else {
			$stmt = $this->pdo->prepare("SELECT * FROM inventariocosecha where Unidad_Id=:unidad_Id");
			$stmt->bindValue(":unidad_Id", $Id, PDO::PARAM_INT);
			$stmt->execute();
			return $stmt->rowCount();
		}
	}
	
	public function getTotalRecordsBusqueda($busqueda){
		try {
		if ( $_SESSION['TipoUsuario'] == 0)//cuando no hay select
		{
			$unidadID = intval($_SESSION['Unidad_Id']);//estosaca su id de la unidad en int
			return $this->getTotalRecordsBusquedaByUnidad($unidadID, $busqueda);
		}
		else
		{

			if ($busqueda!='')
			{
				$busqueda = '%'.$busqueda.'%';
				$stmt = $this->pdo->prepare("SELECT * FROM inventariocosecha where Descripcion LIKE :busqueda ");
				$stmt->bindparam(":busqueda", $busqueda);
				$stmt->execute();
				return $stmt->rowCount();
			} else {
				$stmt = $this->pdo->prepare("SELECT * FROM inventariocosecha");
				$stmt->execute();
				return $stmt->rowCount();
			}
		}		
		
		} catch (Exception $e) {
			die($e->getMessage());
		}
	}

	public function BuscarByUnidadId($startFrom, $busqueda, $Id)
	{
		try
		{
			$limit = resultsPerPage;
			$start = $startFrom;
			$busqueda = '%'.$busqueda.'%';
			$stmt = $this->pdo->prepare("SELECT * FROM inventariocosecha where Unidad_Id=:unidad_Id and (Descripcion LIKE :busqueda) ORDER BY Id ASC LIMIT :startFrom,:resultsPerPage");
			$stmt->bindValue(":unidad_Id", $Id, PDO::PARAM_INT);
			$stmt->bindparam(":busqueda", $busqueda);
			$stmt->bindValue(":startFrom", (int)$start, PDO::PARAM_INT);
			$stmt->bindValue(":resultsPerPage", (int)$limit, PDO::PARAM_INT);
			$stmt->execute();
			return $stmt->fetchAll(PDO::FETCH_OBJ);
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}
	}

	public function Buscar($startFrom, $busqueda)
	{
		if ($_SESSION['TipoUsuario'] == 0 )
		{
			$unidadID = intval($_SESSION['Unidad_Id']);
			return $this->BuscarByUnidadId($startFrom, $busqueda, $unidadID);
		}
		else
		{
			try
			{
				$limit = resultsPerPage;
				$start = $startFrom;	
				$busqueda = '%'.$busqueda.'%';
				$stmt = $this->pdo->prepare("SELECT * FROM inventariocosecha where Descripcion LIKE :busqueda ORDER BY Id ASC LIMIT :startFrom,:resultsPerPage");
				$stmt->bindparam(":busqueda", $busqueda);
				$stmt->bindValue(":startFrom", (int)$start, PDO::PARAM_INT);
				$stmt->bindValue(":resultsPerPage", (int)$limit, PDO::PARAM_INT);
				$stmt->execute();
				return $stmt->fetchAll(PDO::FETCH_OBJ);
			}
			catch(Exception $e)
			{
				die($e->getMessage());
			}
		}
	}


	}
?>
