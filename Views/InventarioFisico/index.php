<!--se tiene que cambiar el orden yo lo cambie al ultimo momenot unidades productivas era el 2do y ahora es el ultimo CUIDADñ-->
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
                <?php $numero=0;?>
                <div class="card">                    
                    <div class="content">
                    
                        <div class="row">

                            <div class="col-md-4">
                                <h4 class="title"> </h4>    
                            </div>
                        
                            <div class="col-md-4">
                                <label> Inventarios</label>
                                <select class="form-control" name="Tablas" id="inventarios" onchange="location=this.value;">

                                    <option value="<?php echo BASE_URL;?>InventarioBienes" <?php if(isset($this->auxTable) && $this->auxTable=="InventarioBienes")echo 'selected';?> > Inventario de Bienes </option>

                                    <option value="<?php echo BASE_URL;?>InventarioEquipos" <?php if (isset($this->auxTable) && $this->auxTable=="InventarioEquipo") echo 'selected'; ?> >Inventario de Equipos </option>

                                    <!--la locaidad en donde se encuentra-->
                                    <option value="<?php echo BASE_URL;?>InventarioFisico" <?php if (isset($this->auxTable) && $this->auxTable=="InventarioFisico") echo 'selected'; ?>>Inventario Fisico</option>

                                    <option value="<?php echo BASE_URL;?>inventariodeseresvivos" <?php if(isset($this->auxTable) && $this->auxTable=="InventarioSeresVivos")echo 'selected';?> > Inventario de Animales </option>


                                </select>             
                            </div>
                            <!--$unidadescogida-->
                            <?php $unidadescogida="" ?>

                            <?php if($_SESSION["TipoUsuario"] != 0 ) {?>
                            <div class="col-md-4">
                                <label> Unidad Productivas </label>
                                <select  class="form-control" name="Unidades" id="Unidades">
                                    <option selected value="-1">----Todos----</option>
                                    <?php foreach($this->model->getUnidades($startFrom) as $r):  ?>
                                        <option value ="<?php echo $r->Id;?>" > <?php echo  $r->Nombre; $unidadescogida = $r->Id;?>
                                        </option>
                                    <?php endforeach ?>
                                </select>
                            </div>
                            <?php } ?>

                        </div>

                        <div>
                            <div class="col-md-2"></div>
                            <div class="col-md-4">
                                <h4 class="title">Inventario Fisico</h4>    
                            </div>
                            <br>
                            <div class="col-md-2 ">
                                <form method="post" action="<?php echo BASE_URL;?>InventarioFisico/Crud" > 
                                    <button type="submit" class="btn btn-info btn-fill pull-right" >
                                        Agregar Inventario
                                    </button>
                                </form>
                            </div> 

                            <div class="col-md-2"> <!--nose como comentarlo-->
                            <!--tengo que hacer la busquedad-->
                                                                
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
                                            <input type="text" id="search" class="search-query form-control" placeholder="Como empieza la Descripcion" />
                                          <div class="input-group-addon"><span class="glyphicon glyphicon-search"></span></div>
                                        </div>
                                </div>
                            </div>    
                        </div>

                        <div id ="target-search">
                        <div class="content table-responsive table-full-width">
                            <table id="tableUnidades" class="table table-hover table-striped">
                                <thead>
                                    <th>Descripcion </th>
                                    <th>Año de Ingreso</th>
                                    <th>Cantidad</th>
                                    <th>Unidad Productiva</th>
                                </thead>

                                <tbody id="target-content">
                                    <?php foreach($this->model->Listar($startFrom) as $r): ?>

                                        <tr>
                                            <td><?php echo $r->Descripcion_Existencia; ?></td>
                                            <td><?php echo $r->FechaIngreso; ?></td>
                                            <td><?php echo ($this->model->getCantidadDetalle($r->Id)=='' ? "0" : $this->model->getCantidadDetalle($r->Id)); ?> </td>
                                            <?php $unidadpro =$this->model->getunidadByid($r->Unidad_Id)  ?>

                                            <td><?php if($unidadpro!=null) echo $unidadpro->Nombre;?> </td>
                                            <!--los %20son los espacios-->
                                            <td class=" cell-actions"><!--</td> class="cell-actions">-->
                                                <div class="btn-group">
                                                    <a class="btn btn-xs btn-warning buttonCrud"    href="<?php echo BASE_URL;?>InventarioFisico/Crud/<?php echo $r->Id; ?> "><span class="glyphicon glyphicon-pencil"></span></a>
                                                    <!--e span de glyphicon-->
                                                <?php if($r->Unidad_Id ==$_SESSION["Unidad_Id"]  ) { ?>                                           
                                                    <a onclick="javascript:return confirm('¿Seguro de eliminar este registro?');" class="btn btn-xs btn-danger buttonCrud" href="<?php echo BASE_URL; ?>InventarioFisico/Eliminar/<?php echo $r->Id; ?>"><span class="glyphicon glyphicon-trash"></span></a>
                                                <?php }?>
                                                </div>

                                            </td>
                                        </tr>
                                        
                                    <?php endforeach; ?>

                                </tbody>
                            </table>
                        </div>
                        
                        <!--PAGINACION-->
                        <nav><ul class="pagination">
                            <?php if(!empty($totalPages)):for($i=1; $i<=$totalPages; $i++):  
                                    if($i == 1):?>
                                        <li class='active'  id="<?php echo $i;?>"><a href='<?php echo BASE_URL;?>InventarioFisico/Paginacion/Pagina/<?php echo $i;?>'><?php echo $i;?></a></li> <!--esto es para el url-->
                                    <?php else:?>       
                                        <li id="<?php echo $i;?>"><a href='<?php echo BASE_URL;?>InventarioFisico/Paginacion/Page/<?php echo $i;?>'><?php echo $i;?></a></li>
                                    <?php endif;?>          
                            <?php endfor;endif;?>
                        </ul></nav>

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">

$('#Unidades').bind('change', BuscarPorUnidadYTexto);

function BuscarPorUnidadYTexto()
{
   var base = "<?php echo BASE_URL;?>";
   var txt = $('#search').val();  
   var unidad = $('#Unidades').val();
   $.ajax({  
      url: base + "InventarioFisico/Buscar/",
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
                    $("#target-content").load(base + "InventarioFisico/Paginacion/Pagina/" + pageNumber + "/Busqueda/" + txt);
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
                $("#target-content").load(base + "InventarioFisico/Paginacion/Pagina/" + pageNumber);
            }
        });
    });

</script>
