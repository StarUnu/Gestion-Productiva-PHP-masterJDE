<?php
	class InventarioFisicoDe
	{
		private $pdo;
	    
	    public $Id;
	    public $Cantidad;
	    public $Estado;
	    public $InventarioFisico_Id;

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


		public function getDetallesByIFisicoId($Id){
			try
			{
				$stm = $this->pdo->prepare("SELECT  do.Id, do.Cantidad, do.Estado FROM InventarioFisico_Detalle  do WHERE do.InventarioFisico_Id = ?");
				$stm->execute(array($Id));

				return $stm->fetchAll(PDO::FETCH_OBJ);
			}
			catch(Exception $e)
			{
				die($e->getMessage());
			}
		}

		public function Listar2($Id)//$startFrom ,
		{
			try
			{
				//$limit = resultsPerPage;
				//$start = $startFrom;
				$stmt = $this->pdo->prepare("SELECT * FROM InventarioFisico_Detalle WHERE InventarioFisico_Id = ?");

				$stmt->execute( array($Id) );
				$row = $stmt->fetchAll(PDO::FETCH_OBJ);
				$cont =count($row);
				return $row;
			}
			catch(Exception $e)
			{
				die($e->getMessage());
			}
		}

		public function Actualizar($Inventariofisicode)
		{
			try 
			{
				if ($Inventariofisicode->Cantidad!=null){

					$sql = "UPDATE InventarioFisico_Detalle SET 
							Cantidad            =  ?, 
							Estado              =  ?,
							InventarioFisico_Id = ?
					   		WHERE Id = ?";

					$this->pdo->prepare($sql)
				     ->execute(
					    array(
	                        $Inventariofisicode->Cantidad,
	                        $Inventariofisicode->Estado,
	                        $Inventariofisicode->InventarioFisico_Id,
	                        $Inventariofisicode->Id
						)
					 );	
					 echo "isdjsodijodondesaleeror";
				} 
			} catch (Exception $e) 
			{
				die($e->getMessage());
			}
		}

		public function Registrar(Inventariofisicode $inventariofisicode)
		{
			try 
			{

				$sql = "INSERT INTO InventarioFisico_Detalle (Id,Cantidad,Estado,InventarioFisico_Id) 
				        VALUES (?,?,?,?)";
				$this->pdo->prepare($sql)
				     ->execute(
						array(
							$inventariofisicode->Id,
							$inventariofisicode->Cantidad, 
							$inventariofisicode->Estado,
		                    $inventariofisicode->InventarioFisico_Id
		                )
					);
				//$this->pdo->commit();
			} catch (Exception $e) 
			{
				die($e->getMessage());
			}
		}

		public function getTotalRecords(){
			try {
				$stm = $this->pdo->prepare("SELECT * FROM InventarioFisico_Detalle");
				$stm->execute();
				return $stm->rowCount();
				
			} catch (Exception $e) {
				die($e->getMessage());
			}
		}

		public function Obtener($Id)
        {
            try {
                $stmt=$this->pdo->prepare("SELECT * FROM InventarioFisico_Detalle Where Id = ?");
                $stmt->execute( array($Id) );
                return $stmt->fetch(PDO::FETCH_OBJ);
            } catch (Exception $e) {
                die($e->getMessage());
            }
        }

		public function getporid($Id)
		{
			try 
			{
				$stm = $this->pdo
				          ->prepare("SELECT * FROM InventarioFisico_Detalle WHERE Id = ?");
					          
				$stm->execute(array($Id));
				return $stm->fetch(PDO::FETCH_OBJ);
			} catch (Exception $e) 
			{
				die($e->getMessage());
			}
		}

		public function getporUnidadID($Id)
		{
			try 
			{
				$stm = $this->pdo
				          ->prepare("SELECT * FROM InventarioFisico_Detalle WHERE InventarioFisico_Id = ?");
					          
				$stm->execute(array($Id));
				return $stm->fetchAll();//esto lo saca como un objeto PDO::FETCH_OBJ
			} catch (Exception $e) 
			{
				die($e->getMessage());
			}
		}

		public function getMarca($Id)//esta la tengo repetida en inventario fisico
		{
			try 
			{
				$stm = $this->pdo
				          ->prepare("SELECT * FROM Material_Insumo WHERE  Id = ?");
					          
				$stm->execute(array($Id));
				return $stm->fetch(PDO::FETCH_OBJ);
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
				          ->prepare("DELETE FROM InventarioFisico_Detalle WHERE  Id = ?");
					          
				$stm->execute(array($Id));
			} catch (Exception $e) 
			{
				die($e->getMessage());
			}
		}

		public function cantidades_estado($Id)
		{
			try 
			{
				$stm = $this->pdo
				          ->prepare("SELECT * FROM InventarioFisico_Detalle WHERE  InventarioFisico_Id = ?");
				$stm->execute(array($Id));
				$stm = $stm->fetchAll(PDO::FETCH_OBJ);
				$arraycanti=array();
				$arraycanti[0]=0;
				$arraycanti[1]=0;
				$arraycanti[2]=0;
				foreach($stm as $r):
					if($r->Estado ==0)//si es malo
						$arraycanti[0]=$arraycanti[0]+$r->Cantidad;
					if($r->Estado == 1)
						$arraycanti[1]=$arraycanti[1]+$r->Cantidad;
					if($r->Estado == 2)
						$arraycanti[2]=$arraycanti[2]+$r->Cantidad;
				endforeach;
				return $arraycanti;
			} catch (Exception $e) 
			{
				die($e->getMessage());
			}	
		}
	
		public function getbyestadobad($Id)
	{
    	try
    	{//seria poderosa si esque lo pongo en una columna como en el alagebra relacional
    		$stmt = $this->pdo->prepare("SELECT sum(Cantidad) FROM InventarioFisico_Detalle Where InventarioFisico_Id = ? and Estado = 0 ");
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
    		$stmt = $this->pdo->prepare("SELECT sum(Cantidad) FROM InventarioFisico_Detalle Where InventarioFisico_Id = ? and Estado = 1 ");
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
    		$stmt = $this->pdo->prepare("SELECT sum(Cantidad) FROM InventarioFisico_Detalle Where InventarioFisico_Id = ? and Estado = 2 ");
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