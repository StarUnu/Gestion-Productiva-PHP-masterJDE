<!--falta agregar la cantidad recuerdas-->
<div class="content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-12">
                <div class="card">
                    <br>
                    <div class="row">
                        <div class="col-md-4">
                            <form method="post" action="<?php echo BASE_URL;?>UnidadMedida/crud" > 
                                <button type="submit" class="btn btn-info btn-fill pull-right" >
                                    PIEZAS DE INVENTARIO
                                </button>
                            </form>
                        </div> 
                <!--
                        <div class="col-md-3">
                            <form method="post" action="<?php echo BASE_URL;?> InventarioFisico/index" > 
                                <button type="submit" class="btn btn-info btn-fill pull-right" >
                                    Generar Reporte
                                </button>
                            </form>
                        </div> 
                    -->
                    </div>
                    <br>
                </div>
                
				<div class="card">
					<div class="content">
						<form method="post" action="<?php echo BASE_URL;?>InventarioBienes/crud">
							<div class="row"> 
								<div class="col-md-3">
									<h4 class="title" class="form-control" >  </h4>
								</div>

								<div class="col-md-4">
                                    <label> Inventarios </label>
									<select class="form-control" name="Tables" id="Tables" onchange="location=this.value;">
										<option value="<?php echo BASE_URL;?>InventarioBienes" <?php if(isset($this->auxTable) && $this->auxTable=="InventarioBienes")echo 'selected';?> > Inventario de Bienes </option>
										<option value="<?php echo BASE_URL;?>InventarioEquipos" <?php if(isset($this->auxTable) && $this->auxTable=="InventarioEquipos")echo 'selected';?> > Inventario de Equipos </option>
										<option value="<?php echo BASE_URL;?>InventarioFisico" <?php if(isset($this->auxTable) && $this->auxTable=="InventarioFisico")echo 'selected';?> > Inventario Fisico </option>
                                    <option value="<?php echo BASE_URL;?>inventariodeseresvivos"<?php if(isset($this->auxTable) && $this->auxTable=="InventarioSeresVivos")echo 'selected';?> > Inventario de Animales </option>
                                    <!--
                                    <option value="<?php echo BASE_URL;?>InventarioCosecha"<?php if(isset($this->auxTable) && $this->auxTable=="InventarioCosecha")echo 'selected';?> >Inventario CosechayOtros </option>-->
									</select>
								</div>

                                <?php if($_SESSION["TipoUsuario"] != 0 ) {?>
                                <div class="col-md-4 ">
                                    <label> Unidaes Productivas </label>
                                    <select  class="form-control" name="Unidades2" id="Unidades2">
                                        <option selected value="-1">----Todas las Unidades Productivas----</option>
                                        <?php foreach($this->model->getUnidades($startFrom) as $r):  ?>
                                            <option value ="<?php echo $r->Id;?>" > <?php echo  $r->Nombre; $unidadescogida = $r->Id;?>
                                            </option>
                                        <?php endforeach ?>
                                    </select>
                                </div>
                                <?php } ?>

                            </div>
                            <div class="row">
                                <div>
                                    <div class="col-md-2"></div>
                                    <div class="col-md-4"> <h4>Inventario de Bienes</h4> </div>
                                </div>
                                
                                    <div class="col-md-2">
                                        <form method="post" action="<?php echo BASE_URL;?>InventarioBienes/Crud" > 
                                            <button type="submit" class="btn btn-info btn-fill pull-right" >
                                                Agregar Inventario
                                            </button>
                                        </form>
                                    </div> 

                            </div>
        
                        <div class="row" style="margin-left: 10%;margin-right: 10%">
                            <div class="col-md-12">
                                <h4>Busqueda:</h4>
                            </div>                            
                        </div>        
        <div class="row" style="margin-left: 10%; margin-right: 10">
        	<div class="col-md-12">
        		<div class="input-group">
        			<input type="text" id="search" class="search-query form-control" placeholder="Como empieza la Descripcion" >
        			<div class="input-group-addon"> <span class="glyphicon glyphicon-search"></span> </div>
        		</div>
        	</div>
        </div>

        <div id ="target-search">
        <div class="content table-responsive table-full-width">
        	<table id=tableBienes class="table table-hover table-striped">
        		<thead>
        			<th>Descripcion </th>
                    <th>Estado de Operatividad</th>
                    <th>Cantidad</th>
                    <th>UnidadProductiva</th>
                    <th>Observaciones</th>
        		</thead>
        		<tbody id="target-content">
        			<?php foreach ($this->model->Listar($startFrom) as $r): ?>
                        <tr>
                        <td><?php echo $r->Descripcion ?> </td>
                        <td><?php echo ($r->EstadoOperativo==0 ? "no operativo" :            ($r->EstadoOperativo==1 ? "equipo con reparaciones" : "operativo")   ) ?></td>
                        <?php 
                        $cantidad=0;
                        if($this->model->getcantidadbyin($r->Id) > 0 )
                            $cantidad=$this->model->getcantidadbyin($r->Id);
                         ?>
                        <td><?php echo $cantidad ; ?></td>                        
                        <td><?php echo $this->model->ObtenerNombreUnidadProductiva($r->Unidad_Id)->Nombre; ?></td>
                        <td><?php echo $r->Observaciones ?></td>
                            <td class=" cell-actions">
                             <div class="btn-group">
                              <a class="btn btn-xs btn-warning buttonCrud"    href="<?php echo BASE_URL;?>InventarioBienes/Crud/<?php echo $r->Id; ?> "><span class="glyphicon glyphicon-pencil"></span></a>
                        <?php if($r->Unidad_Id ==$_SESSION["Unidad_Id"]  ) { ?>                              
                              <a onclick="javascript:return confirm('¿Seguro de eliminar este registro?');" class="btn btn-xs btn-danger buttonCrud" href="<?php echo BASE_URL; ?>InventarioBienes/Eliminar/<?php echo $r->Id; ?>"><span class="glyphicon glyphicon-trash"></span></a>
                             </div>
                            </td>
                        <?php } ?>

                        </tr><!--ya se para que es esto es para las columnas-->
        				<?php endforeach; ?>
        		</tbody>
        	</table>
        </div>
                    <!--PAGINACION-->
            <nav><ul class="pagination">
                <?php if(!empty($totalPages)):for($i=1; $i<=$totalPages; $i++):  
                    if($i == 1):?>
                        <li class='active'  id="<?php echo $i;?>"><a href='<?php echo BASE_URL;?>InventarioBienes/Paginacion/Pagina/<?php echo $i;?>'>  <?php echo $i;?></a></li> 
                    <?php else:?>
                        <li id="<?php echo $i;?>"><a href='<?php echo BASE_URL;?>InventarioBienes/Paginacion/Pagina/<?php echo $i;?>'><?php echo $i;?></a></li>
                    <?php endif;?>          
                <?php endfor;endif;?>
            </ul></nav>

            </div>
            </div>

                                </form>
					</div>
				</div>
			</div>
		</div>
	</div>
<script type="text/javascript">

$('#Unidades2').bind('change', BuscarPorUnidadYTexto);
//trabaja con puros a IDs
function BuscarPorUnidadYTexto()
{
   var base = "<?php echo BASE_URL;?>";
   var txt = $('#search').val();  
   var unidad = $('#Unidades2').val();
   $.ajax({  
      url: base + "InventarioBienes/Buscar/",
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
                    $("#target-content").load(base + "InventarioBienes/Paginacion/Pagina/" + pageNumber + "/Busqueda/" + txt);
                }
            });        
      }
  });
}

$('#search').bind('input',BuscarPorUnidadYTexto);

$(document).ready(function(){//esto hace casi todo 
    $('.pagination').pagination({
            items: <?php echo $totalRecords;?>,
            itemsOnPage: <?php echo resultsPerPage;?>,
            cssStyle: 'light-theme',
            currentPage : 1,
            onPageClick : function(pageNumber) {
                $("#target-content").html('Cargando...');
                var base = "<?php echo BASE_URL;?>";
                $("#target-content").load(base + "InventarioBienes/Paginacion/Page/" + pageNumber);
            }
        });
    });
</script>