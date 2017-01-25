<script type="text/javascript" src="<?php echo BASE_URL;?>Assets/js/jspdf.min.js"></script>
<!--tiene que empezar autoincrementarse desde1-->
<script type="text/javascript">

var cantidadAcumulada = 0;
var editandoDetalle =false;
var rowEdit=null;
var tdId_insumo22='';
var tdCantidad=0;

var verificarRequeridos = function(){
        if ($('#cantidad').val()=='' || $('#Estado').val()=='' ){
            return false;
        }
        return true;
    }

var actualizarCantidadTotal = function()
{
    alert("Actualizar:" + cantidadAcumulada);
    //Actualizar el monto total
    $('[name=cantidadtotal]').val(cantidadAcumulada.toFixed(2));
}

var saveEditDetalle = function(parent){
        var cantidad = $('#cantidad').val();
        var estado = $('#Estado').val();
        var cantidadAnterior = parent.children("td:nth-child(1)").html();
        //var pruebaobserva = observaciones;
        var idanimales = $('#IdSeresVivos').val();
        var contestado=$("#Estado option:selected").html();
        parent.children("td:nth-child(1)").html(cantidad);
        parent.children("td:nth-child(2)").html(contestado);

        parent.children("input:nth-child(4)").val(idanimales);
        parent.children("input:nth-child(5)").val(cantidad);
        parent.children("input:nth-child(6)").val(estado);
        cleanDetalle();
        cantidadAcumulada -= parseFloat(cantidadAnterior);//lee todo y solo regresa el primer numero
        cantidadAcumulada += parseFloat(cantidad);
        actualizarCantidadTotal();
        editDetalle=false;
}

var editDetalle =  function(){
    editandoDetalle = true;
    var parent = $(this).closest("tr");
    rowEdit = parent;
    var tdcantidad = parent.children("td:nth-child(1)").html();
    var tdestado = parent.children("td:nth-child(2)").html();
    //el html cambia toodos los elementos por otro seleccionado o saca valor
    var valorint = parseFloat(tdestado);
    $('#Estado').val(valorint);
     $('#cantidad').val(parseFloat(tdcantidad)) ;
     alert("SSSP");
}


var deleteDetalle = function(){
        //alert("Quitando:" + tdCantidad);
        var parent = $(this).closest("tr");
        var tdCantidad = parent.children("td:nth-child(1)").html();
        alert("Quitando:" + tdCantidad);
        cantidadAcumulada -= parseFloat(tdCantidad);
        editandoDetalle=false;
        actualizarCantidadTotal();
        $(this).closest("tr").remove();
    }

var cleanDetalle = function(){
    $('#cantidad').val('');
    $('#Estado').val('');
   }


$(document).ready(function(){
    //esto se ejcuta al momento de poner sips
    $('.btnEditDetalle').bind("click", editDetalle);//asi se dentra para las class
    $('.btnDeleteDetalle').bind("click",deleteDetalle);

   $('#btnguardar').click(function()
   {
   		if(!verificarRequeridos() )
   		{
   			alert('Ingrese ');
   			return;
   		}

   		if (editandoDetalle){
                alert('Ingrese el detalle a guardar' + rowEdit);
                saveEditDetalle(rowEdit);
         }
        else{
        	alert("deberia de pasar por acas");
            var detalleToAppend = "<tr>";                
            detalleToAppend += "<td>";
            detalleToAppend += $('#cantidad').val();
            detalleToAppend += "</td>";
            detalleToAppend += "<td>";
            detalleToAppend += $("#Estado option:selected").html();;
            detalleToAppend += "</td>"
            detalleToAppend += `<td class=" cell-actions">
                        <button type="button" rel="tooltip" title="Editar Detalle" class="btnEditDetalle btn btn-info btn-simple btn-xs">
                            <i class="fa fa-edit"></i>
                        </button>
                        <button type="button" rel="tooltip" title="Eliminar" class="btnDeleteDetalle btn btn-danger btn-simple btn-xs">
                            <i class="fa fa-times"></i>
                        </button>
                    </td>`;
            detalleToAppend += "</td>";
            detalleToAppend += '<input type="hidden" name="IdAnimal1[]" value="'
            detalleToAppend += '';
            detalleToAppend += '">';

            detalleToAppend += '<input type="hidden" name="Cantidad1[]" value="';
            detalleToAppend += $('#cantidad').val();
            detalleToAppend += '">';
            
            detalleToAppend += '<input type="hidden" name="Estado1[]" value="';
            detalleToAppend += $('#Estado').val();
            detalleToAppend += '">';

            //  detalleToAppend = "</tr>";
           // alert("pasa aqui descripcion"+$('#Descripcion').val());
            $('#tableBienes').append(detalleToAppend);
            cleanDetalle();                                
            $('.btnEditDetalle').bind("click", editDetalle);
            $('.btnDeleteDetalle').off().on("click", deleteDetalle); 
			}
   		alert("esta acas");
   });

});

</script>

<div class="content">
	<div class="container-fluid">
		<div class="row">
			<div class="card">
				<ol class="breadcrumb">
					<li> <a href="<?php echo BASE_URL;?>InventariodeSeresVivos/"> 
						InventariodeAnimales</a>
						</li>
            <?php if($seresvivos->Id != "") {
              $nombreunidad = $this->model->ObtenerNombreUnidadProductiva($seresvivos->Unidad_Id)->Nombre; $nombreunidad .='-';
              } ?>
              <li class="active"><?php echo $seresvivos->Id != null ? ( ($_SESSION["TipoUsuario"] != 1 ) ? $seresvivos->FechaIngreso :$nombreunidad ): 'Nuevo Registro'; ?></li>
				</ol>

				<div class="content">
					<div class="row">
						<div class="col-md-6">
							<h4 class="title">
								<?php echo $seresvivos->Id!=null ? 'Inventario de Animales':'Nuevo Registro: Inventario de Animales '; ?>
							</h4>    
						</div>
					</div>
				</div>
				<ul style="text-align:left ; ">
                            <li> <A href="#agregarbien"> <h5> Nuevo Animal </h5> </A> </li> <!--es necesario el numeral <LI> <LI>-->
                </ul>

				<div class="content crud">
					<form method="post" action="<?php echo BASE_URL;?>InventariodeSeresVivos/Guardar/" enctype="multipart/form-date">
						<div class="row">
              <div class="col-md-6">
                  <div class="form-group">
                      <label> Descripcion</label>
                      <?php $descripcion2="";
                      if($seresvivos->Id >= 0) $descripcion2=$seresvivos->Descripcion;?>
                <textarea class="form-control" type="text" name="Descripcion" value="<?php echo $seresvivos->Descripcion; ?>" required placeholder ="Unadescripciongeneral"><?php echo $seresvivos->Descripcion ?></textarea>
                <input type="hidden" name="Id" value ="<?php echo $seresvivos->Id;?>"/>

                  </div>
              </div>

              <div class="col-md-6">
                  <div class="form-group">
                      <label> Observaciones</label>
                      <?php $descripcion2="";
                      if($seresvivos->Id > 0) $descripcion2=$seresvivos->Observaciones;?>
                <textarea required class="form-control" type="text" name="Observaciones" value="<?php echo $seresvivos->Observaciones; ?>" placeholder ="Unadescripciongeneral"><?php echo $descripcion2 ?></textarea>
                <input type="hidden" name="Id" value ="<?php echo $seresvivos->Id;?>"/>
                  </div>
              </div>

						</div>
            <div class="row">
              <div class="col-md-5">
              <label class="text"> Fecha de Ingreso</label>
              <?php $today=date("Y-m"); $today.=date("d")?>
              <input required class="form-control" type="text" name ="FechaIngreso"  max ="<?php echo date("Y-m-d") ;?>" value="<?php echo $seresvivos->FechaIngreso ;?>" placeholder="<?php echo date('Y-m-d');?>"   >
              </div>
              <div class="col-md-5">
                <label class="text"> Cantidad Total</label>
                <input type="number" class="form-control" name="cantidadtotal"disabled required value="<?php echo $seresvivos->Id != null ? ($this->model->getcantidadbyin($seresvivos->Id) =='' ? "0" : $this->model->getcantidadbyin($seresvivos->Id) ) : " 0 "; ?>">
              </div>
            </div>
            <div class="row">
                <div class="col-md-5"> 
                  <label class="text"> Razas de Animales </label><!--porque lo acepta con "0"-->
                  <select  id ="tipoanimal" class="form-control" name ="tipoanimal">
                   <?php foreach($this->modeltipoanimal->Listar() as $r) : ?> 
                      <option value="$r->Id"><?php echo $r->Descripcion ?>  </option>
                   <?php endforeach ?>
                  </select>
                </div>
                <?php if($_SESSION["Unidad_Id"] == $seresvivos->Unidad_Id || $seresvivos->Id ==""){ ?>
                <button type="submit" id="btnSubmit" class="btn btn-info btn-fill pull-right">Guardar</button>
                <?php }?>
                <br>
            </div>
						<div class=" card">
                            <div class="content">
                       		<a id ="agregarbien" > <h4 style ="text-align:center" ;> Nuevaos Animales </h4> </a>
                       		<div class="row">

                       			<div class="col-md-5">
                       				<!--<div class="form-group">-->
                       				<label >Cantidad</label>
                       				<input type="number" id ="cantidad" min="0" class="form-control" placeholder=""  value="" >
                       				<input type="hidden" id="IdSeresVivos" >
                       			</div>
                            <div class="col-md-5"> 
                              <label class="text"> Estado </label><!--porque lo acepta con "0"-->
                              <select  id ="Estado" class="form-control">
                                <option value=0 > Malo</option>
                                <option  value=1  > Regular</option>
                                <option  value=2 > Bueno</option>
                              </select>
                            </div>
                       		</div>

                      <?php if($_SESSION["Unidad_Id"] == $seresvivos->Unidad_Id || $seresvivos->Id ==""){ ?>
                       		<button type="button" id="btnguardar" class="btn btn-info btn-fill pull-right">Guardar</button>
                          <?php } ?>
                       		<h4>Animales</h4>
	                        <!--falta modificar esto un poco-->
	                        <div class="content table-responsive table-full-width">

	                            <table id="tableBienes" class="table table-hover table-striped">
	                                <thead><!--esto tambien da textura-->
	                                    <th>Cantidad</th>
                                      <th>Estado</th>
	                                </thead>
	                                <tbody id="target-content">
	                                <?php foreach($this->modelanimales->obtenerporidinventario($seresvivos->Id) as $r):?>
	                                	<tr>
	                                		<td> <?php echo $r->Cantidad  ?> </td>
                                      <?php $estado="0 Malo";
                                        if($r->Estado==1)
                                          $estado="1 Regular";
                                        else
                                          $estado="2 Bueno"; ?>
                                      <td> <?php echo $estado; ?> </td>
                            		        <td class=" td-actions text-right">
                                            <button type="button" rel="tooltip" title="Editar Detalle" class="btnEditDetalle btn btn-info btn-simple btn-xs">
                                            <i class="fa fa-edit"></i>
                                            </button>

                                            <?php if ($_SESSION["Unidad_Id"] ==  $seresvivos->Unidad_Id ){ ?>
                                            <button type="button" rel="tooltip" title="Eliminar" class="btnDeleteDetalle btn btn-danger btn-simple btn-xs">
                                            <i class="fa fa-times"></i><!--lo vuelve de color negro-->
                                            </button>
                                            <?php }?>
                                        </td>
                                            <input type="hidden" name="IdAnimal1[]"  value="<?php echo  $r->Id;?>">
                                            <input type="hidden" name="Cantidad1[]" value="<?php echo $r->Cantidad;?> ">
                                            <input type="hidden" name="Estado1[]" value="<?php echo $r->Estado;?>">               
	                                	</tr>
	                                <?php endforeach ?>
	                                </tbody>
	                            </table>
	                        </div>

                       		</div>
                       	</div>
					</form>
				</div>					
			</div>
		</div>
	</div>
</div>
