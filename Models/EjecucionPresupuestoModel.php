<?php

	class EjecucionPresupuesto
	{
		private $pdo;
	    
	    public $Id;
	    public $FuenteFinanciamiento;
	    public $Monto;
	    public $Presupuesto_Id;
	    public $IdentificadorConcepto;

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

		/*
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
		*/

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


		/*
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
		*/

		public function Obtener($Id)
		{
			try 
			{
				$stm = $this->pdo
				          ->prepare("SELECT * FROM EjecucionPresupuestos WHERE Id = ?");
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
				            ->prepare("DELETE FROM EjecucionPresupuestos WHERE Id = ?");			          
				$stm->execute(array($Id));
			} catch (Exception $e) 
			{
				die($e->getMessage());
			}
		}

		public function Actualizar(EjecucionPresupuesto $EjecucionPresupuesto)
		{
			try 
			{
				$sql = "UPDATE EjecucionPresupuestos SET 
							FuenteFinanciamiento= ?,
							Monto = ?,
							Presupuesto_Id = ?,
							IdentificadorConcepto = ?
					    WHERE Presupuesto_Id = ? and IdentificadorConcepto = ?";

				$this->pdo->prepare($sql)
				     ->execute(
					    array(
	                        $EjecucionPresupuesto->FuenteFinanciamiento, 
	                        $EjecucionPresupuesto->Monto,
	                        $EjecucionPresupuesto->Presupuesto_Id,
	                        $EjecucionPresupuesto->IdentificadorConcepto,
	                        $EjecucionPresupuesto->Presupuesto_Id,
	                        $EjecucionPresupuesto->IdentificadorConcepto,
						)
					);
			} catch (Exception $e) 
			{
				die($e->getMessage());
			}
		}

		public function Registrar(EjecucionPresupuesto $EjecucionPresupuesto)
		{
			try 
			{
			$sql = "INSERT INTO EjecucionPresupuestos (FuenteFinanciamiento, Monto, Presupuesto_Id,IdentificadorConcepto) 
			        VALUES (?, ?, ?, ?)";

			$this->pdo->prepare($sql)
			     ->execute(
					array(
						$EjecucionPresupuesto->FuenteFinanciamiento,
						$EjecucionPresupuesto->Monto,
						$EjecucionPresupuesto->Presupuesto_Id,
						$EjecucionPresupuesto->IdentificadorConcepto
	                )
				);
			} catch (Exception $e) 
			{
				die($e->getMessage());
			}
		}

		/*Tipo 1
		1, Terrenos</option>
        2, Edificios y Construcciones</option>
        Tipo 2
        <!--EQUIPAMIENTO-->
        <option hidden value="3" >Equipos de Cómputo y Laboratorio</option>
        <option hidden value="4" >Muebles y enseres</option>
        <option hidden value="5" >Maquinaria y equipos varios</option>
        <option hidden value="6" >Unidades de Transporte</option>
        <option hidden value="7" >Tangibles</option>
        Tipo 3
        <!--INVESTIGACIÓN Y CAPACITACIÓN-->
        <option hidden value="8" >Proyectos de Investigación</option>
        <option hidden value="9" >Otros Estudios</option>
        <option hidden value="10" >Capacitación</option>
        Tipo 4
        <!--INCENTIVOS, PUBLICACIONES Y OTROS-->
        <option hidden value="11" >Incentivos y Bonificaciones</option>
        <option hidden value="12" >Impresiones y Publicaciones</option>
        <option hidden value="13" >Otros bienes y servicios</option>
		*/
		public function getMontoEjecutadoAgrupadoPeriodoUnidadTipo($periodo, $Id, $tipo)
		{
			//$conceptoValues = array();
			//die("UNIDAD: ".$Id." Periodo: ".$periodo." Tipo:".$tipo);
			if ($tipo == 1)
			{
				//$conceptoValues= array(1,2);
				$conceptoValues = "1,2";
			}
			else if ($tipo == 2)
			{
				//$conceptoValues = array(3,4,5,6,7);
				$conceptoValues = "3,4,5,6,7";
			} else if ($tipo == 3)
			{
				//$conceptoValues = array(8,9,10);
				$conceptoValues = "8,9,10";
			} else
			{
				//$conceptoValues = array(11,12,13);
				$conceptoValues = "11,12,13";
			}

			
			//$temporal = implode(',', $conceptoValues);
			//die ($temporal);
			//die($Id.$anio.$mes.$concepto);
			try 
			{
				$stm = $this->pdo
				          ->prepare("SELECT SUM(ep.Monto) as Total from EjecucionPresupuestos ep, Presupuestos pre where ep.Presupuesto_Id = pre.Id and pre.Unidad_Id = ? and pre.Periodo = ? and ep.IdentificadorConcepto IN (".$conceptoValues.")");
				//$stm->execute(array($Id, $periodo, $conceptoValues));
				$stm->execute(array($Id, $periodo));
				$row = $stm->fetch(PDO::FETCH_NUM);
				return $row[0];
				//return $stm->fetchAll(PDO::FETCH_ASSOC);
			} catch (Exception $e) 
			{
				die($e->getMessage());
			}
		}

		///presupuestoConceptoExists
		public function presupuestoConceptoExists($presupuesto, $Id){
			try 
			{
				$stmt = $this->pdo->prepare("SELECT * FROM EjecucionPresupuestos where Presupuesto_Id=:presupuesto and IdentificadorConcepto=:concepto");
				$stmt->bindparam(":presupuesto", $presupuesto);
				$stmt->bindparam(":concepto", $Id);
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

		public function getEjecucionByPresupuestoConcepto($presupuesto_id, $concepto_id)
		{
			try 
			{
				$stm = $this->pdo
				          ->prepare("SELECT * FROM EjecucionPresupuestos where Presupuesto_Id=? and IdentificadorConcepto=? LIMIT 1");
				$stm->execute(array($presupuesto_id, $concepto_id));
				return $stm->fetchAll(PDO::FETCH_ASSOC);
			} catch (Exception $e) 
			{
				file_put_contents('php://stderr', print_r($e->getMessage(), TRUE));
				die($e->getMessage());
			}
		}
	}
?>	