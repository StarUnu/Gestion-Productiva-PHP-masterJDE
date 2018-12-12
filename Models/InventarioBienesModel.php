<?php 
	class InventarioBienes
	{
		private $pdo;
		public $Id;
		public $Descripcion;
		public $EstadoOperativo;
		public $Unidad_Id;
		public $Observaciones;
		public $TipoMaterial_Id;
		/*es necesario un atributo llamado fecha de ingreso*/
		public function __construct()
		{
				try
			{
				$this->pdo=Database::Conectar();
			}
			catch(Exception $e)
			{
				die($e->getMessage());
			}
		}
		public function ListarbyUnidad($startFrom,$Id)
		{
			$limit = resultsPerPage;
			$start = $startFrom;
			$stmt= $this->pdo->prepare("SELECT * FROM InventarioBienes Where Unidad_Id=:unidad_Id ORDER BY Descripcion ASC LIMIT :startFrom,:resultsPerPage");
			$stmt->bindValue(":unidad_Id",$Id, PDO::PARAM_INT);
			$stmt->bindValue(":startFrom", (int)$start, PDO::PARAM_INT);//lo pone el valor a la variable
			$stmt->bindValue(":resultsPerPage", (int)$limit, PDO::PARAM_INT);
			$stmt->execute();
			return $stmt->fetchAll(PDO::FETCH_OBJ);
		}

		public function Listar($startFrom)
		{
			try
			{
				if($_SESSION["TipoUsuario"] == 1)
				{
					$limit = resultsPerPage;
					$start = $startFrom;
					$stmt= $this->pdo->prepare("SELECT * FROM InventarioBienes  ORDER BY Descripcion ASC LIMIT :startFrom,:resultsPerPage");
					$stmt->bindValue(":startFrom", (int)$start, PDO::PARAM_INT);//lo pone el valor a la variable
					$stmt->bindValue(":resultsPerPage", (int)$limit, PDO::PARAM_INT);
					$stmt->execute();
					return $stmt->fetchAll(PDO::FETCH_OBJ);
				}
				else{
					$unidadID= $_SESSION["Unidad_Id"];
					return $this->ListarbyUnidad($startFrom,$unidadID);
				}
			}
			catch(Exception $e)
			{
				die($e->getMessage());
			}
		}

		public function Registrar($InventarioBienes)
		{
			try
			{
				$sql = "INSERT INTO InventarioBienes (Id,EstadoOperativo,Unidad_Id,Descripcion,Observaciones,TipoMaterial_Id) 
				VALUES (?,?,?,?,?,?)";
				$this->pdo->prepare($sql)->execute(array(
					$InventarioBienes->Id,
					$InventarioBienes->EstadoOperativo,
					$InventarioBienes->Unidad_Id,
					$InventarioBienes->Descripcion,
					$InventarioBienes->Observaciones,
					$InventarioBienes->TipoMaterial_Id
				 )
				);
				return $this->pdo->lastInsertId();
			} 
			catch(Exception $e)
			{
				die($e->getMessage());
			}
		}
		public function Eliminar($Id)
		{
			try
			{
				$stm = $this->pdo->prepare("DELETE FROM InventarioBienes WHERE Id =?");
				$stm->execute(array($Id));
			}
			catch(Exception $e)
			{
				die($e->getMessage());
			}
		}
		public function getTotalRecords()
		{
			try
			{
				$stm = $this->pdo->prepare("SELECT * FROM InventarioBienes");
				$stm->execute();
				return $stm->rowCount();
			}
			catch(Exception $e)
			{
				die($e->getMessage());
			}
		}

		public function getTotalRecordsBusquedaByUnidad($Id,$busqueda)
		{
			if ($busqueda!='')
			{
				$busqueda = '%'.$busqueda.'%';
				$stmt = $this->pdo->prepare("SELECT * FROM InventarioBienes where Unidad_Id=:unidad_Id and Descripcion LIKE :busqueda ");
				$stmt->bindparam(":busqueda", $busqueda);
				$stmt->bindValue(":unidad_Id", $Id, PDO::PARAM_INT);
				$stmt->execute();
				return $stmt->rowCount();
			} else {
				$stmt = $this->pdo->prepare("SELECT * FROM InventarioBienes where Unidad_Id=:unidad_Id");
				$stmt->bindValue(":unidad_Id", $Id, PDO::PARAM_INT);
				$stmt->execute();
				return $stmt->rowCount();
			}
		}

		public function getTotalRecordsBusqueda($busqueda)
		{
		    try {
			if ( $_SESSION['TipoUsuario'] == 0 )//cuando no hay select
			{
				$unidadID = intval($_SESSION['Unidad_Id']);//estosaca su id de la unidad en int
				return $this->getTotalRecordsBusquedaByUnidad($unidadID, $search);
			}
			else
			{
				if ($busqueda!='')
				{
					$busqueda = '%'.$busqueda.'%';
					$stmt = $this->pdo->prepare("SELECT * FROM InventarioBienes where Descripcion LIKE :busqueda ");
					$stmt->bindparam(":busqueda", $busqueda);
					$stmt->execute();
					return $stmt->rowCount();
				} else {

					$stmt = $this->pdo->prepare("SELECT * FROM InventarioBienes");
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
			$stmt = $this->pdo->prepare("SELECT * FROM InventarioBienes where Unidad_Id=:unidad_Id and (Descripcion LIKE :busqueda) ORDER BY Id ASC LIMIT :startFrom,:resultsPerPage ");
			$stmt->bindparam(":busqueda", $busqueda);
			$stmt->bindValue(":unidad_Id", $Id, PDO::PARAM_INT);
			$stmt->bindValue(":startFrom", (int)$start, PDO::PARAM_INT);
			$stmt->bindValue(":resultsPerPage", (int)$limit, PDO::PARAM_INT);
			$stmt->execute();
			return $stmt->fetchAll(PDO::FETCH_OBJ);
			}catch(Exception $e)
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
					$stmt = $this->pdo->prepare("SELECT * FROM InventarioBienes where Descripcion LIKE :busqueda ORDER BY Id ASC LIMIT :startFrom,:resultsPerPage");
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

	public function Obtener($Id)
	 {
	   try 
	   {
	    $stmt=$this->pdo->prepare("SELECT * FROM InventarioBienes Where Id = ?");
	    $stmt->execute( array($Id) );
	    return $stmt->fetch(PDO::FETCH_OBJ);
				} 
				catch (Exception $e) 
				{
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
		public function Actualizar(InventarioBienes $InventarioBienes)
		{
			try 
			{
				$sql = "UPDATE InventarioBienes SET 
     				EstadoOperativo   	=  ?,
     				Unidad_Id			=  ?,
     				Descripcion         =  ?,
     				Observaciones	    =  ?,
     				TipoMaterial_Id		=  ?
			  		WHERE Id = ? ";
			  //me sale que el numero de tokens es incorecto por esto le aumente mas el id
				$this->pdo->prepare($sql)->execute(array(		
				 $InventarioBienes->EstadoOperativo,
				 $InventarioBienes->Unidad_Id,
				 $InventarioBienes->Descripcion,
				 $InventarioBienes->Observaciones,
				 $InventarioBienes->TipoMaterial_Id,
				 $InventarioBienes->Id//aqui en especial
			 	 )
			 	);	
			} catch (Exception $e) 
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

	public function ObtenerNombreUnidadProductivaAray($Id)
	{
		try {
			$stm = $this->pdo->prepare("SELECT * FROM InventarioBienes WHERE Unidad_Id =?");
				$stm->execute(array($Id));
				return count($stm->fetch());	
		} catch (Exception $e) {
				die($e->getMessage());
		}
	}

	public function Obtenerbienes()
	{
		try{
			$stm = $this->pdo->prepare("SELECT * FROM Bienes ");
				$stm->execute();
				return $stm->fetchAll(pdo::FETCH_OBJ);	
		}
		catch(Exception $e){
			die($e->getMessage());
		}
	}

	public function getidcentralproductiva($centroproductivo)
	{
		try 
		{
			$stm = $this->pdo
			            ->prepare("SELECT * FROM UnidadesProductivas WHERE Nombre = ?");			          
			$stm->execute(array($centroproductivo));
			return $stm ->fetch(PDO::FETCH_OBJ);
		} catch (Exception $e)
		{
			die($e->getMessage());
		}	
	}

    public function getunidadByid($unidadid)
    {
    	try
    	{
    		$stmt = $this->pdo->prepare("SELECT * FROM UnidadesProductivas Where Id = ?");
			$stmt->execute(array($unidadid) );
			return $stmt->fetch(PDO::FETCH_OBJ);
    	}
    	catch(Exception $e){
    		die($e->getMessage());	
    	}
    }


    public function getunidadByidAll($unidadid)
    {
    	try
    	{
    		$stmt = $this->pdo->prepare("SELECT * FROM InventarioBienes Where Unidad_Id = ?");
			$stmt->execute(array($unidadid) );
			return $stmt->fetchAll(PDO::FETCH_OBJ);
    	}
    	catch(Exception $e){
    		die($e->getMessage());	
    	}
    }

    public function getcantidadbyin($Id)
    {
    	try
    	{
    		$stmt = $this->pdo->prepare("SELECT sum(Cantidad) FROM Bienes Where InventarioBien_Id = ?");
			$stmt->execute(array($Id) );
			$row = $stmt->fetch(PDO::FETCH_NUM);
			if($row[0] <= 0 )
				return 0;
			else
				return $row[0];
    	}
    	catch(Exception $e){
    		die($e->getMessage());	
    	}
    }
}
?>
