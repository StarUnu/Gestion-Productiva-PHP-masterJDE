<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                                
                    <div class="content">
                        <div class="row">
                            <div class="col-md-1"></div>
                            <div class="col-md-2">
                                <h4 class="title">Operaciones</h4>    
                            </div>
                            <div class="col-md-4">
                                <div class="form-group" <?php echo ($_SESSION['TipoUsuario']==0) ? 'hidden' : ''?>>
                                    <label>Unidad Productiva</label>
                                    <select name="Unidad" id="Unidad" class="form-control">
                                        <?php
                                            if ($_SESSION['TipoUsuario']==0) {
                                                echo "<option selected value='".$_SESSION['Unidad_Id']."'>".$_SESSION['UnidadNombre']."</option>";
                                            } else { ?>
                                                <option selected value="NoUnidad">----Todos----</option>
                                                <?php foreach($this->modelUnidadProductiva->getAll() as $r): ?>
                                                    <option <?php echo ($operacion->Unidad_Id==$r->Id) ? 'selected' : '' ?> value="<?php echo $r->Id?>" ><?php echo $r->Nombre;?></option>
                                                <?php endforeach; ?>
                                        <?php
                                            }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Periodo</label>
                                    <input type="number" id="Periodo" class="form-control" min="1900" max="2099" size="4" step="1" value="<?php echo date('Y');?>" />
                                </div>
                            </div>
                            <div class="col-md-2">
                                <form method="post" action="<?php echo BASE_URL;?>Operaciones/Crud">
                                    <button type="submit" class="btn btn-info btn-fill pull-right">Agregar Operacion</button>          
                                </form>
                            </div>
                        </div>

                        <div id="target-search">
                            <div class="content table-responsive table-full-width">
                                <table class="table table-hover table-striped">
                                    <thead>
                                        <th>Unidad</th>
                                        <th>Tipo</th>
                                        <th>Monto</th>
                                        <th>Mes</th>
                                        <th>Periodo</th>
                                        <th></th>
                                    </thead>
                                    <tbody id="target-content">
                                        <?php foreach($this->model->Listar($startFrom) as $r): ?>
                                            <tr>
                                                <td><?php echo $this->model->getUnidadById($r->Unidad_Id); ?></td>
                                                <td><?php echo $r->Tipo==1 ? "Ingreso" : "Egreso" ; ?></td>
                                                <td><?php echo "S/.".($r->Monto==null ? "0" : $r->Monto); ?></td>
                                                <td><?php echo $this->getMesNombre($r->Mes); ?></td>
                                                <td><?php echo $r->Periodo; ?></td>

                                                <td class="cell-actions">
                                                    <div class="btn-group">
                                                        <a class="btn btn-xs btn-warning buttonCrud" href="<?php echo BASE_URL; ?>Operaciones/Crud/<?php echo $r->Id; ?>"><span class="glyphicon glyphicon-pencil"></span></a>
                                                        <a <?php echo ($r->Unidad_Id != $_SESSION['Unidad_Id'] && !EDICION_GENERAL_PERMITIDA) ? 'style="display: none;"' : '' ?> onclick="javascript:return confirm('¿Seguro de eliminar este registro?');" class="btn btn-xs btn-danger buttonCrud" href="<?php echo BASE_URL; ?>Operaciones/Eliminar/<?php echo $r->Id; ?>"><span class="glyphicon glyphicon-trash"></span></a>
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
                                            <li class='active'  id="<?php echo $i;?>"><a href='<?php echo BASE_URL;?>Operaciones/Paginacion/Pagina/<?php echo $i;?>'><?php echo $i;?></a></li> 
                                            <?php else:?>
                                            <li id="<?php echo $i;?>"><a href='<?php echo BASE_URL;?>Operaciones/Paginacion/Pagina/<?php echo $i;?>'><?php echo $i;?></a></li>
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

$('#Unidad').bind('change', BuscarPorUnidadYPeriodo);
$('#Periodo').bind('input', BuscarPorUnidadYPeriodo);

function BuscarPorUnidadYPeriodo()
{
   var base = "<?php echo BASE_URL;?>";
   var periodo = $('#Periodo').val();  
   var unidad = $('#Unidad').val();
    $.ajax({  
         url: base + "Operaciones/Buscar/",
         method:"post",  
         data:{periodo:periodo, unidad:unidad},  
         dataType:"text",  
         success:function(data)  
         {  
            $('#target-search').html(data);  
            $('.pagination').pagination({
                items: $('#totalRecords').val(),
                itemsOnPage: <?php echo resultsPerPage;?>,
                cssStyle: 'light-theme',
                currentPage : 1,
                onPageClick : function(pageNumber) {
                    $("#target-content").html('Cargando...');
                    var base = "<?php echo BASE_URL;?>";
                    $("#target-content").load(base + "Operaciones/Paginacion/Pagina/" + pageNumber + "/Unidad/" + unidad + "/Busqueda/" + periodo);
                }
            });
         }  
    }); 
}

$(document).ready(function(){

    $('.pagination').pagination({
        items: <?php echo $totalRecords;?>,
        itemsOnPage: <?php echo resultsPerPage;?>,
        cssStyle: 'light-theme',
        currentPage : 1,
        onPageClick : function(pageNumber) {
            $("#target-content").html('Cargando...');
            var base = "<?php echo BASE_URL;?>";
            $("#target-content").load(base + "Operaciones/Paginacion/Pagina/" + pageNumber);
        }
    });
});
</script>