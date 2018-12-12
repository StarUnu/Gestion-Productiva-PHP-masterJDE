<?php 
	class InventarioEquipos
	{
		private $pdo;
        public $Descripcion;
        public $Id;
		public $Unidad_Id;
		public $Fecha_Ingreso;
		public $Condicion;
		public $EstadoOperativo;//se haria en cada equipos
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

		public function ListarByUnidad($startFrom,$unidadID)
		{
			$limit = resultsPerPage;//acas lo guarda la variable
			$start = $startFrom;
		    $stmt = $this->pdo->prepare("SELECT * FROM InventarioEquipos Where Unidad_Id=:unidad_Id ORDER BY Fecha_Ingreso ASC LIMIT :startFrom,:resultsPerPage" );

		    $stmt->bindValue(":unidad_Id",$unidadID, PDO::PARAM_INT);
			$stmt->bindValue(":startFrom", (int)$start, PDO::PARAM_INT);//lo pone el valor a la variable
			$stmt->bindValue(":resultsPerPage", (int)$limit, PDO::PARAM_INT);
			$stmt->execute();
			return $stmt->fetchAll(PDO::FETCH_OBJ);	
		}

		public function Listar($startFrom)
		{
			try
			{
				if($_SESSION["TipoUsuario"] ==1)
				{
					$limit = resultsPerPage;//acas lo guarda la variable
					$start = $startFrom;
				    $stmt = $this->pdo->prepare("SELECT * FROM InventarioEquipos ORDER BY Fecha_Ingreso ASC LIMIT :startFrom,:resultsPerPage" );
					$stmt->bindValue(":startFrom", (int)$start, PDO::PARAM_INT);//lo pone el valor a la variable
					$stmt->bindValue(":resultsPerPage", (int)$limit, PDO::PARAM_INT);
					$stmt->execute();
					return $stmt->fetchAll(PDO::FETCH_OBJ);
				}
				else
				{
					$unidadID = intval($_SESSION['Unidad_Id']);
					return $this->ListarByUnidad($startFrom,$unidadID);
				}
			}
			catch(Exception $e)
			{
				die($e->getMessage());
			}
		}

		public function Actualizar($Inventarioequipos)
		{
			try 
			{
				$sql = "UPDATE InventarioEquipos SET 
						Unidad_Id  		    =  ?,
	                    Fecha_Ingreso   	=  ?,
						Condicion           =  ?, 
						EstadoOperativo     =  ?,
						Descripcion			=  ?
				    WHERE Id = ?";
				    
				$this->pdo->prepare($sql)
			     ->execute(
				    array(		
	                    $Inventarioequipos->Unidad_Id,
                        $Inventarioequipos->Fecha_Ingreso,
                        $Inventarioequipos->Condicion,
                        $Inventarioequipos->EstadoOperativo,
                        $Inventarioequipos->Descripcion,
                        $Inventarioequipos->Id//aquì siempres tiene que estar el id 
                        //es en donde lo va buscar
					    )
					 );	
				}
			catch (Exception $e) 
			{
				die($e->getMessage());
			}
		}

		public function Registrar(InventarioEquipos $InventarioEquipos)
			{
				try 
				{
				$sql = "INSERT INTO InventarioEquipos (Id,Unidad_Id,Fecha_Ingreso,Condicion,EstadoOperativo,Descripcion) 
				        VALUES (?,?,?,?,?,?)";

				$this->pdo->prepare($sql)
				     ->execute(
						array(
							$InventarioEquipos->Id,
							$InventarioEquipos->Unidad_Id,
							$InventarioEquipos->Fecha_Ingreso,
							$InventarioEquipos->Condicion,
							$InventarioEquipos->EstadoOperativo,
							$InventarioEquipos->Descripcion
		                )
					);
				     return $this->pdo->lastInsertId();
				} catch (Exception $e) 
				{
					die($e->getMessage());
				}
			}

			public function getTotalRecordsBusquedaByUnidad($Id, $busqueda)
			{
				if ($busqueda!='')
				{
					$busqueda = '%'.$busqueda.'%';
					//el '%',hay cualquier cosa a la izquierda y hay otra cosa a la derecha
					$stmt = $this->pdo->prepare("SELECT * FROM InventarioEquipos where Unidad_Id=:unidad_Id and Descripcion LIKE :busqueda ");
					$stmt->bindparam(":busqueda", $busqueda);
					$stmt->bindValue(":unidad_Id", $Id, PDO::PARAM_INT);
					$stmt->execute();
					return $stmt->rowCount();
				} else {
					$stmt = $this->pdo->prepare("SELECT * FROM InventarioEquipos where Unidad_Id=:unidad_Id");
					$stmt->bindValue(":unidad_Id", $Id, PDO::PARAM_INT);
					$stmt->execute();
					return $stmt->rowCount();
				}
			}
        	
        	public function getTotalRecordsBusqueda($busqueda){
        		try {
				if ( $_SESSION['TipoUsuario'] == 0)//cuando no hay select
				{
					$unidadID = intval($_SESSION['Unidad_Id']);//estosaca su id de la unidad en int
					return $this->getTotalRecordsBusquedaByUnidad($unidadID, $busqueda);
				}
				else
				{

					if ($busqueda!='')
					{
						$busqueda = '%'.$busqueda.'%';
						$stmt = $this->pdo->prepare("SELECT * FROM InventarioEquipos where Descripcion LIKE :busqueda ");
						$stmt->bindparam(":busqueda", $busqueda);
						$stmt->execute();
						return $stmt->rowCount();
					} else {
						$stmt = $this->pdo->prepare("SELECT * FROM InventarioEquipos");
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
					$stmt = $this->pdo->prepare("SELECT * FROM InventarioEquipos where Unidad_Id=:unidad_Id and (Descripcion LIKE :busqueda) ORDER BY Id ASC LIMIT :startFrom,:resultsPerPage");
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
						$stmt = $this->pdo->prepare("SELECT * FROM InventarioEquipos where Descripcion LIKE :busqueda ORDER BY Id ASC LIMIT :startFrom,:resultsPerPage");
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
            try {
                $stmt=$this->pdo->prepare("SELECT * FROM InventarioEquipos Where Id = ?");
                $stmt->execute( array($Id) );
                return $stmt->fetch(PDO::FETCH_OBJ);
            } catch (Exception $e) {
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
	
		public function getTotalRecords(){//esto es para la paginacion
			try {
				$stm = $this->pdo->prepare("SELECT * FROM  InventarioEquipos");
				$stm->execute();
				return $stm->rowCount();
				
			} catch (Exception $e) {
				die($e->getMessage());
			}
		}
        
        public function getTotalRecordsporUnnidad($Id){//esto es para la paginacion
			try {
				$stm = $this->pdo->prepare("SELECT * FROM  InventarioEquipos Where 
					Unidad_Id =?");
				$stm->execute(array($Id));
				return $stm->rowCount();
				
			} catch (Exception $e) {
				die($e->getMessage());
			}
		}

		public function getInventarioId($Id)
		{
			try {
				/*$stm = $this->pdo->prepare("SELECT * FROM  InventarioEquipos WHERE Unidad_Id = ?");*/
				$stm = $this->pdo->prepare("SELECT * FROM InventarioEquipos INNER JOIN Equipos WHERE Equipos.InventarioEquipo_Id =InventarioEquipos.Id
					");
				$stm->execute( array($Id) );

				return $stm->fetchAll(PDO::FETCH_OBJ);
				
			} catch (Exception $e) {
				die($e->getMessage());
			}
		}

        public function Eliminar($Id)
		{
			//Faltar eliminar recursivamente las fks
			try 
			{
				$stm = $this->pdo
				            ->prepare("DELETE FROM InventarioEquipos WHERE Id = ?");			          
				$stm->execute(array($Id));

			} catch (Exception $e)
			{
				die($e->getMessage());
			}
		}

		public function getnombreUnidadId($Id)
		{
			try 
			{
				$stm = $this->pdo
				            ->prepare("SELECT * FROM UnidadesProductivas WHERE Id = ?");			          
				$stm->execute(array($Id));
				return $stm ->fetch(PDO::FETCH_OBJ);
			} catch (Exception $e)
			{
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

	
		
	}//llave de la clase
?>