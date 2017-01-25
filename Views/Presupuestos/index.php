<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">

                                
                    <div class="content">
                        <div class="alert alert-info">
                          <strong>Info!</strong> Solo el Administrador de cada unidad puede ingresar sus respectivos presupuestos.
                        </div>
                        
                        <div class="row" hidden>
                            <div style="margin-left: 15%; margin-right: 15%;" <?php echo ($_SESSION['TipoUsuario']==0) ? 'hidden' : ''?> >
                                <div class="form-group">
                                    <label>Unidad Productiva</label>
                                    <select name="UnidadPrincipal" id="UnidadPrincipal" class="form-control">
                                        <?php
                                            if ($_SESSION['TipoUsuario']==0) {
                                                echo "<option selected value='".$_SESSION['Unidad_Id']."'>".$_SESSION['UnidadNombre']."</option>";
                                            } else { ?>
                                                <?php foreach($this->modelUnidadProductiva->getAll() as $r): ?>
                                                    <option <?php echo ($_SESSION['Unidad_Id']==$r->Id) ? 'selected' : '' ?> value="<?php echo $r->Id?>" ><?php echo $r->Nombre;?></option>
                                                <?php endforeach; ?>
                                        <?php
                                            }
                                        ?>
                                    </select>
                                </div>
                            </div>    
                        </div>
                        <br>

                        <div class="row">
                            <div class="col-md-6">
                                <h4 class="title" style="text-align:center">Asignar Presupuesto:</h4>    
                                <form method="post" action="<?php echo BASE_URL;?>Presupuestos/Guardar/">
                                    <input type="hidden" value="0" id="Unidad" name="Unidad"></input>
                                    <div class="card form-aux" style="padding: 5%;">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>Periodo</label>
                                                    <input class="form-control" maxlength="4" type="number" id="Periodo" name="Periodo" min="1900" max="2099" step="1" placeholder="Año" value="<?php echo date('Y');?>" />
                                                </div>
                                            </div>
                                            <br>
                                        </div>    
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>Asignado</label>
                                                    <div class="input-group">
                                                      <div class="input-group-addon">S/.</div>
                                                        <input type="number" name="Asignado" min="0" step="1.0" class="form-control" id="Asignado" placeholder="Amount" value="0">
                                                      <div class="input-group-addon">.00</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <button type="submit" class="btn btn-info btn-fill pull-right">Asignar</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>    
                            </div>

                            <div class="col-md-6">
                                <h4 class="title" style="text-align:center">Ejecución del presupuesto asignado:</h4>
                                    <form method="post" action="<?php echo BASE_URL;?>Presupuestos/Ejecutar/">
                                        <div class="card" style="padding: 5%;">
                                            <label>Presupuesto</label>
                                            <select name="Presupuesto" id="Presupuesto" class="form-control">
                                                <?php foreach($this->model->getAllByUnidad($_SESSION['Unidad_Id']) as $r): ?>
                                                    <option value="<?php echo $r->Id?>" ><?php echo $r->Periodo.' - '.$r->Asignado;?></option>
                                                <?php endforeach; ?>
                                            </select>
                                            <label>Ejecución</label>
                                            <select name="Tipo" id="TipoEjecucion" class="form-control">
                                                <option value="1">ADQUISICIÓN DE INFRAESTRUCTURA</option>
                                                <option value="2">ADQUISICIÓN DE EQUIPAMIENTO</option>
                                                <option value="3">INVESTIGACIÓN Y CAPACITACIÓN</option>
                                                <option value="4">INCENTIVOS, PUBLICACIONES Y OTROS</option>
                                            </select>

                                            <label>Concepto</label>
                                            <select class="form-control" id="Concepto" name="IdentificadorConcepto">
                                                <option value="1" >Terrenos</option>
                                                <option value="2" >Edificios y Construcciones</option>
                                                <!--EQUIPAMIENTO-->
                                                <option hidden value="3" >Equipos de Cómputo y Laboratorio</option>
                                                <option hidden value="4" >Muebles y enseres</option>
                                                <option hidden value="5" >Maquinaria y equipos varios</option>
                                                <option hidden value="6" >Unidades de Transporte</option>
                                                <option hidden value="7" >Tangibles</option>
                                                <!--INVESTIGACIÓN Y CAPACITACIÓN-->
                                                <option hidden value="8" >Proyectos de Investigación</option>
                                                <option hidden value="9" >Otros Estudios</option>
                                                <option hidden value="10" >Capacitación</option>
                                                <!--INCENTIVOS, PUBLICACIONES Y OTROS-->
                                                <option hidden value="11" >Incentivos y Bonificaciones</option>
                                                <option hidden value="12" >Impresiones y Publicaciones</option>
                                                <option hidden value="13" >Otros bienes y servicios</option>
                                            </select>    

                                            <label>Fuente de Financiamiento</label>
                                            <select class="form-control" id="FuenteFinanciamiento" name="FuenteFinanciamiento">
                                                <option value="RO" >RO:Recursos Ordinarios</option>
                                                <option value="RDR" >RDR: Recursos Directamente Recaudados</option>
                                                <!--EQUIPAMIENTO-->
                                                <option value="DyT" >DyT:Donaciones y Transferencias</option>
                                                <option value="ROOC" >ROOC: Recursos por Operaciones Oficiales de Crédito</option>
                                                <option value="RD" >RD: Recursos Determinados</option>
                                            </select>

                                            <label>Monto</label>
                                            <div class="input-group">
                                              <div class="input-group-addon">S/.</div>
                                                <input type="number" name="Monto" min="0" step="1.00" class="form-control" id="MontoEjecucion" placeholder="S./ 0.00" value="0">
                                              <div class="input-group-addon">.00</div>
                                            </div>
                                            <br>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <button type="submit" class="btn btn-info btn-fill pull-right">Ejecutar</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                            </div>
                        </div>
                        

                        <div class="content table-responsive table-full-width">
                            <table class="table table-hover table-striped">
                                <thead>
                                    <th>PERIODO</th>                                        
                                    <th>ASIGNADO</th>
                                    <th>EJECUTADO(INFRAESTRUCTURA)</th>
                                    <th>EJECUTADO(EQUIPAMIENTO)</th>
                                    <th>EJECUTADO(INVESTIGACION Y CAPACITACIÓN)</th>
                                    <th>EJECUTADO(INCENTIVOS, PUBLICACIONES Y OTROS)</th>
                                </thead>
                                <tbody id="target-content">
                                    <?php foreach($this->model->Listar($startFrom) as $r): ?>
                                        <tr>
                                            <td><?php echo $r->Periodo; ?></td>
                                            <td><?php echo $r->Asignado; ?></td>
                                            <td><?php echo $this->modelEjecucionPresupuesto->getMontoEjecutadoAgrupadoPeriodoUnidadTipo($r->Periodo, $_SESSION['Unidad_Id'], 1);?></td>
                                            <td><?php echo $this->modelEjecucionPresupuesto->getMontoEjecutadoAgrupadoPeriodoUnidadTipo($r->Periodo, $_SESSION['Unidad_Id'], 2);?></td>
                                            <td><?php echo $this->modelEjecucionPresupuesto->getMontoEjecutadoAgrupadoPeriodoUnidadTipo($r->Periodo, $_SESSION['Unidad_Id'], 3);?></td>
                                            <td><?php echo $this->modelEjecucionPresupuesto->getMontoEjecutadoAgrupadoPeriodoUnidadTipo($r->Periodo, $_SESSION['Unidad_Id'], 4);?></td>

                                            <td class="cell-actions">
                                                <div class="btn-group">
                                                    <a onclick="javascript:return confirm('¿Seguro de eliminar este registro?, Se eliminaran todas las ejecuciones relacionadas a este presupuesto');" class="btn btn-xs btn-danger buttonCrud" href="<?php echo BASE_URL; ?>Presupuestos/Eliminar/<?php echo $r->Id; ?>"><span class="glyphicon glyphicon-trash"></span></a>
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
                                        <li class='active'  id="<?php echo $i;?>"><a href='<?php echo BASE_URL;?>Presupuestos/Pagination/Page/<?php echo $i;?>'><?php echo $i;?></a></li> 
                                        <?php else:?>
                                        <li id="<?php echo $i;?>"><a href='<?php echo BASE_URL;?>Presupuestos/Pagination/Page/<?php echo $i;?>'><?php echo $i;?></a></li>
                                    <?php endif;?>          
                            <?php endfor;endif;?>
                        </ul></nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
$(document).ready(function(){

    setValueUnidad();
    $('#UnidadPrincipal').on('change', setValueUnidad);

    function setValueUnidad()
    {
        var valorUnidadPrincipal = $('#UnidadPrincipal').val();
        $('#Unidad').val(valorUnidadPrincipal);
    }

    var BASE_URL = "<?php echo BASE_URL; ?>";

    $('#Presupuesto').on('change', actualizarValoresFinanciamientoMonto);
    $('#Concepto').on('change', actualizarValoresFinanciamientoMonto);

    //Ejecutar esta función cuando cambia el combobox de Presupuesto o Concepto
    //Actualizar los valores de Fuente de financiamiento, Monto
    function actualizarValoresFinanciamientoMonto()
    {
        //$("#response-container").html("<p>Buscando...</p>");
        var presupuesto_id = $('#Presupuesto').val();
        var concepto_id = $('#Concepto').val();

        getEjecucion(presupuesto_id, concepto_id)
        .done( function( response ) {
            if( response.success ) {
                delete response['success'];
                console.log(response);
                var found = false;
                $.each(response, function( key, value ) {
                    found = true;
                    console.log(value);
                    $('#FuenteFinanciamiento').val(value.FuenteFinanciamiento);
                    $('#MontoEjecucion').val(value.Monto);
                    console.log(value.FuenteFinanciamiento + value.Monto);
                });
                if (!found)
                {
                    $("#FuenteFinanciamiento").val($("#FuenteFinanciamiento option:first").val());
                    $('#MontoEjecucion').val(0);
                }

                //$("#response-container").html('');
                
                } else {
                    //$('#area-ingresos').html('No hay datos para mostrar');
                    console.log("error");
            }
        })
        .fail(function( jqXHR, textStatus, errorThrown ) {
            alert("Ocurrio un error", textStatus);
            console.log(textStatus);
            //$("#response-container").html("Algo ha fallado: " +  textStatus);
        });
    }

    var getEjecucion = function(presupuesto_id, concepto_id){
        return $.getJSON(BASE_URL + "Presupuestos/Ejecucion/" + presupuesto_id + "/"+concepto_id);
    }


    $('#TipoEjecucion').change(function(){
        var valorConcepto = $('#Concepto').val();
        if (this.value == 1)
        {
            
            if (valorConcepto>2)
            {
                $('#Concepto').val(1);
                //$("#Concepto option[value='1']").attr('selected', 'selected');
            }
            
             $("#Concepto option[value='1']").removeAttr('hidden');
             $("#Concepto option[value='2']").removeAttr('hidden');
             $("#Concepto option[value='3']").attr('hidden','hidden');
             $("#Concepto option[value='4']").attr('hidden','hidden');
             $("#Concepto option[value='5']").attr('hidden','hidden');
             $("#Concepto option[value='6']").attr('hidden','hidden');
             $("#Concepto option[value='7']").attr('hidden','hidden');
             $("#Concepto option[value='8']").attr('hidden','hidden');
             $("#Concepto option[value='9']").attr('hidden','hidden');
             $("#Concepto option[value='10']").attr('hidden','hidden');
             $("#Concepto option[value='11']").attr('hidden','hidden');
             $("#Concepto option[value='12']").attr('hidden','hidden');
             $("#Concepto option[value='13']").attr('hidden','hidden');
        }
        else if (this.value==2)
        {
            
            if (valorConcepto<3 || valorConcepto>7)
            {
                $('#Concepto').val(3);
                //$("#Concepto option[value='3']").attr('selected', 'selected');
            }
             $("#Concepto option[value='1']").attr('hidden','hidden');
             $("#Concepto option[value='2']").attr('hidden','hidden');
             $("#Concepto option[value='3']").removeAttr('hidden');
             $("#Concepto option[value='4']").removeAttr('hidden');
             $("#Concepto option[value='5']").removeAttr('hidden');
             $("#Concepto option[value='6']").removeAttr('hidden');
             $("#Concepto option[value='7']").removeAttr('hidden');
             $("#Concepto option[value='8']").attr('hidden','hidden');
             $("#Concepto option[value='9']").attr('hidden','hidden');
             $("#Concepto option[value='10']").attr('hidden','hidden');
             $("#Concepto option[value='11']").attr('hidden','hidden');
             $("#Concepto option[value='12']").attr('hidden','hidden');
             $("#Concepto option[value='13']").attr('hidden','hidden');
        } else if (this.value==3)
        {
            if (valorConcepto<8 || valorConcepto>10)
            {
                $('#Concepto').val(8);
                //$("#Concepto option[value='3']").attr('selected', 'selected');
            }
            $("#Concepto option[value='1']").attr('hidden','hidden');
            $("#Concepto option[value='2']").attr('hidden','hidden');
            $("#Concepto option[value='3']").attr('hidden','hidden');
            $("#Concepto option[value='4']").attr('hidden','hidden');
            $("#Concepto option[value='5']").attr('hidden','hidden');
            $("#Concepto option[value='6']").attr('hidden','hidden');
            $("#Concepto option[value='7']").attr('hidden','hidden');
            $("#Concepto option[value='8']").removeAttr('hidden');
            $("#Concepto option[value='9']").removeAttr('hidden');
            $("#Concepto option[value='10']").removeAttr('hidden');
            $("#Concepto option[value='11']").attr('hidden','hidden');
            $("#Concepto option[value='12']").attr('hidden','hidden');
            $("#Concepto option[value='13']").attr('hidden','hidden');
        } else 
        {
            if (valorConcepto<11)
            {
                $('#Concepto').val(11);
                //$("#Concepto option[value='3']").attr('selected', 'selected');
            }
            $("#Concepto option[value='1']").attr('hidden','hidden');
            $("#Concepto option[value='2']").attr('hidden','hidden');
            $("#Concepto option[value='3']").attr('hidden','hidden');
            $("#Concepto option[value='4']").attr('hidden','hidden');
            $("#Concepto option[value='5']").attr('hidden','hidden');
            $("#Concepto option[value='6']").attr('hidden','hidden');
            $("#Concepto option[value='7']").attr('hidden','hidden');
            $("#Concepto option[value='8']").attr('hidden','hidden');
            $("#Concepto option[value='9']").attr('hidden','hidden');
            $("#Concepto option[value='10']").attr('hidden','hidden');
            $("#Concepto option[value='11']").removeAttr('hidden');
            $("#Concepto option[value='12']").removeAttr('hidden');
            $("#Concepto option[value='13']").removeAttr('hidden');
        }
        $('#Concepto').trigger('change');
    });
    $('#Periodo').val(new Date().getFullYear());
$('.pagination').pagination({
        items: <?php echo $totalRecords;?>,
        itemsOnPage: <?php echo resultsPerPage;?>,
        cssStyle: 'light-theme',
        currentPage : 1,
        onPageClick : function(pageNumber) {
            $("#target-content").html('Cargando...');
            var base = "<?php echo BASE_URL;?>";
            $("#target-content").load(base + "Presupuestos/Pagination/Page/" + pageNumber);
        }
    });
});
</script>