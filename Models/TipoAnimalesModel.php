<?php 

	class TipoAnimal
	{
		private $pdo;
		public $Id;
		public $Descripcion;
		
		
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

		public function Listar($startFrom)
		{	
			try
			{
				$limit = resultsPerPage;
				$start = $startFrom;
				$stmt = $this->pdo->prepare("SELECT * FROM tiporaza_animal ORDER BY Descripcion ASC LIMIT :startFrom,:resultsPerPage ");
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

		public function getTotalRecords(){
			try {
				$stm = $this->pdo->prepare("SELECT * FROM tiporaza_animal");

				$stm->execute();
				return $stm->rowCount();
			} catch (Exception $e) {

				die($e->getMessage());
			}
		}


		public function Obtener($Id)
		{
			try 
			{
				$stm = $this->pdo
				          ->prepare("SELECT * FROM tiporaza_animal WHERE Id = ?");
				$stm->execute(array($Id));
				return $stm->fetch(PDO::FETCH_OBJ);
			} catch (Exception $e) 
			{
				die($e->getMessage());
			}
		}


		public function Actualizar($Unidadmedida)
		{
			try 
			{
					$sql = "UPDATE tiporaza_animal SET 
							Descripcion              =  ?
					    WHERE Id = ?";

					$this->pdo->prepare($sql)
				     ->execute(
					    array(
	                        $Unidadmedida->Descripcion,
	                        $Unidadmedida->Id
						)
					 );	
			} catch (Exception $e) 
			{
				die($e->getMessage());
			}
		}

		public function Registrar(TipoAnimal $Unidadmedida)
		{
			try 
			{
				$sql = "INSERT INTO tiporaza_animal (Id,Descripcion) 
				        VALUES (?, ?)";

				$this->pdo->prepare($sql)
				     ->execute(
						array(
							$Unidadmedida->Id, 
							$Unidadmedida->Descripcion
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
				            ->prepare("DELETE FROM tiporaza_animal WHERE Id = ?");			          
				$stm->execute(array($Id));
			} catch (Exception $e) 
			{
				die($e->getMessage())	;
			}
		}
		
		public function getdescribyidanimal($Id)
		{
			try
			{
				$stm = $this->pdo->prepare("SELECT * FROM tiporaza_animal WHERE Id = ? ");
				$stm->execute( array($Id) );
	
				return $stm->fetch(PDO::FETCH_OBJ);//tiene que obtener con todas las coincidencias
			}
			catch(Exception $e)
			{
				die($e->getMessage());
			}	
		}

	

	}


?>