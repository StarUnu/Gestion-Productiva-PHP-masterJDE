
<div class="content">
	<div class="container-fluid">
		<div class="row">
		<div class="col-md-12">
			<div class="card">
				<ol class="breadcrumb">
					<li> <a href="<?php echo BASE_URL;?>InventarioCosecha/"> 
						Inventario de Cosecha </a>
						</li>
					<li class="active"><?php echo $Inventariocosecha->Id != null ? $Inventariocosecha->Fechaingreso : 'Nuevo Registro'; ?></li>
							</div>
						</div>
						<div class="row">
						<div class="form-group">
							<div class="col-md-6">

							<input type="hidden" name="Id" value ="<?php echo $Inventariocosecha->Id;?>" 	/>
								<label> Fecha de Ingreso 	</label>
								<input type="date" class="form-control" placeholder="fecha" name="Fecha_Ingreso" value="<?php echo $Inventariocosecha->Fechaingreso;?>" required>
							</div>
							<div class="col-md-6">
								<!-- <textarea form="a" name="a" cols="50" rows="2"></textarea> -->
								<label class="text"> Descripcion	</label>
								<input type="text" class="form-control" placeholder="Ingrese aqui" name="Descripcion" value="<?php echo $Inventariocosecha->Descripcion;?>" required>
							</div>
						</div>
						</div>
							<div class="row">
							<div class="col-md-6">
								<!-- <textarea form="a" name="a" cols="50" rows="2"></textarea> -->
								<label class="text"> Observaciones	</label>
								<input type="text" class="form-control" placeholder="Ingrese aqui" name="Observaciones" value="<?php echo $Inventariocosecha->Observaciones;?>">
							</div>
							<div class="col-md-6">
							<button type="submit" id="btnSubmit" class="btn btn-info btn-fill pull-right">Guardar</button>
							</div>

							</div>
							
						
						

						<div class="card">
														<div class="content">
													<a id ="agregarbien" > <h4 style ="text-align:center" ;> Nuevo Bien </h4> </a>
													<div class="row">

														<div class="col-md-5">
															<!--<div class="form-group">-->
															<label >Descripcion</label>
															<input type="text" id = "Descripcion" placeholder="dequetrata" class=" form-control">
															<input type="hidden" id="Idbien" >
														</div>


														<div class="col-md-5">
															<label > Tipo material</label>
															<?php $cont=0; ?>
															<select class="form-control" name = "Idmaterial2" id="Idmaterial">
																<?php 
																	foreach ($this->modeltipomaterial->Listar($startfrom ) as $r) {?>
																		<option value="<?php echo $cont; ?>" >
																			<?php echo $r->Descripcion;?>
																		</option>
																		<?php $cont++ ?>

																<?php
																	}
																?>

															</select>
														</div>

													</div>

													<button type="button" id="btnguardar" class="btn btn-info btn-fill pull-right">Guardar</button>
													<h4>Bienes</h4>
													<!--falta modificar esto un poco-->
													<div class="content table-responsive table-full-width">

															<table id="tableBienes" class="table table-hover table-striped">
																	<thead><!--esto tambien da textura-->
																			<th>Tipo Material</th>
																			<th>Descripcion</th>
																	</thead>
																	<tbody id="target-content">
																		
																	<?php foreach($this->modelbien->obtenerbienes2($Inventariobienes->Id) as $r):?>
																		<tr>
																			<td> <?php echo $this->modeltipomaterial->obtenertipomaterialid($r->TipoMaterial_Id)->Descripcion ; ?> </td>
																			<td> <?php echo $r->Descripcion; ?> </td>

																				<td class=" td-actions text-right"><!--</td> class="cell-actions">-->
																				
																						<button type="button" rel="tooltip" title="Editar Detalle" class="btnEditDetalle btn btn-info btn-simple btn-xs">
																						<i class="fa fa-edit"></i>
																						</button>

																						<button type="button" rel="tooltip" title="Eliminar" class="btnDeleteDetalle btn btn-danger btn-simple btn-xs">
																						<i class="fa fa-times"></i>
																						</button>
																						</td>

																						<input type="hidden" name="IdBien1[]"  value="<?php echo  $r->Id;?>">

																						<input type="hidden" name="TipoMaterial1[]" value="<?php echo $r->TipoMaterial_Id;?> ">

																						<input type="hidden" name="Descripcion1[]" value="<?php echo    $r->Descripcion;?>">
																		</tr>
																	<?php endforeach ?>
																	</tbody>
															</table>
													</div>

													</div>
												</div>

					</form>
				</div>	
				</ol>
			</div>
			</div>					
		</div>
	</div>
</div>
