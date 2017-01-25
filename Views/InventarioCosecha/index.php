<div class="content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-12">
			<?php if($_SESSION['TipoUsuario'] !=0 ) { ?>
			<div class="card">
			<br>
				<div class="row">
					<div class="col-md-7">
						<form method="post" action="<?php echo BASE_URL;?>cc/crud" > 
							<button type="submit" class="btn btn-info btn-fill pull-right" >
								PIEZAS DE INVENTARIO
							</button>
						</form>
					</div> 
				</div>
			<br>
			</div>
			<?php } ?>

				<div class="card">
					<div class="content">
						<form method="post" action="<?php echo BASE_URL;?>InventarioCosecha/crud">
							<div class="row">

								<div class="col-md-4">
									<h4 class="title"> </h4>    
								</div>
							
								<div class="col-md-4">
									<label> Inventarios </label>
									<select class="form-control" name="Tablas" id="Tablas" onchange="location=this.value;">
										<option value="<?php echo BASE_URL;?>InventarioFisico" <?php if (isset($this->auxTable) && $this->auxTable=="InventarioFisico") echo 'selected'; ?>>Inventario Fisico</option>

										<option value="<?php echo BASE_URL;?>InventarioEquipos" <?php if (isset($this->auxTable) && $this->auxTable=="InventarioEquipo") echo 'selected'; ?> >Inventario de Equipos </option>

										<option value="<?php echo BASE_URL;?>InventarioBienes" <?php if(isset($this->auxTable) && $this->auxTable=="InventarioBienes")echo 'selected';?> > Inventario de Bienes </option>
										<option value="<?php echo BASE_URL;?>inventariodeseresvivos" <?php if(isset($this->auxTable) && $this->auxTable=="InventarioSeresVivos")echo 'selected';?> > Inventario de Animales </option>

	                                    <option value="<?php echo BASE_URL;?>InventarioCosecha" <?php if(isset($this->auxTable) && $this->auxTable=="InventarioCosecha")echo 'selected';?> > Inventario Cosecha y Otros </option>
									</select>             
								</div>
								<?php $unidadescogida="" ?>

	                            <?php if($_SESSION["TipoUsuario"] != 0 ) {?>
	                            <div class="col-md-4">
	                                <label> Unidad Productivas </label>
	                                <select  class="form-control" name="Unidades" id="Unidades1">
	                                    <option selected value="-1">----Todos----</option>
	                                    <?php foreach($this->model->getUnidades($startFrom) as $r):  ?>
	                                        <option value ="<?php echo $r->Id;?>" > <?php echo  $r->Nombre; $unidadescogida = $r->Id;?>
	                                        </option>
	                                    <?php endforeach ?>
	                                </select>
	                            </div>
	                            <?php } ?>
							</div>

							<div class = "row">
								<div>
								<div class="col-md-2"></div>
								<div class="col-md-4">
									<h4 class="title">Inventario de Cosecha</h4>    
								</div>

								<div class="col-md-4">
										<button type="submit" class="btn btn-info btn-fill pull-right">Agregar Inventario</button>            </div>
							</div>
						</form>
							</div>							
				</div>

				<div class="row" style="margin-left: 10%;margin-right: 10%">
					<div class="col-md-12">
						<h4>Busqueda:</h4>
					</div>                            
				</div>

				<div class="row" style="margin-left: 10%;margin-right: 10%">
					<div class="col-md-12">
						<div class="form-group">
							<div class="input-group">
								<input type="text" id="search" class="search-query form-control" placeholder="Ingrese lo que desee Buscar" />
							  <div class="input-group-addon"><span class="glyphicon glyphicon-search"></span></div>
							</div>
						</div>
					</div>    
				</div>
				<div class=" target-search">
				<div class="content table-responsive table-full-width">
					<table id="tableUnidades" class="table table-hover table-striped">
								<thead>
									<th>Fecha de Ingreso </th>
									<th>Condicion</th>
									<th>Estado de Operatividad</th>		
									<?php  
									if(!isset($_SESSION['Unidad_Id']))
									{?>
										<!--<th>Observaciones</th>-->
										<th> Observaciones</th>
									<?php } 
									else
									{?>
											<th>Observaciones</th>
									  <?php  }?>
								</thead>
								<tbody id="target-content">
									<?php foreach($this->model->Listar($startFrom) as $r ) : ?>
										<tr>
										<td><?php echo $r->Fechaingreso; ?> </td>
										<td><?php echo $r->Descripcion; ?> </td>
										<td><?php echo $r->Observaciones; ?> </td>
										<!--los %20son los espacios-->
										<td class=" cell-actions">
											 <div class="btn-group">
												<?php if($r->Unidad_Id ==$_SESSION["Unidad_Id"]) { ?>
												<a class="btn btn-xs btn-warning buttonCrud"    href="<?php echo BASE_URL;?>InventarioEquipos/Crud/<?php echo $r->Id; ?> "><span class="glyphicon glyphicon-pencil"></span></a>
												<a onclick="javascript:return confirm('¿Seguro de eliminar este registro?');" class="btn btn-xs btn-danger buttonCrud" href="<?php echo BASE_URL; ?>InventarioEquipos/Eliminar/<?php echo $r->Id; ?>"><span class="glyphicon glyphicon-trash"></span></a>
												<?php } ?>
											</div>
										</td>
										</tr>
									<?php endforeach; ?>
								</tbody>
							</table>
				</div>
				</div>                   <!--PAGINACION-->
                        <nav><ul class="pagination">
                            <?php if(!empty($totalPages)):for($i=1; $i<=$totalPages; $i++):  
                                    if($i == 1):?>
                                        <li class='active'  id="<?php echo $i;?>"><a href='<?php echo BASE_URL;?>InventarioCosecha/Paginacion/Pagina/<?php echo $i;?>'><?php echo $i;?></a></li> <!--esto es para el url-->
                                    <?php else:?>       
                                        <li id="<?php echo $i;?>"><a href='<?php echo BASE_URL;?>InventarioCosecha/Paginacion/Page/<?php echo $i;?>'><?php echo $i;?></a></li>
                                    <?php endif;?> 

                            <?php endfor;endif;?>
                        </ul></nav>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
$('#Unidades1').bind('change', BuscarPorUnidadYTexto);
//trabaja con puros a IDs
function BuscarPorUnidadYTexto()
{
   var base = "<?php echo BASE_URL;?>";
   var txt = $('#search').val();  
   var unidad = $('#Unidades1').val();
   $.ajax({  
      url: base + "InventarioCosecha1212/Buscar/",
      method:"post",  
      data:{search:txt, unidad:unidad},// en el proceso de data :{} lo convierte a string 
      dataType:"text",  
      success:function(data)
      {     $('#target-search').html(data);//esto lo va ha suplantaras
            $('.pagination').pagination({
                items: $('#totalRecords').val(),
                itemsOnPage: <?php echo resultsPerPage;?>,
                cssStyle: 'light-theme',
                currentPage : 1,
                onPageClick : function(pageNumber) {
                    $("#target-content").html('Cargando...');
                    var base = "<?php echo BASE_URL;?>";
                    $("#target-content").load(base + "InventarioCosecha/Paginacion/Pagina/" + pageNumber + "/Busqueda/" + txt);
                }
            });        
      }
  });
}

$('#search').bind('input',BuscarPorUnidadYTexto);

$(document).ready(function(){
$('.pagination').pagination({
        items: <?php echo $totalRecords;?>,
        itemsOnPage: <?php echo resultsPerPage;?>,
        cssStyle: 'light-theme',
        currentPage : 1,
        onPageClick : function(pageNumber) {
            $("#target-content").html('Cargando...');
            var base = "<?php echo BASE_URL;?>";
            $("#target-content").load(base + "InventarioCosecha/Paginacion/Pagina/" + pageNumber);
        }
    });
});
</script>