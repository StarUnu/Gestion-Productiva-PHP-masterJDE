<script type="text/javascript">


    $(document).ready(function(){
        mostrarConceptosCorrectos();

        function mostrarConceptosCorrectos()
        {
            var valorConcepto = $('#Concepto').val();
            var valorTipoOperacion = $('#TipoOperacion').val()
            if (valorTipoOperacion == 1)
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
            }
            else
            {
                if (valorConcepto<3)
                {
                    $('#Concepto').val(3);
                    //$("#Concepto option[value='3']").attr('selected', 'selected');
                }
                 $("#Concepto option[value='1']").attr('hidden','hidden');
                 $("#Concepto option[value='2']").attr('hidden','hidden');
                 $("#Concepto option[value='3']").removeAttr('hidden');
                 $("#Concepto option[value='4']").removeAttr('hidden');
                 $("#Concepto option[value='5']").removeAttr('hidden');
            }
        }
        $('#TipoOperacion').change(mostrarConceptosCorrectos);

    })

</script>

<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    
                    <ol class="breadcrumb">
                        <li><a href="<?php echo BASE_URL;?>Operaciones/">Operaciones</a></li>
                        <li class="active"><?php echo $operacion->Id != null ? ($operacion->Tipo==1 ? "Ingreso" : "Egreso").'('.$operacion->Fecha.')' : 'Registro de Operación'; ?></li>
                    </ol>
                    <form method="post" action="<?php echo BASE_URL;?>Operaciones/Guardar/" enctype="multipart/form-data">
                        <div class="content">

                            <div class="row">
                                <div class="col-md-1"></div>
                                <div class="col-md-11">
                                    <h4 class="title" id="Titulo">
                                        <?php echo $operacion->Id!=null ? ($operacion->Tipo==1 ? "Ingreso" : "Egreso").' ('. $this->model->getUnidadById($operacion->Unidad_Id).')' : 'Nuevo Registro: Operaciones';?>
                                    </h4>    
                                </div>
                            </div>

                            <div class="content crud">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Periodo</label>
                                            <input type="number" id="Periodo" name="Periodo" class="form-control" min="1900" max="2099" size="4" step="1" value="<?php echo $operacion->Id != null ? ($operacion->Periodo ==null ? date('Y') : $operacion->Periodo) : date('Y'); ?>"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Mes</label>
                                            <select name="Mes" id="Mes" class="form-control">
                                                <option <?php echo $operacion->Mes==1 ? 'selected':''; ?> value="1">Enero (1)</option>
                                                <option <?php echo $operacion->Mes==2 ? 'selected':''; ?> value="2">Febrero (2)</option>
                                                <option <?php echo $operacion->Mes==3 ? 'selected':''; ?> value="3">Marzo (3)</option>
                                                <option <?php echo $operacion->Mes==4 ? 'selected':''; ?> value="4">Abril (4)</option>
                                                <option <?php echo $operacion->Mes==5 ? 'selected':''; ?> value="5">Mayo (5)</option>
                                                <option <?php echo $operacion->Mes==6 ? 'selected':''; ?> value="6">Junio (6)</option>
                                                <option <?php echo $operacion->Mes==7 ? 'selected':''; ?> value="7">Julio (7)</option>
                                                <option <?php echo $operacion->Mes==8 ? 'selected':''; ?> value="8">Agosto (8)</option>
                                                <option <?php echo $operacion->Mes==9 ? 'selected':''; ?> value="9">Septiembre (9)</option>
                                                <option <?php echo $operacion->Mes==10 ? 'selected':''; ?> value="10">Octubre (10)</option>
                                                <option <?php echo $operacion->Mes==11 ? 'selected':''; ?> value="11">Noviembre (11)</option>
                                                <option <?php echo $operacion->Mes==12 ? 'selected':''; ?> value="12">Diciembre (12)</option>
                                            </select>
                                        </div>
                                    </div>    
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <input type="hidden" name="Id" value="<?php echo $operacion->Id; ?>" />
                                            <label>Tipo</label>
                                            <select name="Tipo" id="TipoOperacion" class="form-control">
                                                <option <?php echo $operacion->Tipo==1 ? 'selected':''; ?> value="1">Ingreso</option>
                                                <option <?php echo $operacion->Tipo==2 ? 'selected':''; ?> value="2">Egreso</option>
                                            </select>
                                        </div>
                                    </div>    
                                </div>

                                <div class="row">
                                    <div class="col-md-12" <?php echo (!EDICION_GENERAL_PERMITIDA || $_SESSION['TipoUsuario']==0)? 'hidden' : ''?> >
                                        <div class="form-group">
                                            <label>Unidad Productiva</label>
                                            <select name="Unidad" class="form-control">
                                                <?php
                                                    if ($_SESSION['TipoUsuario']==0) {
                                                        echo "<option selected value='".$_SESSION['Unidad_Id']."'>".$_SESSION['UnidadNombre']."</option>";
                                                    } else {
                                                        if (EDICION_GENERAL_PERMITIDA==false)
                                                        {
                                                            echo "<option selected value='".$_SESSION['Unidad_Id']."'>".$_SESSION['UnidadNombre']."</option>";
                                                        } else {
                                                         ?>
                                                        <?php foreach($this->modelUnidadProductiva->getAll() as $r): ?>
                                                            <option <?php echo ($operacion->Unidad_Id==$r->Id) ? 'selected' : '' ?> value="<?php echo $r->Id?>" ><?php echo $r->Nombre;?></option>
                                                        <?php endforeach; ?>
                                                <?php
                                                      }
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Concepto</label>
                                            <select class="form-control" id="Concepto" name="Concepto">
                                                <option value="1" <?php if ($operacion->Concepto==1) echo 'selected'; ?> <?php if ($operacion->Tipo==2) echo 'disabled' ?>>Ventas</option>
                                                <option value="2" <?php if ($operacion->Concepto==2) echo 'selected'; ?> <?php if ($operacion->Tipo==2) echo 'disabled' ?>>Otros Ingresos</option>
                                                <option value="3" <?php if ($operacion->Concepto==3) echo 'selected'; ?> <?php if ($operacion->Tipo==1) echo 'disabled' ?>>Costos Totales</option>
                                                <option value="4" <?php if ($operacion->Concepto==4) echo 'selected'; ?> <?php if ($operacion->Tipo==1) echo 'disabled' ?>>Costos Fijos</option>
                                                <option value="5" <?php if ($operacion->Concepto==5) echo 'selected'; ?> <?php if ($operacion->Tipo==1) echo 'disabled' ?>>Costos Variables</option>
                                            </select>    
                                        </div>
                                    </div>    
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Monto</label>
                                                <div class="input-group">
                                                  <div class="input-group-addon">S/.</div>
                                                    <input type="number" required name="Monto" min="0" step="1.00" class="form-control" id="exampleInputAmount" placeholder="Cantidad en Soles" value="<?php echo $operacion->Id != null ? ($operacion->Monto ==null ? "0" : $operacion->Monto) : "0"; ?>">
                                                  <div class="input-group-addon">.00</div>
                                                </div>
                                        </div>
                                    </div>    
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Tipo de Comprobante/Documento</label>
                                            <select name="TipoComprobateDocumento" class="form-control">
                                                <?php foreach($this->modelTipoComprobante->getComprobantesSeleccionados() as $r): ?>
                                                    <option <?php echo ($operacion->Tipo_Comprobante_Documento_Id==$r->Id) ? 'selected' : '' ?> value="<?php echo $r->Id?>" ><?php echo $r->Descripcion;?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>



                                <div class="row">
                                    <div class="form-group">
                                        <a class="btn btn-info btn-fill pull-right btnMargin" href="<?php echo BASE_URL;?>Operaciones">Cancelar</a>                                        
                                        <div <?php echo ($operacion->Unidad_Id != $_SESSION['Unidad_Id'] && !EDICION_GENERAL_PERMITIDA) ? (($operacion->Unidad_Id!=null) ? 'hidden' : '') : '' ?> >
                                            <button type="submit" id="btnSubmit" class="btn btn-info btn-fill pull-right btnMargin">Guardar</button>    
                                        </div>
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