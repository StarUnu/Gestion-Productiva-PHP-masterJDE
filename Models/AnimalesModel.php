<?php
class Animales
{
	private $pdo;

	public $Id;
	public $Inventariodeseresvivos_Id;
	public $Cantidad;
	public $Estado;

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
			echo "termina de ejecutar listar";
		    $stmt = $this->pdo->prepare("SELECT * FROM animales ORDER BY Estado ASC LIMIT :startFrom,:resultsPerPage" );
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

	public function Actualizar($detalleequipo)
		{

			try 
			{

				$sql = "UPDATE animales SET 
						Inventariodeseresvivos_Id     	        = ?, 
						Cantidad				          	    = ?,
						Estado       							= ?
				   		WHERE Id = ?";

				$this->pdo->prepare($sql)
			     ->execute(
				    array(
                        $detalleequipo->Inventariodeseresvivos_Id,
                        $detalleequipo->Cantidad,
                        $detalleequipo->Estado,
                        $detalleequipo->Id
					)
				 );	
			} catch (Exception $e) 
			{
				die($e->getMessage());
			}
		}

	public function Registrar(Animales $detalleoperacion)
	{
		try 
		{
		  // $this->pdo->beginTransaction();	//prque se pone esto aqui??

			$sql = "INSERT INTO animales (Id,Inventariodeseresvivos_Id,Cantidad,Estado) 
			        VALUES (?,?,?,?) ";
//estos son los datos que envias
			$this->pdo->prepare($sql)
			     ->execute(
					array(
						$detalleoperacion->Id,
	                    $detalleoperacion->Inventariodeseresvivos_Id,
	                    $detalleoperacion->Cantidad,
	                    $detalleoperacion->Estado
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
			$stm = $this->pdo
			            ->prepare("DELETE FROM animales WHERE Id = ?");			          
			$stm->execute(array($Id));
		} catch (Exception $e)
		{
			die($e->getMessage());
		}
	}

	public function obtenerporidinventario($Id)
	{
		try
		{
			$stm = $this->pdo->prepare("SELECT * FROM animales WHERE Inventariodeseresvivos_Id = ? ");
			$stm->execute( array($Id) );

			return $stm->fetchAll(PDO::FETCH_OBJ);//tiene que obtener con todas las coincidencias
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}	
	}

	public function obtenerporidinventarioarray($Id)
	{
		try
		{
			$stm = $this->pdo->prepare("SELECT * FROM animales WHERE Inventariodeseresvivos_Id = ? ");
			$stm->execute( array($Id) );

			return $stm->fetchAll();//tiene que obtener con todas las coincidencias
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}	
	}

	public function getdescribyidanimal($Id)
	{
		try
		{
			$stm = $this->pdo->prepare("SELECT * FROM animales WHERE Id = ? ");
			$stm->execute( array($Id) );

			return $stm->fetchAll(PDO::FETCH_OBJ);//tiene que obtener con todas las coincidencias
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}	
	}

	public function getbyestadobad($Id)
	{
    	try
    	{//seria poderosa si esque lo pongo en una columna como en el alagebra relacional
    		$stmt = $this->pdo->prepare("SELECT sum(Cantidad) FROM animales Where Inventariodeseresvivos_Id = ? and Estado = 0 ");
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
    		$stmt = $this->pdo->prepare("SELECT sum(Cantidad) FROM animales Where Inventariodeseresvivos_Id = ? and Estado = 1 ");
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
    		$stmt = $this->pdo->prepare("SELECT sum(Cantidad) FROM animales Where Inventariodeseresvivos_Id = ? and Estado = 2 ");
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
}
?>