<script type="text/javascript" src="<?php echo BASE_URL;?>Assets/js/jspdf.min.js"></script>

<script type="text/javascript">//aca guarda el detalle y todo lo demas
//btnGuardarDetalleFisico
//verificar el estado
//para añadir una nuevo material insumo se tiene que añadir por una tabla auxiliar
//me falta que lo que yo guardo aparesca en la tabla contigua averiguar

var cantidadAcumulada =  parseFloat("<?php echo $Inventariofisico->Id != null ? ($this->model->getCantidadDetalle($Inventariofisico->Id) =='' ? "0" : $this->model->getCantidadDetalle($Inventariofisico->Id)) : "0"; ?>");

var editandoDetalle =false;
var rowEdit=null;
var tdId_insumo22='';
var tdCantidad=0;

    var verificarRequeridos = function(){
        if ( $('#Cantidad').val()=='' ||  $('#Estado').val()==''){
            return false;
        }  
        return true;
    }
    
    var flag = true;

    var actualizarCantidadTotal = function()
    {
        alert("Actualiza a:" + cantidadAcumulada);
        //Actualizar el monto total
        $('[name=codigo2]').val(cantidadAcumulada.toFixed(2));
    }


    var deleteDetalle = function(){
            var parent = $(this).closest("tr");
            var tdCantidad = parent.children("td:nth-child(1)").html();
            alert("Quitando:" + tdCantidad);
            cantidadAcumulada -= parseFloat(tdCantidad);
            actualizarCantidadTotal();
            editandoDetalle=false;
            $(this).closest("tr").remove();
        }

    var saveEditDetalle = function(parent){
        
        var cantidad = $('#Cantidad').val();
        var cantidadAnterior = parent.children("td:nth-child(1)").html();
        var estado = $('#Estado').val();
        parent.children("td:nth-child(1)").html(cantidad);
        parent.children("td:nth-child(2)").html(estado);
        //estono me sale ademas me sale de un foranea llave yo cambie por char
        //aca lo pones los valores a los inputs ultimos array para enviar la informacion
        parent.children("input:nth-child(5)").val(cantidad);
        parent.children("input:nth-child(6)").val(estado);
        cantidadAcumulada -= parseFloat(cantidadAnterior);//lee todo y solo regresa el primer numero
        cantidadAcumulada += parseFloat(cantidad);
        actualizarCantidadTotal();//en vez del monto el mio seria la cantidad
        //cleanDetalle();
        editandoDetalle = false;
    }

    var editDetalle =  function(){
        editandoDetalle = true;
        var parent = $(this).closest("tr");
        rowEdit = parent;
        tdCantidad = parent.children("td:nth-child(1)").html();
        var tdEstado = parent.children("td:nth-child(2)").html();
        //el html cambia toodos los elementos por otro seleccionado o saca valor
        var valorint = parseFloat(tdId_insumo);
        $('#Cantidad').val(tdCantidad);
        $('#Estado').val(tdEstado);
    }

    var cleanDetalle = function(){
        $('#Cantidad').val('');
        $('#Estado').val('');
    }

    $(document).ready(function(){
        //esto se ejcuta al momento de poner sips
        $('.btnEditDetalle').bind("click", editDetalle);
        $('.btnDeleteDetalle').bind("click", deleteDetalle);

        $('#btnguardar').click(function(){
            //editandoDetalle =true
            if(!verificarRequeridos()){
                alert('Ingrese ');
                return;
            }

            if (editandoDetalle){
                saveEditDetalle(rowEdit);
            }
            else{
                var detalleToAppend = "<tr>";
                detalleToAppend += "<td>";
                detalleToAppend += $('#Cantidad').val();
                detalleToAppend += "</td>"                
                detalleToAppend += "<td>";
                detalleToAppend += $('#Estado').val();
                detalleToAppend += "</td>"
                detalleToAppend += `<td class=" cell-actions">
                                        <button type="button" rel="tooltip" title="Editar Detalle" class="btnEditDetalle btn btn-info btn-simple btn-xs">
                                            <i class="fa fa-edit"></i>
                                        </button>
                                        <button type="button" rel="tooltip" title="Eliminar" class="btnDeleteDetalle btn btn-danger btn-simple btn-xs">
                                            <i class="fa fa-times"></i>
                                        </button>
                                    </td>`;
                detalleToAppend += '<input type="hidden" name="IdDetalle[]" value="'
                detalleToAppend += '';
                detalleToAppend += '">';
                detalleToAppend += '<input type="hidden" name="CantidadDetalle[]"   value="'
                detalleToAppend += $('#Cantidad').val();
                detalleToAppend += '">';
                detalleToAppend += '<input type="hidden" name="Estado1[]" value="'
                detalleToAppend += $('#Estado').val();
                detalleToAppend += '">';
                cantidadAcumulada += parseFloat($('#Cantidad').val());
                $('#tableDetalle').append(detalleToAppend);
                actualizarCantidadTotal();
                cleanDetalle();                                
                $('.btnEditDetalle').bind("click", editDetalle);
                $('.btnDeleteDetalle').off().on("click", deleteDetalle); 
            }

        });

    });

</script>


<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    
                    <ol class="breadcrumb">
                        <li><a href="<?php echo BASE_URL;?>InventarioFisico/">InventarioFisico</a></li>
                        <?php if($Inventariofisico-> Id != "")
                        {   $nombreunidad = $this->model->getunidadByid($Inventariofisico->Unidad_Id)->Nombre; 
                             $nombreunidad .='-';
                           $nombreunidad .= $Inventariofisico->Descripcion_Existencia;
                        } ?>
                        <li class="active"><?php echo $Inventariofisico->Id != null ? ( ($_SESSION["TipoUsuario"] != 1 ) ? $Inventariofisico->Descripcion_Existencia :$nombreunidad   ): 'Nuevo Registro'; ?></li>
                    </ol>
                        
                    <div class="content">
                        <div class="row">
                            <div class="col-md-6">
                                <h4 class="title">
                                    <?php echo $Inventariofisico->Id!=null ? $Inventariofisico->Descripcion_Existencia : 'Nuevo Registro: InventarioFisico';?>
                                    <!--.' ('. $this->model->getRubroById($unidad->Rubro_Id).')' no se necesito sJ-->
                                </h4>    
                            </div>
                        </div>
                    </div>

                    <ul style="text-align:left ; ">
                            <li> <A href="#agregardetalle"> <h5> Agregar Detalle </h5> </A> </li> <!--es necesario el numeral <LI> <LI>-->
                    </ul>

                        
                        <form method="post" action="<?php echo BASE_URL;?>InventarioFisico/Guardar/" enctype="multipart/form-data"    >

                        <div class="content">
                        <!--<div class="content">-->
                            <div class="row">

                                <div class="col-md-6">
                                    <div class="form-group">

                                        <input type="hidden" name="Id" value="<?php echo              $Inventariofisico->Id; ?>">

                                        <label class="text-danger">Descripcion de Existencia(*)</label>
                                        <!--onBlur="checkNameAvailability()" esto es un scripts + abajo-->
                                        <input type="text" required maxlength="100" class="form-control" placeholder="eje muebles" name="Descripcion"  value="<?php echo $Inventariofisico->Descripcion_Existencia; ?>">
                                        <span id="name-availability-status"></span>
                                        <!--css spam previamente definido-->
                                       <!-- <p><img src="<?php echo BASE_URL;?>Assets/img/LoaderIcon.gif" id="loaderIcon" style="display:none" /></p>-->

                                    </div>
                                </div>  

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Año de Ingreso</label>
                                        <input type="date" class="form-control" name="Periodo1" min = "1828-01-00" max ="<?php date("Y-m-d");?>" placeholder="<?php echo date("Y-m-d");?>" value='<?php echo $Inventariofisico->FechaIngreso; ?>' required>
                                        <!--esto averiguar como ponerlo corectamente-->
                                    </div>
                                </div>

                            </div>



                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Tipos de Inventario</label>
                                        <select name="TipoInventario2" class="form-control" required >
                                                <?php foreach($this->model->getTipos_inventarios() as $r): ?>
                                                    <!--<?php echo "llal"?>-->
                                                        <option <?php echo ($Inventariofisico->TipoExistencia_Id==$r->Id) ? 'selected' : '' ?> value="<?php echo $r->Id?>" ><?php echo $r->Descripcion;?></option>
                                                        <?php endforeach; ?>
                                                ?>
                                        </select>

                                            <div class="form-group">
                                                
                                            <select name="Unidad" class="form-control" style="visibility:hidden" >
                                           <?php echo "<option selected value='".$_SESSION['Unidad_Id']."'>".$_SESSION['UnidadNombre']."1</option>";?>
                                           </select>
                                           </div>


                                    </div>
                                </div>

                                <div class="col-md-5">
                                    <div class="form-group">
                                         <label>Unidad Medida</label>
                                         <select name="UnidadMedida" class="form-control">
                                            <?php foreach($this->model->getUnidadMedida() as $r): ?>
                                            <option  value="<?php echo $r->Id?>"  <?php echo ($Inventariofisico->UnidadMedida_Id==$r->Id) ? 'selected' : '' ?>  >
                                            <?php echo $r->Descripcion;?></option>
                                            <?php endforeach; ?>
                                            ?>

                                        </select>                                       
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Cantidad </label>
                                        <input type="number"  name="codigo2" class="form-control"  
                                        min="0" step="1"  placeholder="0"  disabled required value="<?php echo   $Inventariofisico->Id != null ? ($this->model->getCantidadDetalle($Inventariofisico->Id) =='' ?  "0" : $this->model->getCantidadDetalle($Inventariofisico->Id) ) : " 0 "; ?>" >

                                        <!--esto averiguar como ponerlo corectamente-->
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Observaciones </label>
                                        <?php $observa ='';
                                        if($Inventariofisico->Id>0)
                                            $observa.=$Inventariofisico->Observaciones ?>
                                        <textarea class="form-control" name="Observaciones" placeholder="Escriba algo acà"><?php echo $observa ?></textarea>
                                    </div>
                                </div>

                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                <div class="form-group">
                                <label>Marca</label><!--observarlo despues-->
                                <?php $cont=0; ?>
                                <select id= "Marca22"  name ="Marca33" class=" form-control"  >
                                    <option value="-1" >---Todos--</option>
                                    <?php foreach ($this->Materialinsumo->getrowId() as $s): ?>
                                        <option value=<?php echo $s->Id;?> <?php echo ($Inventariofisico->Material_Insumo_Id==$s->Id) ? 'selected' : '' ?>  > <?php echo "$s->Id.-$s->Marca" ; ?>
                                         </option>

                                    <?php $cont++ ?>
                                    <?php endforeach ?>
                                </select>
                                </div>
                                </div>
                            </div>
                            <?php  if($_SESSION["TipoUsuario"] ==0  || $Inventariofisico->Id =="" || $_SESSION["Unidad_Id"] == $Inventariofisico->Unidad_Id ) {?>
                            <button type="submit" id="btnSubmit" class="btn btn-info btn-fill pull-right">Guardar</button>
                            <div class="clearfix"></div>
                            <?php }?>
                        

                       <!-- <A id="ini">Agregar Tipo</A>--> <!--aumentado esto por mi --> 
                       <div class=" card">
                            <div class="content">
                       <a id ="agregardetalle" > <h4 style ="text-align:center" ;> Nuevo Detalle</h4> </a>

                        <div class=" row" >
                            <div class="col-md-5">
                            <div class="form-group">
                                <label> Estado </label>
                                <select name = "Estado"  id = "Estado" class=" form-control" >
                                    <option id = "EBueno" value = 0 >Bueno</option>
                                    <option id ="ERegular" value = 1 > Regular</option>que esta regular

                                    <option id ="EMalo" value = 2 >Malo</option>que esta bien
                                    this->model->getCantidadDetalle($Inventariofisico->Codigo_Existencia)
                                </select>
                                <!--<input type="number" id="Estado" name="Estado3" step =1 min=1 max=3 class="form-control">-->

                            </div>
                            </div>
                            
                            <div  class="col-md-5">
                                <div class="form-group">
                                <label> Cantidad </label>
                                <input type="number" id="Cantidad" name="Cantidad22" step =0.1 min=1 class="form-control" value="<?php $Inventariofisico ?>">
                            </div>
                            </div>

                        </div>                            
                        <?php if( $Inventariofisico->Id == ""  || $_SESSION["Unidad_Id"] == $Inventariofisico->Unidad_Id )  {?>
                            <button type="button" id="btnguardar" class="btn btn-info btn-fill pull-right">Guardar</button>
                            <?php } ?>
                        <!--</form>-->

                        <h4>Detalles</h4>
                        <!--falta modificar esto un poco-->
                        <div class="content table-responsive table-full-width">

                            <table id="tableDetalle" class="table table-hover table-striped">
                                <thead><!--esto tambien da textura-->
                                    <th>Cantidad</th>
                                    <th>Estado</th>
                                </thead>
                                    <?php foreach($this->modeldetalle->getDetallesByIFisicoId($Inventariofisico->Id) as $r):?>
                                        <tr>
                                            <td><?php echo $r->Cantidad; ?></td>
                                            <td><?php echo $r->Estado; ?></td>
                                            <td class=" td-actions text-right"><!--</td> class="cell-actions">-->
                                                
                                                    <button type="button" rel="tooltip" title="Editar Detalle" class="btnEditDetalle btn btn-info btn-simple btn-xs">
                                                    <i class="fa fa-edit"></i>
                                                    </button>
                                                    <?php if ($_SESSION["Unidad_Id"] == $Inventariofisico->Unidad_Id ){ ?>
                                                    <button type="button" rel="tooltip" title="Eliminar" class="btnDeleteDetalle btn btn-danger btn-simple btn-xs">
                                                    <i class="fa fa-times"></i>
                                                    </button>
                                                    <?php } ?>

                                            </td>
                                           <!-- <td>-->
                                                <input type="hidden" name="IdDetalle[]"  value="<?php echo  $r->Id;?>">

                                                <input type="hidden" name="CantidadDetalle[]" value="<?php echo $r->Cantidad;?> ">

                                               <input type="hidden" name="Estado1[]" value="<?php echo   $r->Estado;?>">

                                        </tr>                                 
                                        
                                    <?php endforeach; ?>

                                </tbody>

                            </table>

                        </div>
                        </div>
                        </div>
                        </div>
                    </form>

                    </div>


                </div>
            </div>
        </div>
    </div>
</div>
