<?php

	class Presupuesto
	{
		private $pdo;
	    
	    public $Id;
	    public $Periodo;
	    public $Asignado;
	    public $Unidad_Id;

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

		public function ListarByUnidad($startFrom,$Id)
		{
			try
			{
				$limit = resultsPerPage;
				$start = $startFrom;
				$stmt = $this->pdo->prepare("SELECT * FROM Presupuestos where Unidad_Id=:unidad_Id ORDER BY Periodo DESC LIMIT :startFrom,:resultsPerPage");
				$stmt->bindValue(":unidad_Id", $Id, PDO::PARAM_INT);
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

		public function getAllByUnidad($Id)
		{
			try
			{
				$stmt = $this->pdo->prepare("SELECT * FROM Presupuestos where Unidad_Id=:unidad_Id ORDER BY Periodo DESC");
				$stmt->bindValue(":unidad_Id", $Id, PDO::PARAM_INT);
				$stmt->execute();
				return $stmt->fetchAll(PDO::FETCH_OBJ);
			}
			catch(Exception $e)
			{
				die($e->getMessage());
			}
		}	

		public function Listar($startFrom)
		{
			if (isset($_SESSION['Unidad_Id']))
			{
				$unidadID = intval($_SESSION['Unidad_Id']);
				return $this->ListarByUnidad($startFrom,$unidadID);
			}
			else
			{
				try
				{
					$limit = resultsPerPage;
					$start = $startFrom;
					$stmt = $this->pdo->prepare("SELECT * FROM Presupuestos ORDER BY Periodo DESC LIMIT :startFrom,:resultsPerPage");
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

		/*
		public function BuscarByUnidadId($startFrom, $busqueda, $Id)
		{
			try
			{
				$limit = resultsPerPage;
				$start = $startFrom;
				$busqueda = '%'.$busqueda.'%';
				$stmt = $this->pdo->prepare("SELECT * FROM Presupuestos where Unidad_Id=:unidad_Id and (Nombres LIKE :busqueda or Apellidos LIKE :busqueda) ORDER BY Apellidos ASC LIMIT :startFrom,:resultsPerPage");
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
			if (isset($_SESSION['Unidad_Id']))
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
					$stmt = $this->pdo->prepare("SELECT * FROM Personas where Nombres LIKE :busqueda or Apellidos LIKE :busqueda ORDER BY Apellidos ASC LIMIT :startFrom,:resultsPerPage");
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
		*/	


		public function getTotalRecordsByUnidad($Id)
		{
			try
			{
				$stmt = $this->pdo->prepare("SELECT * FROM Presupuestos where Unidad_Id=:unidad_Id");
				$stmt->bindValue(":unidad_Id", $Id, PDO::PARAM_INT);
				$stmt->execute();
				return $stmt->rowCount();
			}
			catch(Exception $e)
			{
				die($e->getMessage());
			}
		}

		public function getTotalRecords(){
			try {
				if (isset($_SESSION['Unidad_Id']))
				{
					$unidadID = intval($_SESSION['Unidad_Id']);
					return $this->getTotalRecordsByUnidad($unidadID);
				}
				else
				{
					$stm = $this->pdo->prepare("SELECT * FROM Presupuestos");
					$stm->execute();
					return $stm->rowCount();
				}
			} catch (Exception $e) {
				die($e->getMessage());
			}
		}

		public function Obtener($Id)
		{
			try 
			{
				$stm = $this->pdo
				          ->prepare("SELECT * FROM Presupuestos WHERE Id = ?");
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
				            ->prepare("DELETE FROM Presupuestos WHERE Id = ?");			          
				$stm->execute(array($Id));
			} catch (Exception $e) 
			{
				die($e->getMessage());
			}
		}

		public function Actualizar(Presupuesto $Presupuesto)
		{
			try 
			{
				$sql = "UPDATE Presupuestos SET 
							Periodo= ?,
							Asignado = ?,
							Unidad_Id = ?
					    WHERE Unidad_Id = ? and Periodo=?";

				$this->pdo->prepare($sql)
				     ->execute(
					    array(
	                        $Presupuesto->Periodo, 
	                        $Presupuesto->Asignado,
	                        $Presupuesto->Unidad_Id,
	                        $Presupuesto->Unidad_Id,
	                        $Presupuesto->Periodo
						)
					);
			} catch (Exception $e) 
			{
				die($e->getMessage());
			}
		}

		public function Registrar(Presupuesto $Presupuesto)
		{
			try 
			{
			$sql = "INSERT INTO Presupuestos (Periodo, Asignado, Unidad_Id) 
			        VALUES (?, ?, ?)";

			$this->pdo->prepare($sql)
			     ->execute(
					array(
						$Presupuesto->Periodo,
						$Presupuesto->Asignado,
						$Presupuesto->Unidad_Id,
	                )
				);
			} catch (Exception $e) 
			{
				die($e->getMessage());
			}
		}


		///PeriodoUnidad exists
		public function periodoUnidadExists($periodo, $Id){
			try 
			{
				$stmt = $this->pdo->prepare("SELECT * FROM Presupuestos where Periodo=:periodo and Unidad_Id=:unidad");
				$stmt->bindparam(":periodo", $periodo);
				$stmt->bindparam(":unidad", $Id);
				$stmt->execute();
				if($stmt->rowCount() > 0){
					return true;
				}
				else{
					return false;
				}
			} 
			catch (Exception $e) 
			{
				die($e->getMessage());
			}
		}
	}
?>