<?php 
	class InventarioFisico
	{
		private $pdo;
        
        public $Id;
		public $TipoExistencia_Id;
		public $UnidadMedida_Id;
		public $Descripcion_Existencia;
		public $Codigo_Existencia;//esto ya no deberia de dentrar porque ya tiene 
		//la descripvin de existencia
		public $Unidad_Id;
        public $incremento;
        public $Observaciones;
        public $Material_Insumo_Id;
        public $FechaIngreso;
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
				$stmt = $this->pdo->prepare("SELECT * FROM InventarioFisico where Unidad_Id=:unidad_Id ORDER BY Descripcion_Existencia ASC LIMIT :startFrom,:resultsPerPage");
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
			try
			{
				$limit = resultsPerPage;//acas lo guarda la variable
				$start = $startFrom;

				if ($_SESSION["TipoUsuario"] ==  0)//cuando es 0 es unidad productiva
				{
					$unidadID = intval($_SESSION['Unidad_Id']);
					return $this->ListarByUnidad($startFrom,$unidadID);
				}
				else{
					//usuario numero 1 es admin
					$stmt = $this->pdo->prepare("SELECT * FROM  InventarioFisico  ORDER BY Descripcion_Existencia ASC LIMIT :startFrom,:resultsPerPage" );
					//$stmt->bindValue(":unidad_id", $unidad_id1, PDO::PARAM_INT);//lo 
					$stmt->bindValue(":startFrom", (int)$start, PDO::PARAM_INT);//lo pone el valor a la variable

					$stmt->bindValue(":resultsPerPage", (int)$limit, PDO::PARAM_INT);
					$stmt->execute();
				}
	
				return $stmt->fetchAll(PDO::FETCH_OBJ);
			}
			catch(Exception $e)
			{
				die($e->getMessage());
			}
		}

		public function Actualizar(InventarioFisico $Inventariofisico)
		{
			try 
			{
				echo "actualiza el inventario fisioco";
				$sql = "UPDATE inventariofisico SET 
						TipoExistencia_Id  =  ?, 
	    				UnidadMedida_Id     =  ?,
	    				Unidad_Id 			= ?,
						Descripcion_Existencia  = ?, 
						Codigo_Existencia = ?,
						FechaIngreso = ?,
						Observaciones = ?,
						Material_Insumo_Id =?
				    	WHERE Id = ?";
				$this->pdo->prepare($sql)->execute(
				    array(
	                    $Inventariofisico->TipoExistencia_Id,
                        $Inventariofisico->UnidadMedida_Id,
                        $Inventariofisico->Unidad_Id,
                        $Inventariofisico->Descripcion_Existencia,
                        $Inventariofisico->Codigo_Existencia,
                        $Inventariofisico->FechaIngreso,
                        $Inventariofisico->Observaciones,
                        $Inventariofisico->Material_Insumo_Id,
                        $Inventariofisico->Id
					    )
					 );
			}
			catch (Exception $e) 
			{
				die($e->getMessage());
			}
		}

		public function Registrar(InventarioFisico $InventarioFisico)
			{

				try 
				{
//					$this->pdo->beginTransaction();	//prque se pone esto aqui??

				$sql = "INSERT INTO InventarioFisico (Id,TipoExistencia_Id,UnidadMedida_Id,Unidad_Id,Descripcion_Existencia,Codigo_Existencia,FechaIngreso,Observaciones,Material_Insumo_Id) 
				        VALUES (?,?,?,?,?,?,?,?,?)";

				$this->pdo->prepare($sql)
				     ->execute(
						array(
							$InventarioFisico->Id,
							$InventarioFisico->TipoExistencia_Id,
							$InventarioFisico->UnidadMedida_Id,
							$InventarioFisico->Unidad_Id,
							$InventarioFisico->Descripcion_Existencia,
							$InventarioFisico->Codigo_Existencia,
							$InventarioFisico->FechaIngreso,
							$InventarioFisico->Observaciones,
							$InventarioFisico->Material_Insumo_Id
		                )
					);
				    return $this->pdo->lastInsertId();
				} catch (Exception $e) 
				{
					die($e->getMessage());
				}

			}
        
        public function Obtener($Id)
        {
            try {
                $stmt=$this->pdo->prepare("SELECT * FROM InventarioFisico Where Id = ?");
                $stmt->execute( array($Id) );
                return $stmt->fetch(PDO::FETCH_OBJ);
            } catch (Exception $e) {
                die($e->getMessage());
            }
        }

        public function getUnidadMedida()
        {
        	try
        	{
        		$stmt = $this->pdo->prepare("SELECT * FROM UnidadMedida  ORDER BY Id ");
				$stmt->execute();
				return $stmt->fetchAll(PDO::FETCH_OBJ);
        	}
        	catch(Exception $e){
        		die($e->getMessage());	
        	}
        }

        public function getTipos_inventarios()
        {
        	try
        	{
        		$stmt = $this->pdo->prepare("SELECT * FROM TipoExistencia ORDER BY Id");
				$stmt->execute();
				return $stmt->fetchAll(PDO::FETCH_OBJ);
        	}
        	catch(Exception $e){
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

        public function getUnidades(){
			try
			{
				$stmt = $this->pdo->prepare("SELECT * FROM UnidadesProductivas ORDER BY Nombre");
				$stmt->execute();
				return $stmt->fetchAll(PDO::FETCH_OBJ);

			}
			catch(Exception $e)
			{
				die($e->getMessage());
			}
		}


		public function getCantidadDetalle($Id){
			try 
			{
				$stm = $this->pdo
				            ->prepare("SELECT sum(Cantidad) FROM InventarioFisico_Detalle WHERE InventarioFisico_Id = ?  ");			          
				$stm->execute(array($Id));
				//$row = mysql_fetch_array($stm)
				$row = $stm->fetch(PDO::FETCH_NUM);
				if($row[0]>0)
					return $row[0];//aca saca la columna
				else
					return 0;
			}
			catch (Exception $e) 
			{
				die($e->getMessage());
			}
		}


		public function getTotalRecords(){//esto es para la paginacion
			try {
				$stm = $this->pdo->prepare("SELECT * FROM  InventarioFisico");
				$stm->execute();
				return $stm->rowCount();
				
			} catch (Exception $e) {
				die($e->getMessage());
			}
		}

        public function getTotalRecordsporUnnidad($Id){
			try {
				//ya entiendo para el codigo no sea enredoso ,jair lo pone aquì las restricciones
				$stm = $this->pdo->prepare("SELECT * FROM  InventarioFisico Where 
					Unidad_Id =?");
				$stm->execute(array($Id));
				return $stm->rowCount();
				
			} catch (Exception $e) {
				die($e->getMessage());
			}
		}

		public function getTotalRecordsBusquedaByUnidad($Id, $busqueda)
		{
			if ($busqueda!='')
			{
				$busqueda = '%'.$busqueda.'%';
				$stmt = $this->pdo->prepare("SELECT * FROM InventarioFisico where Unidad_Id=:unidad_Id and Descripcion_Existencia LIKE :busqueda ");
				$stmt->bindparam(":busqueda", $busqueda);
				$stmt->bindValue(":unidad_Id", $Id, PDO::PARAM_INT);
				$stmt->execute();
				return $stmt->rowCount();
			} else {
				$stmt = $this->pdo->prepare("SELECT * FROM InventarioFisico where Unidad_Id=:unidad_Id");
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
					$stmt = $this->pdo->prepare("SELECT * FROM InventarioFisico where Descripcion_Existencia LIKE :busqueda ");
					$stmt->bindparam(":busqueda", $busqueda);
					$stmt->execute();
					return $stmt->rowCount();
				} else {

					$stmt = $this->pdo->prepare("SELECT * FROM InventarioFisico");
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
			$stmt = $this->pdo->prepare("SELECT * FROM InventarioFisico where Unidad_Id=:unidad_Id and (Descripcion_Existencia LIKE :busqueda) ORDER BY Id ASC LIMIT :startFrom,:resultsPerPage");
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
				$stmt = $this->pdo->prepare("SELECT * FROM InventarioFisico where Descripcion_Existencia LIKE :busqueda ORDER BY Id ASC LIMIT :startFrom,:resultsPerPage");
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


        public function Eliminar($Id)
		{
			//Faltar eliminar recursivamente las fks
			try 
			{
				$stm = $this->pdo
				            ->prepare("DELETE FROM InventarioFisico WHERE Id = ?");			          
				$stm->execute(array($Id));

				//$this->pdo->commit();//averiguar que esto 

			} catch (Exception $e)
			{
				die($e->getMessage());
			}
		}

		public function getporIddetalle($Id)///esto no lo puseeeeeeeeeeeeeeeeeeee
		{
			try 
			{
				$stm = $this->pdo
				            ->prepare("DELETE FROM InventarioFisico_Detalle WHERE InventarioFisico_Id = ?");			          
				$stm->execute(array($Id));

				
				return $stmt->fetchAll(PDO::FETCH_OBJ);

			} catch (Exception $e)
			{
				
				die($e->getMessage());
			}

		}
        public function getInventarioFisico_DetalleById($Id){
			try 
			{
				$stm = $this->pdo
				            ->prepare("SELECT * FROM InventarioFisico_Detalle WHERE Id = ?");			        
				$stm->execute(array($Id));
				$row = $stm->fetch(PDO::FETCH_ASSOC);
				return $row['Cantidad'];//como saco dos atributos ?? para sacar tambien el estado
			}
			catch (Exception $e) 
			{
				die($e->getMessage());
			}
		}

        public function getInventarioFisico_DetalleById2($Id){
			try 
			{
				$stm = $this->pdo
				            ->prepare("SELECT * FROM InventarioFisico_Detalle WHERE Id = ?");			        
				$stm->execute(array($Id));
				$row = $stm->fetch(PDO::FETCH_ASSOC);
				return $row['Id'];//como saco dos atributos ?? para sacar tambien el estado
			}
			catch (Exception $e) 
			{
				die($e->getMessage());
			}
		}

		public function getUnidadMedidaById($Id){
			try 
			{
				$stm = $this->pdo
				            ->prepare("SELECT * FROM UnidadMedida WHERE Id = ?");			          

				$stm->execute(array($Id));
				$row = $stm->fetch(PDO::FETCH_ASSOC);
				return $row['Nombre'];
			}
			catch (Exception $e) 
			{
				die($e->getMessage());
			}
		}

		public function getDetalle_Cantidad($Id)
		{
				try 
			    {
					$stm = $this->pdo
					            ->prepare("SELECT * FROM InventarioFisico_Detalle WHERE InventarioFisico_Id = ?");		          

					$stm->execute(array($Id));
					 //$stm->fetch(PDO::FETCH_ASSOC);
					return $stm->fetch(PDO::FETCH_ASSOC);;
				}
				catch (Exception $e) 
				{
					die($e->getMessage());
				}
		}
		public function getMaterial_InsumoById($Id){
				try 
			    {
					$stm = $this->pdo
					            ->prepare("SELECT * FROM Material_Insumo WHERE Id = ?");		          

					$stm->execute(array($Id));
					$row = $stm->fetch(PDO::FETCH_ASSOC);
					return $row['Marca'];
				}
				catch (Exception $e) 
				{
					die($e->getMessage());
				}
			}

		///////////////////haciendo para el checkbox 

		public function getidofnombredemarca($id)
		{
			try 
			{
				$stm = $this->pdo
				            ->prepare("SELECT * FROM Material_Insumo WHERE Marca = ?");			          
				$stm->execute(array($Id) );
				return $stm ->fetch(PDO::FETCH_OBJ);
			} catch (Exception $e)
			{
				die($e->getMessage());
			}	
		}

		public function getAllbyunidad($Id)
		{
			try 
			{
				$stm = $this->pdo
				            ->prepare("SELECT * FROM InventarioFisico Where Unidad_Id = ?");			          
				$stm->execute(array($Id));
				echo "llega por acasas";
				return $stm->fetchAll(PDO::FETCH_OBJ);
			} catch (Exception $e)
			{
				die($e->getMessage());
			}	
		}	

	}




?>


