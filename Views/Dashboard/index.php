        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="header">
                                <h4 class="title" style="text-align: center;">Unidad Productiva</h4>
                            </div>
                            <div class="content">

                                <div class="form-group" style="margin-left: 15%; margin-right: 15%;">
                                    <select name="Unidad" id="Unidad" class="form-control">
                                        <?php
                                            if ($_SESSION['TipoUsuario']==0) {
                                                echo "<option selected value='".$_SESSION['Unidad_Id']."'>".$_SESSION['UnidadNombre']."</option>";
                                            } else { ?>
                                                <?php foreach($this->modelUnidadProductiva->getAll() as $r): ?>
                                                    <option value="<?php echo $r->Id?>" ><?php echo $r->Nombre;?></option>
                                                <?php endforeach; ?>
                                        <?php
                                            }
                                        ?>
                                    </select>
                                </div>
                                
                                <div class="footer">
                                    <div class="stats">
                                    </div>
                                </div>
                            </div>    
                        </div>
                    </div>                    
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <div class="card ">
                            <div class="header">
                                <h4 class="title">Reportes</h4>
                            </div>
                            <div class="content">
                                <div>
                                    <label>Periodo: </label>
                                    <input type="number" name="Periodo" id="Periodo" class="form-control" min="1900" max="2099" size="4" step="1" value="<?php echo date('Y');?>" />
                                </div>
                                <br>
                                <div>
                                    <button type="button" id="reporteTrabajadoresBtn" class="btn btn-primary btn-fill btn-block" style="margin-bottom:4px;white-space: normal;">Relación de Trabajadores</button>      
                                </div>
                                <div>
                                    <button type="button" id="reporteDocumentosBtn" class="btn btn-primary btn-fill btn-block" style="margin-bottom:4px;white-space: normal;">Inventario de Documentos Existentes</button>            
                                </div>
                                                                <div>
                                    <button type="button" id="reporteInventarioFisicoBtn" class="btn btn-primary btn-fill btn-block" style="margin-bottom:4px;white-space: normal;">Inventario Fisico </button>            
                                </div>

                                <div>
                                    <button type="button" id="reporteInventarioEquiposBtn" class="btn btn-primary btn-fill btn-block" style="margin-bottom:4px;white-space: normal;">Inventario Equipos </button>            
                                </div>

                                <div>
                                    <button type="button" id="reporteInventarioBienMEIBtn" class="btn btn-primary btn-fill btn-block" style="margin-bottom:4px;white-space: normal;">Inventario Bienes Muebles e Inmuebles </button>            
                                </div>

                                <?php //queriendo hacer queno salge en todas
                                $facultad = $this->modelUnidadProductiva->Obtener($_SESSION['Unidad_Id'])->Facultad_Id ;
                                $facultad2="";
                                $facultad2=$this->modelUnidadProductiva->getnomfacultasByunidad($facultad)->Nombre;
                                ?>
                                <div>
                                <button type="button" id="reporteInventarioBienABtn" class="btn btn-primary btn-fill btn-block" style="margin-bottom:4px;white-space: normal;">Inventario de Animales </button>            
                                </div> 
                                <div>
                                <button type="button" id="reporteInventarioBienCYOBtn" class="btn btn-primary btn-fill btn-block" style="margin-bottom:4px;white-space: normal;">Inventario de Cosechas y Otros </button>            
                                </div>

                                <div>
                                    <button type="button" id="reporteIngresosEgresosBtn" class="btn btn-primary btn-fill btn-block" style="margin-bottom:4px;white-space: normal;">Reporte de Ingresos y Egresos</button>
                                </div>
                                <div hidden <?php echo ($_SESSION['TipoUsuario']==1) ? '' : 'hidden'?> >
                                    <button type="button" id="reporteAnualBtn" class="btn btn-primary btn-fill btn-block" style="margin-bottom:4px;white-space: normal;">REPORTE ANUAL SOBRE CENTROS DE PRODUCCIÓN DE BIENES Y SERVICIOS</button>
                                </div>
                                <div id="chartActivity" class="ct-chart"></div>

                                <!--
                                <div class="footer">
                                    <div class="legend">
                                        <i class="fa fa-circle text-info"></i> Tesla Model S
                                        <i class="fa fa-circle text-danger"></i> BMW 5 Series
                                    </div>
                                    <hr>
                                    <div class="stats">
                                        <i class="fa fa-check"></i> Data information certified
                                    </div>
                                </div>
                                -->
                            </div>
                        </div>
                    </div>

                    <div class="col-md-9">
                        <div class="card">
                            <div class="header">
                                <h4 class="title" style="text-align: center;">Estadisticas</h4>
                            </div>
                            <hr>
                            <div class="content">
                                <div id="response-container"></div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <h4 style="text-align: center;">Ingresos</h4>
                                        <div class="canvas-holder" id="area-ingresos">
                                            <canvas id="chart-area-Ingresos" />
                                        </div>    
                                    </div>
                                    <div class="col-md-6">
                                        <h4 style="text-align: center;">Egresos</h4>
                                        <div class="canvas-holder" id="area-Egresos">
                                            <canvas id="chart-area-Egresos" />
                                        </div> 
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--
                <div class="row">
                    <div class="col-md-4">
                        <div class="card">
                            <div class="header">
                                <h4 class="title">Email Statistics</h4>
                                <p class="category">Last Campaign Performance</p>
                            </div>
                            <div class="content">
                                <div id="chartPreferences" class="ct-chart ct-perfect-fourth"></div>

                                <div class="footer">
                                    <div class="legend">
                                        <i class="fa fa-circle text-info"></i> Open
                                        <i class="fa fa-circle text-danger"></i> Bounce
                                        <i class="fa fa-circle text-warning"></i> Unsubscribe
                                    </div>
                                    <hr>
                                    <div class="stats">
                                        <i class="fa fa-clock-o"></i> Campaign sent 2 days ago
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card ">
                            <div class="header">
                                <h4 class="title">Tasks</h4>
                                <p class="category">Backend development</p>
                            </div>
                            <div class="content">
                                <div class="table-full-width">
                                    <table class="table">
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <label class="checkbox">
                                                        <input type="checkbox" value="" data-toggle="checkbox">
                                                    </label>
                                                </td>
                                                <td>Sign contract for "What are conference organizers afraid of?"</td>
                                                <td class="td-actions text-right">
                                                    <button type="button" rel="tooltip" title="Edit Task" class="btn btn-info btn-simple btn-xs">
                                                        <i class="fa fa-edit"></i>
                                                    </button>
                                                    <button type="button" rel="tooltip" title="Remove" class="btn btn-danger btn-simple btn-xs">
                                                        <i class="fa fa-times"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <label class="checkbox">
                                                        <input type="checkbox" value="" data-toggle="checkbox" checked="">
                                                    </label>
                                                </td>
                                                <td>Lines From Great Russian Literature? Or E-mails From My Boss?</td>
                                                <td class="td-actions text-right">
                                                    <button type="button" rel="tooltip" title="Edit Task" class="btn btn-info btn-simple btn-xs">
                                                        <i class="fa fa-edit"></i>
                                                    </button>
                                                    <button type="button" rel="tooltip" title="Remove" class="btn btn-danger btn-simple btn-xs">
                                                        <i class="fa fa-times"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <label class="checkbox">
                                                        <input type="checkbox" value="" data-toggle="checkbox" checked="">
                                                    </label>
                                                </td>
                                                <td>Flooded: One year later, assessing what was lost and what was found when a ravaging rain swept through metro Detroit
</td>
                                                <td class="td-actions text-right">
                                                    <button type="button" rel="tooltip" title="Edit Task" class="btn btn-info btn-simple btn-xs">
                                                        <i class="fa fa-edit"></i>
                                                    </button>
                                                    <button type="button" rel="tooltip" title="Remove" class="btn btn-danger btn-simple btn-xs">
                                                        <i class="fa fa-times"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                -->
            </div>
        </div>

<script type="text/javascript">

    /*
    * ESTADISTICAS INGRESOS/EGRESOS
    */
    var dataIngreso = [];
    var labelsIngreso = [];
    var dataEgreso = [];
    var labelsEgreso = [];

    $(document).ready(function(){

        var BASE_URL = "<?php echo BASE_URL; ?>";
        
        //getdeails será nuestra función para enviar la solicitud ajax
        var getIngresos = function(id){
            return $.getJSON(BASE_URL + "Dashboard/Ingresos/" + id);
        }

        var getEgresos = function(id){
            return $.getJSON(BASE_URL + "Dashboard/Egresos/" + id);
        }

        var ctx = document.getElementById("chart-area-Ingresos").getContext("2d");
        var ctx2 = document.getElementById("chart-area-Egresos").getContext("2d");
        window.myPie = new Chart(ctx, configIngreso);
        window.myPie2 = new Chart(ctx2, configEgreso);
        //Obtener Ingresos

        getIngresos($('#Unidad').val())
        .done( function( response ) {
            dataIngreso = [];
            labelsIngreso = [];
            if( response.success ) {
                delete response['success'];
                $.each(response, function( key, value ) {
                    console.log(value);
                    //dataIngreso.push(value.Total);
                    //labelsIngreso.push(value.Descripcion);
                    console.log(value.Descripcion + value.Total);
                });
                /*
                configIngreso.data.datasets.forEach(function(dataset) {
                    dataset.data = dataIngreso;
                });*/
                console.log("actualiza el pie",);
                //configIngreso.data.labels = labelsIngreso;
                window.myPie.update();

                $("#response-container").html('');
                
                } else {
                    $('#area-ingresos').html('No hay datos para mostrar');
                    console.log("error");
            }
        })
        .fail(function( jqXHR, textStatus, errorThrown ) {
            alert("Ocurrio un error", textStatus);
            console.log(textStatus);
            $("#response-container").html("Algo ha fallado: " +  textStatus);
        });

        getEgresos($('#Unidad').val())
        .done( function( response ) {
            dataEgreso = [];
            labelsEgreso = [];
            if( response.success ) {
                delete response['success'];
                console.log(response);
                $.each(response, function( key, value ) {
                    console.log(value);
                    //dataEgreso.push(value.Total);
                    //labelsEgreso.push(value.Descripcion);
                    console.log(value.Descripcion + value.Total);
                });
                configEgreso.data.datasets.forEach(function(dataset) {
                    //dataset.data = dataEgreso;
                });

                //configEgreso.data.labels = labelsEgreso;

                window.myPie2.update();
                $("#response-container").html('');
                
                } else {
                    $('#area-Egresos').html('No hay datos para mostrar');
                    console.log("error");
            }
        })
        .fail(function( jqXHR, textStatus, errorThrown ) {
            alert("Ocurrio un error", textStatus);
            console.log(textStatus);
            $("#response-container").html("Algo ha fallado: " +  textStatus);
        });
        
        //al Cambiar la unidad.....
        $('#Unidad').on('change',function(e){
            $("#response-container").html("<p>Buscando...</p>");
            getIngresos(this.value)
            .done( function( response ) {
                dataIngreso = [];
                labelsIngreso = [];
                if( response.success ) {
                    delete response['success'];
                    console.log(response);
                    $.each(response, function( key, value ) {
                        console.log(value);
                        //dataIngreso.push(value.Total);
                        //labelsIngreso.push(value.Descripcion);
                        console.log(value.Descripcion + value.Total);
                    });
                    configIngreso.data.datasets.forEach(function(dataset) {
                        //dataset.data = dataIngreso;
                    });

                    //configIngreso.data.labels = labelsIngreso;

                    window.myPie.update();

                    $("#response-container").html('');
                    
                    } else {
                        $('#area-ingresos').html('No hay datos para mostrar');
                        console.log("error");
                }
            })
            .fail(function( jqXHR, textStatus, errorThrown ) {
                alert("Ocurrio un error", textStatus);
                console.log(textStatus);
                $("#response-container").html("Algo ha fallado: " +  textStatus);
            });

            getEgresos(this.value)
            .done( function( response ) {
                dataEgreso = [];
                labelsEgreso = [];
                if( response.success ) {
                    //delete response['success'];
                    console.log(response);
                    $.each(response, function( key, value ) {
                        console.log(value);
                        //dataEgreso.push(value.Total);
                        //labelsEgreso.push(value.Descripcion);
                        console.log(value.Descripcion + value.Total);
                    });
                    configEgreso.data.datasets.forEach(function(dataset) {
                        //dataset.data = dataEgreso;
                    });

                    //configEgreso.data.labels = labelsEgreso;

                    window.myPie2.update();
                    $("#response-container").html('');
                    
                    } else {
                        $('#area-Egresos').html('No hay datos para mostrar');
                        console.log("error");
                }
            })
            .fail(function( jqXHR, textStatus, errorThrown ) {
                alert("Ocurrio un error", textStatus);
                //console.log(textStatus);
                $("#response-container").html("Algo ha fallado: " +  textStatus);
            });
        });
    }); 
    var randomScalingFactor = function() {
        return Math.round(Math.random() * 100);
    };

    window.chartColors = {
        softGreen: 'rgb(106,255,101)',
        red: 'rgb(255, 99, 132)',
        orange: 'rgb(255, 159, 64)',
        yellow: 'rgb(255, 205, 86)',
        green: 'rgb(75, 192, 192)',
        blue: 'rgb(54, 162, 235)',
        purple: 'rgb(153, 102, 255)',
        grey: 'rgb(231,233,237)'
    };


    var configIngreso = {
        type: 'pie',
        data: {
            datasets: [{
                data: [
                    randomScalingFactor(),
                    randomScalingFactor(),
                    randomScalingFactor(),
                    randomScalingFactor(),
                    randomScalingFactor(),
                ],
                backgroundColor: [
                    window.chartColors.softGreen,
                    window.chartColors.orange,
                    window.chartColors.yellow,
                    window.chartColors.green,
                    window.chartColors.blue,
                ],
                label: 'Dataset 1'
            }],
            labels: [
                "Boleta de Venta",
                "Factura",
                "Liquidación de Compra",
                "Recibo por Honorarios",
                "Otros"
            ]
        },
        options: {
            responsive: true,
            tooltips: {
                callbacks: {
                    /*label: function(tooltipItem, data) {
                        var allData = data.datasets[tooltipItem.datasetIndex].data;
                        var tooltipLabel = data.labels[tooltipItem.index];
                        var tooltipData = allData[tooltipItem.index];
                        var total = 0;
                        for (var i in allData) {
                            if (i)
                            {
                                total += parseFloat(allData[i]);
                            }
                        }
                        var tooltipPercentage = Math.round((tooltipData / total) * 100);
                        return tooltipLabel + ': ' + tooltipData + ' (' + tooltipPercentage + '%)';
                    }*/
                }
            }
        }
    };


    var configEgreso = {
        type: 'pie',
        data: {
            datasets: [{
                data: [
                    randomScalingFactor(),
                    randomScalingFactor(),
                    randomScalingFactor(),
                    randomScalingFactor(),
                    randomScalingFactor(),
                ],
                backgroundColor: [
                    window.chartColors.softGreen,
                    window.chartColors.orange,
                    window.chartColors.yellow,
                    window.chartColors.green,
                    window.chartColors.blue,
                ],
                label: 'Dataset 1'
            }],
            labels: [
                "Boleta de Venta",
                "Factura",
                "Liquidación de Compra",
                "Recibo por Honorarios",
                "Otros"
            ]
        },
        options: {
            responsive: true,
            tooltips: {
                callbacks: {
                    /*
                    label: function(tooltipItem, data) {
                        var allData = data.datasets[tooltipItem.datasetIndex].data;
                        var tooltipLabel = data.labels[tooltipItem.index];
                        var tooltipData = allData[tooltipItem.index];
                        var total = 0;
                        for (var i in allData) {
                            total += parseFloat(allData[i]);    
                        }
                        var tooltipPercentage = Math.round((tooltipData / total) * 100);
                        return tooltipLabel + ': ' + tooltipData + ' (' + tooltipPercentage + '%)';
                    }*/
                }
            }
        }
    };
    
    /*
    * REPORTES
    *
 

    $(document).ready()
    {
        $('#reporteDocumentosBtn').click(openReporteDocumentos);
        $('#reporteTrabajadoresBtn').click(openReporteTrabajadores);
        $('#reporteIngresosEgresosBtn').click(openReporteIngresosEgresos);
        $('#reporteAnualBtn').click(openReporteAnual);

        $('#reporteInventarioFisicoBtn').click(openReporteInventarioFisico);
        $('#reporteInventarioEquiposBtn').click(openReporteInventarioEquipos);
        $('#reporteInventarioBienMEIBtn').click(openReporteInventarioBienesMEI);
        $('#reporteInventarioBienABtn').click(openReporteInventarioBienesA);
        $('#reporteInventarioBienCYOBtn').click(openReporteInventarioBienesCYO);
        
    }

	    function openReporteInventarioBienesMEI()
    {
            var unidadId ="<?php echo $_SESSION["Unidad_Id"] ; ?> ";
            var BASE_URL = "<?php echo BASE_URL;?>";
            var win = window.open(BASE_URL + "Dashboard/ReporteInventarioBienesMEI/" + unidadId, '_blank');
            if (win) {
            //Browser has allowed it to be opened
            win.focus();//no se porque lo pone esto acas
            } else {
                //Browser has blocked it lo bloqueo que son popus 
                alert('Por favor habilite los popups para este sitio.');
            }
    }

    function openReporteInventarioBienesA()
    {
        if("<?php echo $_SESSION["TipoUsuario"] ;?>" != -1)
        {
            var unidadId ="<?php echo $_SESSION["Unidad_Id"] ; ?> ";
            var BASE_URL = "<?php echo BASE_URL;?>";;
            var win = window.open(BASE_URL + "Dashboard/ReporteInventarioBienesA/" + unidadId, '_blank');
            if (win) {
            //Browser has allowed it to be opened
            win.focus();//no se porque lo pone esto acas
            } else {
                //Browser has blocked it lo bloqueo que son popus 
                alert('Por favor habilite los popups para este sitio.');
            }
        }
    }

        function openReporteInventarioBienesCYO()
    {
        if("<?php echo $_SESSION["TipoUsuario"] ;?>" != -1)
        {
            var unidadId ="<?php echo $_SESSION["Unidad_Id"] ; ?> ";
            var BASE_URL = "<?php echo BASE_URL;?>";
            var win = window.open(BASE_URL + "Dashboard/ReporteInventarioBienesCYO/" + unidadId, '_blank');
            if (win) {
            //Browser has allowed it to be opened
            win.focus();//no se porque lo pone esto acas
            } else {
                //Browser has blocked it lo bloqueo que son popus 
                alert('Por favor habilite los popups para este sitio.');
            }
        }
    }

    function openReporteInventarioEquipos()
    {
        if("<?php echo $_SESSION["TipoUsuario"] ;?>" != -1)
        {
            var unidadId ="<?php echo $_SESSION["Unidad_Id"] ; ?> ";
            var BASE_URL = "<?php echo BASE_URL;?>";
            var win = window.open(BASE_URL + "Dashboard/ReporteInventarioEquipos/" + unidadId, '_blank');
            if (win) {
            //Browser has allowed it to be opened
            win.focus();//no se porque lo pone esto acas
            } else {
                //Browser has blocked it lo bloqueo que son popus 
                alert('Por favor habilite los popups para este sitio.');
            }
        }
    }

    function openReporteInventarioFisico()
    {
        
        if("<?php echo $_SESSION["TipoUsuario"] ;?>" != -1)
        {
            var unidadId ="<?php echo $_SESSION["Unidad_Id"] ; ?> ";
            var BASE_URL = "<?php echo BASE_URL;?>";
            var win = window.open(BASE_URL + "Dashboard/ReporteInventarioFisico/" + unidadId, '_blank');
            if (win) {
            //Browser has allowed it to be opened
            win.focus();//no se porque lo pone esto acas
            } else {
                //Browser has blocked it lo bloqueo que son popus 
                alert('Por favor habilite los popups para este sitio.');
            }
        }
    }


    function openReporteAnual()
    {
        var periodoSeleccionado = $('#Periodo').val();
        var BASE_URL = "<?php echo BASE_URL;?>"
        var win = window.open(BASE_URL + "Dashboard/ReporteAnual/" + periodoSeleccionado , '_blank');
        if (win) {
            //Browser has allowed it to be opened
            win.focus();
        } else {
            //Browser has blocked it
            alert('Por favor habilite los popups para este sitio.');
        }
    }

    function openReporteDocumentos()
    {
        var unidadActual = $('#Unidad').val()
        var periodoSeleccionado = $('#Periodo').val();
        
        var BASE_URL = "<?php echo BASE_URL;?>"
        var win = window.open(BASE_URL + "Dashboard/ReporteDocumentos/" + unidadActual  + "/" + periodoSeleccionado, '_blank');
        if (win) {
            //Browser has allowed it to be opened
            win.focus();
        } else {
            //Browser has blocked it
            alert('Por favor habilite los popups para este sitio.');
        }
    }

    function openReporteTrabajadores()
    {
        var unidadActual = $('#Unidad').val();
        var BASE_URL = "<?php echo BASE_URL;?>"
        var win = window.open(BASE_URL + "Dashboard/ReporteTrabajadores/" + unidadActual, '_blank');
        if (win) {
            win.focus();
        } else {
            alert('Por favor habilite los popups para este sitio.');
        }
    }

    function openReporteIngresosEgresos()
    {
        var unidadActual = $('#Unidad').val();
        var periodoSeleccionado = $('#Periodo').val();
        //alert("UNIDAD ACTUAL ID:" + unidadActual);
        var BASE_URL = "<?php echo BASE_URL;?>";
        var win = window.open(BASE_URL + "Dashboard/ReporteIngresosEgresos/" + unidadActual + "/" + periodoSeleccionado, '_blank');
        if (win) {
            //Browser has allowed it to be opened
            win.focus();
        } else {
            //Browser has blocked it
            alert('Por favor habilite los popups para este sitio.');
        }
    }   */
</script>