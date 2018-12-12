<?php
//averiguar que es lo que coresponde inventario de bienes???
class Bienes
{
	public $pdo;
	public $Id;
	public $InventarioBien_Id;
	public $Cantidad;
	public $Estado;
	function __construct()
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

		public function Listar($startFrom)
		{
			try
			{
				$limit = resultsPerPage;
				$start = $startFrom;
				$stmt= $this->pdo->prepare("SELECT * FROM Bienes ORDER BY Descripcion ASC LIMIT :startFrom,:resultsPerPage");
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

		public function Registrar($Bienes)
		{
			try
			{
				$sql = "INSERT INTO Bienes (Id,InventarioBien_Id,Cantidad,Estado) 
				VALUES (?,?,?,?)";
				$this->pdo->prepare($sql)->execute(array(
					$Bienes->Id,
					$Bienes->InventarioBien_Id,
					$Bienes->Cantidad,
					$Bienes->Estado
				 )
				);
			} 
			catch(Exception $e)
			{
				die($e->getMessage());
			}
		}


		public function Actualizar($Bienes1)
		{
			try 
			{
				$sql = "UPDATE  Bienes SET
					InventarioBien_Id   =?,
					Cantidad            =?,
					Estado              =?,
			  	WHERE Id = ?";

				$this->pdo->prepare($sql)->execute(array(		
			     $Bienes1->InventarioBien_Id,
			     $Bienes1->Cantidad,
			     $Bienes1->Estado,
			     $Bienes1->Id
			  )
			 );	
			} catch (Exception $e) 
			{
				die($e->getMessage());
			}
		}

		public function Eliminar($Id)
		{
			try
			{
				$stm = $this->pdo->prepare("DELETE FROM Bienes WHERE Id =?");
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
				$stm = $this->pdo->prepare("SELECT * FROM Bienes");
				$stm->execute();
				return $stm->rowCount();
			}
			catch(Exception $e)
			{
				die($e->getMessage());
			}
		}

		public function obteneridbienes($Id)
		{
			try
			{
				$stm = $this->pdo->prepare("SELECT * FROM Bienes WHERE Id =?");
				$stm->execute(array($Id));
				return $stm->fetchAll(PDO::FETCH_OBJ);
			}
			catch(Exception $e)
			{
				die($e->getMessage());
			}
		}

		public function obtenerporidinventario($Id)
		{
			try
			{
				$stm = $this->pdo->prepare("SELECT * FROM Bienes WHERE InventarioBien_Id = ? ");
				$stm->execute( array($Id) );
	
				return $stm->fetchAll(PDO::FETCH_OBJ);//tiene que obtener con todas las coincidencias
			}
			catch(Exception $e)
			{
				die($e->getMessage());
			}	
		}

		public function obtenerporidinventarioarra($Id)
		{
			try
			{
				$stm = $this->pdo->prepare("SELECT * FROM Bienes WHERE InventarioBien_Id = ? ");
				$stm->execute( array($Id) );
	
				return $stm->fetchAll();//tiene que obtener con todas las coincidencias
			}
			catch(Exception $e)
			{
				die($e->getMessage());
			}	
		}

		public function getipoid($id)
	    {
	        try
	    	{
			$stmt = $this->pdo->prepare("SELECT * FROM inventariobienes Where Unidad_Id =?");
				$stmt->execute(array($id));
				echo "asmaso";
				return $stmt->fetchAll(PDO::FETCH_OBJ);
	    	}
	    	catch(Exception $e){
	    		die($e->getMessage());	
	    	}	
	    }

	public function getbyestadobad($Id)
	{
    	try
    	{//seria poderosa si esque lo pongo en una columna como en el alagebra relacional
    		$stmt = $this->pdo->prepare("SELECT sum(Cantidad) FROM Bienes Where InventarioBien_Id = ? and Estado = 0 ");
			$stmt->execute(array($Id) );
			$row = $stmt->fetch(PDO::FETCH_NUM);
			if($row[0]>0)
				return $row[0] ;
			else return 0;
    	}
    	catch(Exception $e){
    		die($e->getMessage());	
    	}
	}

	public function getbyestadoregular($Id)
	{
    	try
    	{//seria poderosa si esque lo pongo en una columna como en el alagebra relacional
    		$stmt = $this->pdo->prepare("SELECT sum(Cantidad) FROM Bienes Where InventarioBien_Id = ? and Estado = 1 ");
			$stmt->execute(array($Id) );
			$row = $stmt->fetch(PDO::FETCH_NUM);
			
			if($row[0]>0)
				return $row[0] ;
			else return 0;
    	}
    	catch(Exception $e){
    		die($e->getMessage());	
    	}
	}

	public function getbyestadogood($Id)
	{
    	try
    	{//seria poderosa si esque lo pongo en una columna como en el alagebra relacional
    		$stmt = $this->pdo->prepare("SELECT sum(Cantidad) FROM Bienes Where InventarioBien_Id = ? and Estado = 2 ");
			$stmt->execute(array($Id) );
			$row =$stmt->fetch(PDO::FETCH_NUM);
			if($row[0]>0)
				return $row[0] ;
			else return 0;
    	}
    	catch(Exception $e){
    		die($e->getMessage());	
    	}
	}

	public function getInventarioId($Id)
    {
    	try
    	{
    		$stmt = $this->pdo->prepare("SELECT * FROM InventarioBienes Where Unidad_Id = ?");
			$stmt->execute(array($Id) );
			return $stmt->fetchAll(PDO::FETCH_OBJ);
    	}
    	catch(Exception $e){
    		die($e->getMessage());	
    	}
    }

    public function getnombrebyidtipomaterial($Id)
    {
    	try
    	{
    		$stmt = $this->pdo->prepare("SELECT * FROM TipoMaterial Where Id  = ?");
			$stmt->execute(array($Id) );
			return $stmt->fetch(PDO::FETCH_OBJ);
    	}
    	catch(Exception $e){
    		die($e->getMessage());	
    	}
    }
}
?>