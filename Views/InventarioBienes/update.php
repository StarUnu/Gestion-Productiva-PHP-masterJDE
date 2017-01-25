<script type="text/javascript" src="<?php echo BASE_URL;?>Assets/js/jspdf.min.js"></script>
<!--tiene que empezar autoincrementarse desde1-->
<script type="text/javascript">

var cantidadAcumulada = 0;
var editandoDetalle =false;
var rowEdit=null;
var tdId_insumo22='';
var tdCantidad=0;
  
var verificarRequeridos = function(){
        if ( $('#cantidad22').val()=='' || $('#Estado').val()==''|| $('#Observaciones').val()=='' ){
            return false;
        }
        return true;
    }

var saveEditDetalle = function(parent){
        var cantidad = $('#cantidad22').val();
        var estado = $('#Estado').val();
        var observaciones = $('#Observaciones').val();
        var pruebaobserva = observaciones;
        var idbien = $('#Idbien').val();
        var contestado=$("#Estado option:selected").html();
        parent.children("td:nth-child(1)").html(cantidad);
        parent.children("td:nth-child(2)").html(contestado);
        
        parent.children("input:nth-child(5)").val(idbien);
        parent.children("input:nth-child(6)").val(cantidad);
        parent.children("input:nth-child(7)").val(estado);
        cleanDetalle();
        editDetalle=false;
}

var editDetalle =  function(){
    editandoDetalle = true;
    var parent = $(this).closest("tr");
    rowEdit = parent;
    var tdcantidad = parent.children("td:nth-child(1)").html();
    var tdestado = parent.children("td:nth-child(2)").html();
    var tdObservaciones = parent.children("td:nth-child(3)").html();
    //el html cambia toodos los elementos por otro seleccionado o saca valor
    var valorint = parseFloat(tdestado);
    $('#Estado').val(valorint);
    alert("cantidad"+tdcantidad);
    $('#cantidad22').val(tdcantidad);
}


var deleteDetalle = function(){
        //alert("Quitando:" + tdCantidad);
        var parent = $(this).closest("tr");
        //var tdCantidad = parent.children("td:nth-child(1)").html();
        //cantidadAcumulada -= parseFloat(tdCantidad);
        //actualizarCantidadTotal();
        editandoDetalle=false;
        $(this).closest("tr").remove();
    }

var cleanDetalle = function(){
    $('#cantidad').val('');
    $('#Observaciones').val('');
    $('#Estado').val('');
    //$('#Idbien').val('');//esto esta demas creo
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
            detalleToAppend += '<input type="hidden" name="IdBien1[]" value="'
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
					<li> <a href="<?php echo BASE_URL;?>InventarioBienes/"> 
						Inventario de Bienes </a>
						</li>
            <?php if($Inventariobienes->Id != "") {
              $nombreunidad = $this->model->getunidadByid($Inventariobienes->Unidad_Id)->Nombre; $nombreunidad .='-';
              } ?>
              <li class="active"><?php echo $Inventariobienes->Id != null ? ( ($_SESSION["TipoUsuario"] != 1 ) ? $Inventariobienes->Fecha_Ingreso :$nombreunidad   ): 'Nuevo Registro'; ?></li>

					<!--<li class="active"><?php echo $Inventariobienes->Id != null ? $Inventariobienes ->Fecha_Ingreso : 'Nuevo Registro'; ?></li>-->
				</ol>

				<div class="content">
					<div class="row">
						<div class="col-md-6">
							<h4 class="title">
								<?php echo $Inventariobienes->Id!=null ? 'Inventario de Bienes':'Nuevo Registro: Inventario de Bienes'; ?>
							</h4>    
						</div>
					</div>
				</div>
				<ul style="text-align:left ; ">
                            <li> <A href="#agregarbien"> <h5> Nuevo bien </h5> </A> </li> <!--es necesario el numeral <LI> <LI>-->
                </ul>

				<div class="content crud">
					<form method="post" action="<?php echo BASE_URL;?>InventarioBienes/Guardar/" enctype="multipart/form-date">
						<div class="row">
              <div class="col-md-6">
                  <div class="form-group">
                      <label> Descripcion</label>
                      <?php $descripcion2="";
                      if($Inventariobienes->Id > 0) $descripcion2=$Inventariobienes->Descripcion;?>
                <textarea class="form-control" type="text" name="Descripcion" value="<?php echo $Inventariobienes->Descripcion; ?>" placeholder ="Unadescripciongeneral"><?php echo $descripcion2 ?></textarea>
                <input type="hidden" name="Id" value ="<?php echo $Inventariobienes->Id;?>"/>

                  </div>
              </div>

							<div class="col-md-6">
								<label class="text"> Estado Operativo </label>
								<select name="EstadoOperativo" class="form-control">
									<option value=0 <?php echo ($Inventariobienes->EstadoOperativo ==0 ) ? 'selected' : ''; ?> > no operativo</option>
									<option value=1  <?php echo ($Inventariobienes->EstadoOperativo ==1 ) ? 'selected' : '1' ; ?> > bien con reparaciones</option>
									<option value=2 <?php echo   ($Inventariobienes->EstadoOperativo == 2) ? 'selected' : '2' ; ?> > operativo</option>
								</select>
							</div>
						</div>
            <div class="row">
              <div class="col-md-6">
              <label class="text"> CantidadTotal</label>
              <input class="form-control" type="number"  disabled required value="<?php echo $Inventariobienes->Id != null ? ($this->model->getcantidadbyin($Inventariobienes->Id) =='' ?  0 : $this->model->getcantidadbyin($Inventariobienes->Id) ) :0; ?>"  >
              </div>
              <div class="col-md-6">
                  <label class="text"> Observaciones</label>
                  <textarea  class="form-control"  name="Observaciones" placeholder = "Cuales son sus Observaciones"
                    ><?php if($Inventariobienes->Id > 0) echo $Inventariobienes->Observaciones;?>
                  </textarea>
              </div>
            </div>
            <div class="row">
               <div class="col-md-6">
                <label class="text"> Tipo Material </label>
                <select name="TipoMaterial_Id" class="form-control" value ="$Inventariobienes->TipoMaterial_Id ">
                  <?php foreach ($this->modeltipomaterial->Listar() as $r ) :?>
                    <option ><?php echo $r->Descripcion ?></option>
                  <?php endforeach ?>
                </select>
              </div>
              <div  class="col-md-6">
              <?php if($_SESSION["Unidad_Id"] == $Inventariobienes->Unidad_Id || $Inventariobienes->Id ==""){ ?>
                  <button type="submit" id="btnSubmit" class="btn btn-info btn-fill pull-right">Guardar</button>
                  <?php }?>
                <br>
              </div>
            </div>
						<div class=" card">
                            <div class="content">
                       		<a id ="agregarbien" > <h4 style ="text-align:center" ;> Nuevo Bien </h4> </a>
                       		<div class="row">

                       			<div class="col-md-5">
                       				<!--<div class="form-group">-->
                       				<label >Cantidad</label>
                       				<input type="number" id ="cantidad22" min="0" class="form-control" placeholder="" >
                       				<input type="hidden" id="Idbien" >
                       			</div>
                              <div class="col-md-5"> 
                              <label class="text"> Estado </label><!--porque lo acepta con "0"-->
                              <select  id ="Estado" class="form-control">
                                <option value=0 > 0.-Malo</option>
                                <option  value=1  >1.-Regular</option>
                                <option  value=2 > 2.-Bueno</option>
                              </select>
                              </div>
                       		</div>

                      <?php if($_SESSION["Unidad_Id"] == $Inventariobienes->Unidad_Id || $Inventariobienes->Id ==""){ ?>
                       		<button type="button" id="btnguardar" class="btn btn-info btn-fill pull-right">Guardar</button>
                          <?php } ?>
                       		<h4>Bienes</h4>
	                        <!--falta modificar esto un poco-->
	                        <div class="content table-responsive table-full-width">

	                            <table id="tableBienes" class="table table-hover table-striped">
	                                <thead><!--esto tambien da textura-->
	                                    <th>Cantidad</th>
                                      <th>Estado</th>
	                                </thead>
	                                <tbody id="target-content">
	                                <?php foreach($this->modelbien->obtenerporidinventario($Inventariobienes->Id) as $r):?>
	                                	<tr>
	                                		<td> <?php echo $r->Cantidad?> </td>
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

                                            <?php if ($_SESSION["Unidad_Id"] ==              $Inventariobienes->Unidad_Id ){ ?>
                                            <button type="button" rel="tooltip" title="Eliminar" class="btnDeleteDetalle btn btn-danger btn-simple btn-xs">
                                            <i class="fa fa-times"></i><!--lo vuelve de color negro-->
                                            </button>
                                            <?php }?>
                                        </td>
                                            <input type="hidden" name="IdBien1[]"  value="<?php echo  $r->Id;?>">

                                            <input type="hidden" name="TipoMaterial1[]" value="<?php echo $r->TipoMaterial_Id;?> ">
                                            <input type="hidden" name="Cantidad1[]" value="<?php echo $r->Cantidad;?> ">
                                            <input type="hidden" name="Estado1[]" value="<?php echo $r->Estado;?>">
                                            <input type="hidden" name="Observaciones1[]" value="<?php echo $r->Observaciones;?>">
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
