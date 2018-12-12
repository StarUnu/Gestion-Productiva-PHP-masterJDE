<?php

class InventarioAnimales
{
	private $pdo;

	public $Id;
	public $Descripcion;
	public $Observaciones;
	public $FechaIngreso;
	public $Unidad_Id;
	public $tipoanimal_id;

	function __construct()
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
		    $stmt = $this->pdo->prepare("SELECT * FROM inventarioanimales ORDER BY Descripcion ASC LIMIT :startFrom,:resultsPerPage" );
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

	public function Actualizar($inventarioanimales)
		{

			try 
			{
				$sql = "UPDATE inventarioanimales SET 
						Descripcion             = ?, 
						Observaciones     	    = ?,
                        FechaIngreso 			= ?,
						Unidad_Id       		= ?,
						tipoanimal_id			= ?
				   		WHERE Id = ?";

				$this->pdo->prepare($sql)
			     ->execute(
				    array(
                        $inventarioanimales->Descripcion,
                        $inventarioanimales->Observaciones,
                        $inventarioanimales->FechaIngreso,
                        $inventarioanimales->Unidad_Id,
                        $inventarioanimales->tipoanimal_id,
                        $inventarioanimales->Id
					)
				 );	
			} catch (Exception $e) 
			{
				die($e->getMessage());
			}
		}

		public function Registrar( $inventariodeseresvivos)
		{
			try 
			{
			  // $this->pdo->beginTransaction();	//prque se pone esto aqui??
				echo "estasaisn";
				$sql = "INSERT INTO inventarioanimales (Id,Descripcion,Observaciones,FechaIngreso,Unidad_Id,tipoanimal_id) 
				        VALUES (?,?,?,?,?,?)";
//estos son los datos que envias
				$this->pdo->prepare($sql)
				     ->execute(
						array(
							$inventariodeseresvivos->Id,
		                    $inventariodeseresvivos->Descripcion,
		                    $inventariodeseresvivos->Observaciones,
		                    $inventariodeseresvivos->FechaIngreso,
		                    $inventariodeseresvivos->Unidad_Id,
		                    $inventariodeseresvivos->tipoanimal_id,
		                )
					);
				return $this->pdo->lastInsertId();
			} catch (Exception $e) 
			{
				die($e->getMessage());
			}
		}

		public function Eliminar($Id)
		{
			try 
			{
				$stm = $this->pdo
				            ->prepare("DELETE FROM inventarioanimales WHERE Id = ?");			          
				$stm->execute(array($Id));
			} catch (Exception $e)
			{
				die($e->getMessage());
			}
		}


		public function getTotalRecords()
		{
			try
			{
				$stm = $this->pdo->prepare("SELECT * FROM inventarioanimales");
				$stm->execute();
				return $stm->rowCount();
			}
			catch(Exception $e)
			{
				die($e->getMessage());
			}
		}

		public function ObtenerNombreUnidadProductivaAray($Id)
		{
			try {
				$stm = $this->pdo->prepare("SELECT * FROM inventarioanimales WHERE Unidad_Id =?");
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
			$stm = $this->pdo
			          ->prepare("SELECT * FROM inventarioanimales WHERE Id = ?");
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
    		$stmt = $this->pdo->prepare("SELECT sum(Cantidad) FROM Animales Where inventariodeseresvivos_Id = ?");
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

	public function obtenerbyunidad($Id,$periodo)
	{
		try
		{
			$stm = $this->pdo->prepare("SELECT * FROM inventarioanimales WHERE Unidad_Id = ? ORDER By Descripcion ");
			$stm->execute( array($Id) );
			return $stm->fetchAll(PDO::FETCH_OBJ);//tiene que obtener con todas las coincidencias
		}
		catch(Exception $e)
		{
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
			$stmt = $this->pdo->prepare("SELECT * FROM inventarioanimales where Unidad_Id=:unidad_Id and (Descripcion LIKE :busqueda) ORDER BY Id ASC LIMIT :startFrom,:resultsPerPage");
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
				$stmt = $this->pdo->prepare("SELECT * FROM inventarioanimales where Descripcion LIKE :busqueda ORDER BY Id ASC LIMIT :startFrom,:resultsPerPage");
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

	public function getTotalRecordsBusquedaByUnidad($Id, $busqueda)
	{
		if ($busqueda!='')
		{
			$busqueda = '%'.$busqueda.'%';
			$stmt = $this->pdo->prepare("SELECT * FROM inventarioanimales where Unidad_Id=:unidad_Id and Descripcion LIKE :busqueda ");
			$stmt->bindparam(":busqueda", $busqueda);
			$stmt->bindValue(":unidad_Id", $Id, PDO::PARAM_INT);
			$stmt->execute();
			return $stmt->rowCount();
		} else {
			$stmt = $this->pdo->prepare("SELECT * FROM inventarioanimales where Unidad_Id=:unidad_Id");
			$stmt->bindValue(":unidad_Id", $Id, PDO::PARAM_INT);
			$stmt->execute();
			return $stmt->rowCount();
		}
	}

	public function getTotalRecordsBusqueda($busqueda)
	{
		try {

		if ( $_SESSION['TipoUsuario'] == 0)
		{
			$unidadID = intval($_SESSION['Unidad_Id']);//estosaca su id de la unidad en int
			return $this->getTotalRecordsBusquedaByUnidad($unidadID, $busqueda);
		}
		else
		{
			if ($busqueda!='')
			{
				$busqueda = '%'.$busqueda.'%';
				$stmt = $this->pdo->prepare("SELECT * FROM inventarioanimales where Descripcion LIKE :busqueda ");
				$stmt->bindparam(":busqueda", $busqueda);
				$stmt->execute();
				return $stmt->rowCount();
			} else {

				$stmt = $this->pdo->prepare("SELECT * FROM inventarioanimales");
				$stmt->execute();
				return $stmt->rowCount();
			}
		}		
		
		} catch (Exception $e) {
			die($e->getMessage());
		}
	}		
}
?>