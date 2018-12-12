<?php

	class Documento
	{
		private $pdo;
	    
	    public $Id;
	    public $Unidad_Id;
	    public $Fecha_Legalizacion;
	    public $Numero_Folios;
	    public $EstadoOperativo;
	    public $Observaciones;
	    public $Descripcion;
	    public $Tipo_Documento_Id;
	    public $Numero;
	    public $EnlaceDigital;
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


		public function ListarByUnidad($startFrom,$Id)
		{
			try
			{
				$limit = resultsPerPage;
				$start = $startFrom;
				$stmt = $this->pdo->prepare("SELECT * FROM DocumentoExistente where Unidad_Id=:unidad_Id ORDER BY Fecha_Legalizacion ASC LIMIT :startFrom,:resultsPerPage");
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
					$stmt = $this->pdo->prepare("SELECT * FROM DocumentoExistente ORDER BY Fecha_Legalizacion ASC LIMIT :startFrom,:resultsPerPage");
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

		public function getAll()
		{
			try
				{
					$stmt = $this->pdo->prepare("SELECT * FROM DocumentoExistente ORDER BY Fecha_Legalizacion ASC");
					$stmt->execute();
					return $stmt->fetchAll(PDO::FETCH_OBJ);
				}
				catch(Exception $e)
				{
					die($e->getMessage());
				}
		}

		public function getAllByUnidadIdAndPeriodo($Id, $periodo)
		{
			try
			{
				$stmt = $this->pdo->prepare("SELECT * FROM DocumentoExistente WHERE Unidad_Id=:unidad_Id AND Periodo=:periodo ORDER BY Fecha_Legalizacion ASC");
				$stmt->bindValue(":unidad_Id", $Id, PDO::PARAM_INT);
				$stmt->bindValue(":periodo", $periodo, PDO::PARAM_INT);
				$stmt->execute();
				return $stmt->fetchAll(PDO::FETCH_OBJ);
			}
			catch(Exception $e)
			{
				die($e->getMessage());
			}
		}
		

		public function getAllByUnidadId($Id)
		{
			try
			{
				$stmt = $this->pdo->prepare("SELECT * FROM DocumentoExistente WHERE Unidad_Id=:unidad_Id ORDER BY Fecha_Legalizacion ASC");
				$stmt->bindValue(":unidad_Id", $Id, PDO::PARAM_INT);
				$stmt->execute();
				return $stmt->fetchAll(PDO::FETCH_OBJ);
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
				$stmt = $this->pdo->prepare("SELECT * FROM DocumentoExistente where Unidad_Id=:unidad_Id and Descripcion LIKE :busqueda ORDER BY Descripcion ASC LIMIT :startFrom,:resultsPerPage");
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
				else 
				{
					try
					{
						$limit = resultsPerPage;
						$start = $startFrom;
						$busqueda = '%'.$busqueda.'%';
						$stmt = $this->pdo->prepare("SELECT * FROM DocumentoExistente where Descripcion LIKE :busqueda ORDER BY Descripcion ASC LIMIT :startFrom,:resultsPerPage");
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

		public function Obtener($Id)
		{
			try 
			{
				$stm = $this->pdo
				          ->prepare("SELECT * FROM DocumentoExistente WHERE Id = ?");
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
			            ->prepare("DELETE FROM DocumentoExistente WHERE Id = ?");
			$stm->execute(array($Id));
			} catch (Exception $e) 
			{
				die($e->getMessage());
			}
		}

		public function Actualizar(Documento $documento)
		{
			try 
			{
				if ($documento->EnlaceDigital!=null){
					$sql = "UPDATE DocumentoExistente SET 
						Descripcion          = ?, 
						Tipo_Documento_Id        = ?,
                        Numero        		= ?,
						Fecha_Legalizacion        = ?, 
						Numero_Folios  = ?,
						EstadoOperativo 			= ?, 
						Observaciones 		= ?, 
						Unidad_Id 		= ?,
						EnlaceDigital = ?,
						Periodo = ?
				    WHERE Id = ?";

					$this->pdo->prepare($sql)
				     ->execute(
					    array(
	                        $documento->Descripcion, 
	                        $documento->Tipo_Documento_Id,
	                        $documento->Numero,
	                        $documento->Fecha_Legalizacion,
	                        $documento->Numero_Folios,
	                        $documento->EstadoOperativo,
	                        $documento->Observaciones,
	                        $documento->Unidad_Id,
	                        $documento->EnlaceDigital,
	                        $documento->Periodo,
	                        $documento->Id
						)
				    );
				    if (!move_uploaded_file($_FILES['DocumentoDigital']['tmp_name'], $documento->EnlaceDigital))
					{
						die("Error al subir la imagen al servidor, puede que no tenga permisos para escribir en el directorio.");
					}
				
				} else {
					$sql = "UPDATE DocumentoExistente SET 
						Descripcion          = ?, 
						Tipo_Documento_Id        = ?,
                        Numero        		= ?,
						Fecha_Legalizacion        = ?, 
						Numero_Folios  = ?,
						EstadoOperativo 			= ?, 
						Observaciones 		= ?, 
						Unidad_Id 		= ?,
						Periodo = ?
				    WHERE Id = ?";

					$this->pdo->prepare($sql)
				     ->execute(
					    array(
	                        $documento->Descripcion, 
	                        $documento->Tipo_Documento_Id,
	                        $documento->Numero,
	                        $documento->Fecha_Legalizacion,
	                        $documento->Numero_Folios,
	                        $documento->EstadoOperativo,
	                        $documento->Observaciones,
	                        $documento->Unidad_Id,
	                        $documento->Periodo,
	                        $documento->Id
						)
					);	
				}
			} catch (Exception $e) 
			{
				die($e->getMessage());
			}
		}

		public function Registrar(Documento $documento)
		{
			try 
			{
			$this->pdo->beginTransaction();
			$sql = "INSERT INTO DocumentoExistente (Descripcion,Tipo_Documento_Id,Numero,Fecha_Legalizacion, Numero_Folios, EstadoOperativo, Observaciones, Unidad_Id, Periodo) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

			$this->pdo->prepare($sql)
			     ->execute(
					array(
						$documento->Descripcion, 
                        $documento->Tipo_Documento_Id,
                        $documento->Numero,
                        $documento->Fecha_Legalizacion,
                        $documento->Numero_Folios,
                        $documento->EstadoOperativo,
                        $documento->Observaciones,
                        $documento->Unidad_Id,
                        $documento->Periodo
	                )
				);
			if($_FILES["DocumentoDigital"]["error"] == 4) {
                //means there is no file uploaded
            }
            else{
                $lastId = $this->pdo->lastInsertId();
				$path = $_FILES['DocumentoDigital']['name'];
				$ext = pathinfo($path, PATHINFO_EXTENSION);
				$documentPath = 'documentos/'.$lastId.'.'.$ext;
				$sql = "UPDATE DocumentoExistente SET EnlaceDigital=? WHERE Id=?";
				$this->pdo->prepare($sql)
				     ->execute(
						array(
							$documentPath,
							$lastId
		                )
					);
				if (!move_uploaded_file($_FILES['DocumentoDigital']['tmp_name'], $documentPath))
				{
					die("Error al subir el documento al servidor, puede que no tenga permisos para escribir en el directorio.");
				}
            }
            $this->pdo->commit();

			} catch (Exception $e) 
			{
				die($e->getMessage());
				$this->pdo->rollback();
			}
		}


		public function getTotalRecordsByUnidad($Id)
		{
			try
			{
				$stmt = $this->pdo->prepare("SELECT * FROM DocumentoExistente where Unidad_Id=:unidad_Id");
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
					$stm = $this->pdo->prepare("SELECT * FROM DocumentoExistente");
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
					$stmt = $this->pdo->prepare("SELECT * FROM DocumentoExistente where Unidad_Id=:unidad_Id and Descripcion LIKE :busqueda");
					$stmt->bindparam(":busqueda", $busqueda);
					$stmt->bindValue(":unidad_Id", $Id, PDO::PARAM_INT);
					$stmt->execute();
					return $stmt->rowCount();
				} else {
					$stmt = $this->pdo->prepare("SELECT * FROM DocumentoExistente where Unidad_Id=:unidad_Id");
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
						$stmt = $this->pdo->prepare("SELECT * FROM DocumentoExistente where Descripcion LIKE :busqueda");
						$stmt->bindparam(":busqueda", $busqueda);
						$stmt->execute();
						return $stmt->rowCount();
					} else {
						$stmt = $this->pdo->prepare("SELECT * FROM DocumentoExistente");
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