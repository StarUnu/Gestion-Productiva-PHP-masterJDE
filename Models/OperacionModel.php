<?php

	class Operacion
	{
		private $pdo;
	    
	    public $Id;
	    public $Tipo;
	    public $Unidad_Id;
	    public $Fecha;
	    public $Tipo_Comprobante_Documento_Id;
	    public $Concepto;
	    public $Monto;
	    public $Mes;
	    public $Periodo;

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

		public function getPeriodos()
		{
			try
			{
				$stmt = $this->pdo->prepare("SELECT DISTINCT Periodo FROM Operaciones");
				$stmt->execute();
				return $stmt->fetchAll(PDO::FETCH_OBJ);
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
				$stmt = $this->pdo->prepare("SELECT * FROM Operaciones WHERE Unidad_Id=:unidad_Id ORDER BY Periodo, Mes DESC LIMIT :startFrom,:resultsPerPage");
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

		public function Listar($startFrom)
		{
			if ($_SESSION['TipoUsuario']==0)
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
					$stmt = $this->pdo->prepare("SELECT * FROM Operaciones ORDER BY Periodo, Mes DESC LIMIT :startFrom,:resultsPerPage");
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

		public function BuscarByUnidadId($startFrom, $busqueda, $Id)
		{
			try
			{
				$limit = resultsPerPage;
				$start = $startFrom;
				$busqueda = '%'.$busqueda.'%';
				$stmt = $this->pdo->prepare("SELECT * FROM Operaciones where Unidad_Id=:unidad_Id and Periodo LIKE :busqueda ORDER BY Periodo, Mes DESC LIMIT :startFrom,:resultsPerPage");
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


		public function Buscar($startFrom, $busqueda, $unidad)
		{
			if ($_SESSION['TipoUsuario']==0)
			{
				$unidadID = intval($_SESSION['Unidad_Id']);
				return $this->BuscarByUnidadId($startFrom, $busqueda, $unidadID);
			}
			else
			{
				if ($unidad!=-1)
				{
					return $this->BuscarByUnidadId($startFrom, $busqueda, $unidad);
				}
				try
				{
					$limit = resultsPerPage;
					$start = $startFrom;
					$busqueda = '%'.$busqueda.'%';
					$stmt = $this->pdo->prepare("SELECT * FROM Operaciones where Periodo LIKE :busqueda ORDER BY Periodo, Mes DESC LIMIT :startFrom,:resultsPerPage");
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

		public function getTotalRecordsByUnidad($Id)
		{
			try
			{
				$stmt = $this->pdo->prepare("SELECT * FROM Operaciones where Unidad_Id=:unidad_Id");
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
				if ($_SESSION['TipoUsuario']==0)
				{
					$unidadID = intval($_SESSION['Unidad_Id']);
					return $this->getTotalRecordsByUnidad($unidadID);
				}
				else
				{
					$stm = $this->pdo->prepare("SELECT * FROM Operaciones");
					$stm->execute();
					return $stm->rowCount();
				}
			} catch (Exception $e) {
				die($e->getMessage());
			}
		}

		public function getTotalRecordsBusquedaByUnidad($Id, $busqueda)
		{
			try {
				if ($busqueda!='')
				{
					$busqueda = '%'.$busqueda.'%';
					$stmt = $this->pdo->prepare("SELECT * FROM Operaciones where Unidad_Id=:unidad_Id and Periodo LIKE :busqueda");
					$stmt->bindparam(":busqueda", $busqueda);
					$stmt->bindValue(":unidad_Id", $Id, PDO::PARAM_INT);
					$stmt->execute();
					return $stmt->rowCount();
				} else {
					$stmt = $this->pdo->prepare("SELECT * FROM Operaciones where Unidad_Id=:unidad_Id");
					$stmt->bindValue(":unidad_Id", $Id, PDO::PARAM_INT);
					$stmt->execute();
					return $stmt->rowCount();
				}
				
			} catch (Exception $e) {
				die($e->getMessage());
			}
		}

		public function getTotalRecordsBusqueda($busqueda){
			try {
				if ($_SESSION['TipoUsuario']==0)
				{
					$unidadID = intval($_SESSION['Unidad_Id']);
					return $this->getTotalRecordsBusquedaByUnidad($unidadID, $busqueda);
				}
				else
				{
					if ($busqueda!='')
					{
						$busqueda = '%'.$busqueda.'%';
						$stmt = $this->pdo->prepare("SELECT * FROM Operaciones where Periodo LIKE :busqueda");
						$stmt->bindparam(":busqueda", $busqueda);
						$stmt->execute();
						return $stmt->rowCount();
					} else {
						$stmt = $this->pdo->prepare("SELECT * FROM Operaciones");
						$stmt->execute();
						return $stmt->rowCount();
					}
				}		
				
			} catch (Exception $e) {
				die($e->getMessage());
			}
		}


		public function getOperacionesByUnidadId($unidad_Id, $startFrom){
			try
			{
				$limit = resultsPerPage;
				$start = $startFrom;
				$stmt = $this->pdo->prepare("SELECT * FROM Operaciones WHERE Unidad_Id=:unidad_id ORDER BY Periodo, Mes DESC LIMIT :startFrom,:resultsPerPage");
				$stmt->bindparam(":unidad_id", $unidad_Id);
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

		public function getUnidades(){
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

		public function getUnidadById($Id){
			try 
			{
				$stm = $this->pdo
				            ->prepare("SELECT * FROM UnidadesProductivas WHERE Id = ?");			          

				$stm->execute(array($Id));
				$row = $stm->fetch(PDO::FETCH_ASSOC);
				return $row['Nombre'];
			}
			catch (Exception $e) 
			{
				die($e->getMessage());
			}
		}

		public function getMontoTotal($Id){
			try 
			{
				$stm = $this->pdo
				            ->prepare("SELECT Monto FROM Operaciones where Id = ?");			          

				$stm->execute(array($Id));
				$row = $stm->fetch(PDO::FETCH_NUM);
				return $row[0];
			}
			catch (Exception $e) 
			{
				die($e->getMessage());
			}
		}

		public function Obtener($Id)
		{
			try 
			{
				$stm = $this->pdo
				          ->prepare("SELECT * FROM Operaciones WHERE Id = ?");
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
				            ->prepare("DELETE FROM Operaciones WHERE Id = ?");			          
				$stm->execute(array($Id));
			} catch (Exception $e) 
			{
				die($e->getMessage());
			}
		}

		public function Actualizar(Operacion $operacion)
		{
			try 
 			{
				$sql = "UPDATE Operaciones SET 
							Tipo          = ?,
							Unidad_Id=?,
							Fecha=?,
							Tipo_Comprobante_Documento_Id = ?,
							Concepto = ?,
							Monto = ?,
							Mes = ?,
							Periodo = ?
					    WHERE Id = ?";

				$this->pdo->prepare($sql)
				     ->execute(
					    array(
	                        $operacion->Tipo, 
	                        $operacion->Unidad_Id,
	                        $operacion->Fecha,
	                        $operacion->Tipo_Comprobante_Documento_Id,
	                        $operacion->Concepto,
	                        $operacion->Monto,
	                        $operacion->Mes,
	                        $operacion->Periodo,
	                        $operacion->Id
						)
					);
			} catch (Exception $e) 
			{
				die($e->getMessage());
			}
		}

		public function Registrar(Operacion $operacion)
		{
			try 
			{
			$sql = "INSERT INTO Operaciones (Tipo, Unidad_Id, Fecha, Tipo_Comprobante_Documento_Id, Concepto, Monto, Mes, Periodo) 
			        VALUES (?,?,?,?, ?,?,?,?)";

			$this->pdo->prepare($sql)
			     ->execute(
					array(
						$operacion->Tipo,
						$operacion->Unidad_Id,
						$operacion->Fecha,
						$operacion->Tipo_Comprobante_Documento_Id,
						$operacion->Concepto,
						$operacion->Monto,
						$operacion->Mes,
						$operacion->Periodo
	                )
				);
			return $this->pdo->lastInsertId();
			} catch (Exception $e) 
			{
				die($e->getMessage());
			}
		}

		
		public function getIngresosAgrupadosByUnidadId($Id)
		{
			try 
			{
				$stm = $this->pdo
				          ->prepare("SELECT Descripcion, SUM(Monto) AS Total FROM Operaciones op INNER JOIN Tipo_Comprobante_Documento tcd ON op.Tipo_Comprobante_Documento_Id = tcd.Id WHERE Tipo=1 and op.Unidad_Id=? GROUP BY op.Tipo_Comprobante_Documento_Id");
				$stm->execute(array($Id));
				return $stm->fetchAll(PDO::FETCH_ASSOC);
			} catch (Exception $e) 
			{
				die($e->getMessage());
			}
		}

		public function getConceptoById($Id)
		{
			if ($Id == 1)
			{
				return 'Ventas';
			} else if ($Id == 2)
			{
				return 'Otros Ingresos';
			} else if ($Id == 3)
			{
				return 'Costos Totales';
			} else  if ($Id == 4)
			{
				return 'Costos Fijos';
			} else 
			{
				return 'Costos Variables';
			}
		}

		public function getIngresosAgrupadosMesPeriodoUnidadConcepto($Id, $anio, $mes, $concepto)
		{
			try 
			{
				$stm = $this->pdo
				          ->prepare("SELECT SUM(Monto) AS Total FROM Operaciones where Concepto=? and Unidad_Id = ? and Periodo = ? and Mes = ? GROUP BY Mes");
				$stm->execute(array($concepto, $Id, $anio, $mes));
				$row = $stm->fetch(PDO::FETCH_NUM);
				return $row[0];
				//return $stm->fetchAll(PDO::FETCH_ASSOC);
			} catch (Exception $e) 
			{
				die($e->getMessage());
			}
		}

		public function getEgresosAgrupadosByUnidadId($Id)
		{
			try 
			{
				$stm = $this->pdo
				          ->prepare("SELECT Descripcion, SUM(Monto) AS Total FROM Operaciones op INNER JOIN Tipo_Comprobante_Documento tcd ON op.Tipo_Comprobante_Documento_Id = tcd.Id WHERE Tipo=2 and op.Unidad_Id=? GROUP BY op.Tipo_Comprobante_Documento_Id");
				$stm->execute(array($Id));
				return $stm->fetchAll(PDO::FETCH_ASSOC);
			} catch (Exception $e) 
			{
				die($e->getMessage());
			}
		}
	}
?>