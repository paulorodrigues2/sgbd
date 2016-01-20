<?php	
	require_once("custom/php/common.php");
	
	if(!is_user_logged_in() || !current_user_can("manage_custom_forms"))
	{
?>
		<p>Não tem autorização para aceder a esta página. Tem de efetuar <a href=><?php wp_loginout("wordpress/gestao-de-formularios")?> </a>.</p>	
<?php
	
	}
	else
	{
	
		if(!isset($_REQUEST['estado']) || $_REQUEST['estado'] == "")
		{
?>		
			<h3><b>Gestão de formulários customizados</b></h3>
<?php		
			$query_cform = "SELECT * FROM custom_form";
			$result_cform = mysqli_query($link, $query_cform);
			
			if (mysqli_num_rows($result_cform) == 0)
			{
				echo "Não há formulários customizados";
			}
			else
			{
			
				$queryformulario = "SELECT cf.name AS 'cf.name', cf.id AS 'cf.id'
									FROM custom_form as cf, custom_form_has_property AS cfhp 
									WHERE cfhp.custom_form_id = cf.id
									GROUP BY name";
				$resultadoformulario = mysqli_query($link, $queryformulario);				
?>				
				<table class="mytable">
				<thead>
				<tr>
				<th>formulario</th>
				<th>id</th>
				<th>propriedade</th>
				<th>tipo de valor</th>
				<th>nome do campo no formulario</th>
				<th>tipo do campo no formulario</th>
				<th>tipo de unidade</th>
				<th>ordem do campo no formulário</th>
				<th>tamanho do campo no formulario</th>
				<th>obrigatorio</th>
				<th>estado</th>
				<th>acao</th>
				</tr>
				</thead>
				
				<tbody>
	
<?php	
				while($linha = mysqli_fetch_array($resultadoformulario))
				{
					//Seleciona id, nome, tipo de valor, nome do formulário e o tipo de formulário.
					$queryproper = "SELECT property.*
									FROM property AS p, custom_form_has_property AS cfhp
									WHERE p.id = cfhp.property_id
									AND cfhp.custom_form_id = ".$linha['cf.id']."
									GROUP BY id";
					$resultproper = mysqli_query($link, $queryproper);
					$num_rows_property = mysqli_num_rows($rresultproper);

?>					
					
					<tr>
					
					<td colspan = "1" rowspan = "<?php echo $num_rows_property; ?>">
					
					<?php echo '<a href="gestao-de-formularios?estado=editar_form&id='.$linha['c.id'].'">
					
					'.$linha['cf.name'].' </a>'; ?>					
					</td>					
<?php					
					while($linhapropriedade = mysqli_fetch_array($result_property))
					{
						#Definimos o id da propriedade, nome da propriedade, tipo de valor, nome do formulário e tipo de formulário;
?>							

						<td><?php echo $linhapropriedade['id']; ?></td>
						<td><?php echo $linhapropriedade['name']; ?></td>
						<td><?php echo $linhapropriedade['value_type']; ?></td>
						<td><?php echo $linhapropriedade['form_field_name']; ?></td>
						<td><?php echo $linhapropriedade['form_field_type']; ?></td>
<?php					
					
						if($linhapropriedade['unit_type_id'] != ' ' )
						{
							$queryunidade = "SELECT put.name AS 'put.name'
												FROM prop_unit_type AS put, property AS p
												WHERE p.unit_type_id=put.id and  p.unit_type_id=".$linhapropriedade['unit_type_id']."";
							$result_unit_type = mysqli_query($link, $query_unit_type);
							$linhaunidade = mysqli_fetch_array($result_unit_type);
							#Tipo de unidade da propriedade
?>								
							<td> <?php echo $linhaunidade['put.name']; ?> </td>
<?php	
						}						
						else
						{
?>						
							<td>  </td>
<?php							
						} #Ordem do formulário 
?>				
								
						<td> <?php echo $linhapropriedade['form_field_order']; ?> </td>
<?php						
						if($linhapropriedade['form_field_size'] != null)
						{
?>
							<td> <?php echo $linhapropriedade['form_field_size']; ?> </td>
<?php							
						}
						else
						{
?>
							<td> - </td>
<?php						
						}	
						#Caso for obrigatorio 
						if($linhapropriedade['mandatory'] == 1)
						{
?>
							<td> sim </td>
<?php
						}
						else
						{
?>
							<td> não </td>
<?php
						}
						
						if($linhapropriedade['state']== "active")
						{
?>						
							<td> activo </td>
							<td> [editar]<br>[desactivar]</td>						 
<?php
						}
						else
						{
?>
							<td> inactivo </td>
							<td> [editar]<br>[activar]</td>
<?php
						}
?>	
						</tr>
<?php						
	
					}					
				}
?>				
				</tbody>
				</table>
<?php								
			}
?>
			<h3><b>Gestão de formulários customizados - Introducao</b></h3>
			
			<form name="gestao-de-formularios" method="POST">	
			<fieldset>
			
			<input type="hidden" name="estado" value="inserir"> 
			<p>
			<label><b>Nome do Formulário:</b></label>
			<input type="text" name="form_name">
			</p>
			
			<table>
			<thead>
			<tr>
			<th>Componente</th>
			<th>ID</th>
			<th>propriedade</th>
			<th>tipo de valor</th>
			<th>nome do campo no formulario</th>
			<th>tipo do campo no formulario</th>
			<th>tipo de unidade</th>
			<th>ordem do campo no formulario</th>
			<th>tamanho do campo no formulario</th>
			<th>obrigatorio</th>
			<th>estado</th>
			<th>escolher</th>
			<th>ordem</th>
			</tr>
			</thead>
					
			<tbody>
<?php

		$query_componentes="SELECT component.name, component.id FROM component";
		$result_componentes=mysqli_query($link, $query_componentes);
		while($linhacomponente= mysqli_fetch_array($result_componentes)
	{
		$query_propriedades="SELECT property.* 
		FROM property, component
		WHERE property.component_id=".$linhacomponente."";
		$result_propriedades= mysqli_query($link, $query_propriedades);
		$num_rows_propriedades=mysqli_num_rows($result_propriedades);
?>
		<tr>
		<td colspan="1" rowspan="<?php echo $num_rows_propriedades?>" <?php echo $linhacomponente['name'];?></td>
<?php
		while($linhapropriedades = mysqli_fetch_array($result_propriedades)
		{
?>
		<td> <?php echo $linhapropriedades['name'];?></td>
		<td> <?php echo $linhapropriedades['value_type'];?></td>
		<td> <?php echo $linhapropriedades['form_field_name'];?></td>
		<td> <?php echo $linhapropriedades['form_field_type'];?></td>
		<td> <?php echo $linhapropriedades['form_field_type'];?></td>
<?php					
		if($linhapropriedades['unit_type_id'] != ' ' )
	{
			$query_unidades2="SELECT put.name AS 'put.name'
			FROM prop_unit_type AS put, propert AS p
			WHERE p.unit_type_id=put.id and  p.unit_type_id=".$linhapropriedades['unit_type_id']."";
			$resultado_unidades2 = mysqli_query($link, $query_unidades);
			$array_unidades2 = mysqli_fetch_array($resultado_unidades);
?>								
						<td> <?php echo $array_unit_type2['put.name']; ?> </td>
<?php						
	}
					else
					{
?>						
						<td> "NULL"; </td>
<?php												
					}
?>				
					<td> <?php echo $linhapropriedades['form_field_order']; ?> </td>
<?php
					if($linhapropriedades['form_field_size'] != null)
					{
?>
						<td> <?php echo $linhapropriedades['form_field_size']; ?> </td>
<?php							
					}
					else
					{
?>
						<td> "NULL"; </td>
<?php						
					}
					
					if($linhapropriedades['mandatory'] == 1)
					{
?>
						<td> sim </td>
<?php
					}
					else
					{
?>
						<td> não </td>
<?php
					}
					
					if($linhapropriedades['state']== "active")
					{
?>						
						<td> activo </td>					 
<?php
					}
					else
					{
?>
						<td> inactivo </td>
<?php
					}
?>					<td><input type="checkbox" name="checkbox" value="<?php echo $linhapropriedades['id'];?>"></td>
					<td><input type="text" name="order by_<?php /*ORDENA propriedades por id*/ echo $linhapropriedades['id'];?>"></td> 
				
					</tr>
<?php
				
			}
		}
?>			
			</tbody>
			</table>
	
			<input type="submit" value="Criar formulario">
			</fieldset>	
			</form>			
<?php			

		}
		elseif($_REQUEST['estado']=='inserir'){

		
		{
			$form_name = $_REQUEST['form_name'];
			$check= $_REQUEST['checkbox'];
			
			#Validações server side
			if(empty($form_name))
			{
?>
				<p>Tem de escolher o nome do formulário pretendido.</p>
<?php			
				back();
			}
			#Validações server side
			elseif(is_null($check))
			{
?>			
				<p>Tem de escolher pelo menos uma propriedade pretendida.</p>
<?php	
				back();				
			}
			else
			{	
				
				$inserir = "INSERT INTO custom_form ('name') VALUES("$form_name")";
				$result_inserir = mysqli_query($link, $inserir);
				$formulario_id= mysqli_insert_id(); //Gera o último id que foi inserido
				
				
				foreach($check as $chave => $valor)  //percorre o array $check sendo $chave o indice do array e $valor os dados associados a esse indice
				{
					#Define a ordem do formulário que foi colocado no check 
					$order = $_REQUEST['order_by'.$valor];
					if(empty($order))
					{
						$inserir_id_propriedade_id = sprintf(' INSERT INTO custom_form_has_property (`custom_form_id`,`property_id`) 
																	VALUES ("$formulario_id", "$valor"); ',
																	mysql_real_escape_string($formulario_id),
																	mysql_real_escape_string($valor));
																	
						$resultado_inserir_id_propriedade_id = mysqli_query($link, $inserir_id_propriedade_id);
					}
					else
					{
						$inserir_formulario = sprintf(' INSERT INTO custom_form_has_property ('custom_form_id', 'property_id', 'field_order') 
																	VALUES ('$formulario_id', '$valor','$order'); ',
																	mysql_real_escape_string($formulario_id),
																	mysql_real_escape_string($valor),
																	mysql_real_escape_string($order));
																	
						$result_inserir_formulario = mysqli_query($link, $inserir_formulario);	
						
					}					
				}
				
				if($resultado_inserir_id_propriedade_id || $result_inserir_formulario){
				//Transição feita com sucesso.
				mysqli_commit($link);
				
?>					
					<p>Inseriu os dados de novo formulário customizado com sucesso.</p>
					<p>Clique em <a href="gestao-de-formularios">Continuar</a> para avancar.</p><br>
<?php					
					
			}
				else
				{
?>			
					<p>Ocorreu um erro ao inserir os dados</p>
<?php					mysqli_query('ROLLBACK');
						back();
				}
      		}
		}		
		elseif($_REQUEST['estado'] == "editar_form")
		{
?>
<?php	$custom_id = $_GET['id']; // Tem o id do formulário pretendido
			
			$query_custom = "SELECT name FROM custom_form WHERE id = $custom_id";
			$result_custom = mysqli_query($link, $query_custom);
			$array_$result_custom = mysqli_fetch_array($result_custom);
			$array_$result_custom = mysqli_fetch_array($result_custom);
?>			
			<form name="gestao-de-formularios-editar" method="POST">	
			<fieldset>
			
			<input type="hidden" name="estado" value="atualizar_form_custom"> 
			<p>
			<label><b>Nome do Formulário:</b></label>
			<input type="text" name="form_name" value="<?php echo $array_$result_custom['name']; ?>">
			</p>
			
			<table>
			<thead>
			<tr>
			<th>Componente</th>
			<th>ID</th>
			<th>propriedade</th>
			<th>tipo de valor</th>
			<th>nome do campo no formulario</th>
			<th>tipo do campo no formulario</th>
			<th>tipo de unidade</th>
			<th>ordem do campo no formulario</th>
			<th>tamanho do campo no formulario</th>
			<th>obrigatorio</th>
			<th>estado</th>
			<th>escolher</th>
			<th>ordem</th>
			</tr>
			</thead>
					
			<tbody>
<?php
		$query_componentes="SELECT component.name, component.id FROM component";
		$result_componentes=mysqli_query($link, $query_componentes);
		while($linhacomponente= mysqli_fetch_array($result_componentes)
		{

		$query_propriedades="SELECT property.* 
		FROM property, component
		WHERE property.component_id=".$linhacomponente."";
		$result_propriedades= mysqli_query($link, $query_propriedades);
		$num_rows_propriedades=mysqli_num_rows($result_propriedades);
?>
		<tr>
		<td colspan="1" rowspan="<?php echo $num_rows_propriedades?>" <?php echo $linhacomponente['name'];?></td>
<?php
		while($linhapropriedades = mysqli_fetch_array($result_propriedades)
		{
?>
		<td> <?php echo $linhapropriedades['name'];?></td>
		<td> <?php echo $linhapropriedades['value_type'];?></td>
		<td> <?php echo $linhapropriedades['form_field_name'];?></td>
		<td> <?php echo $linhapropriedades['form_field_type'];?></td>
		<td> <?php echo $linhapropriedades['form_field_type'];?></td>
<?php						
	if($linhapropriedades['unit_type_id'] != ' ' )
						{
			$query_unidades2="SELECT put.name AS 'put.name'
			FROM prop_unit_type AS put, propert AS p
			WHERE p.unit_type_id=put.id and  p.unit_type_id=".$linhapropriedades['unit_type_id']."";
			$resultado_unidades2 = mysqli_query($link, $query_unidades);
			$array_unidades2 = mysqli_fetch_array($resultado_unidades);
?>								
						<td> <?php echo $array_unit_type2['put.name']; ?> </td>
<?php						
					}
					else
					{
?>						
						<td> "NULL"; </td>
<?php												
					}
?>				
					<td> <?php echo $linhapropriedades['form_field_order']; ?> </td>
<?php
					if($linhapropriedades['form_field_size'] != null)
					{
?>
						<td> <?php echo $linhapropriedades['form_field_size']; ?> </td>
<?php							
					}
					else
					{
?>
						<td> "NULL"; </td>
<?php						
					}
					
					if($linhapropriedades['mandatory'] == 1)
					{
?>
						<td> sim </td>
<?php
					}
					else
					{
?>
						<td> não </td>
<?php
					}
					
					if($linhapropriedades['state']== "active")
					{
?>						
						<td> activo </td>					 
<?php
					}
					else
					{
?>
						<td> inactivo </td>
<?php
					}
					$query_formulario_atualizavel="SELECT cfhp.property_id as 'cfhp.property_id', cfhp.custom_form_id AS 'cfhp.custom_form_id', p.field_order AS 'p.field_order'
					FROM property AS p, custom_form_has_property as cfhp, custom_form as cf
					WHERE cfhp.custom_form_id=cf.id and cfhp.property_id=p.id and p.id=".$linhapropriedades['id']."";
					$resultado_formulario_atualizavel=mysqli_query($link, $query_formulario_atualizavel);
					$linha_associativa=mysqli_fetch_array($resultado_formulario_atualizavel);
					if($linhapropriedades['id'] == $linha_associativa['property_id'])
					{
?>						
						<td><input type="checkbox" name="check[]" value="<?php echo $linhapropriedades['id'];?>" CHECKED></td>
						<td><input type="text" name="order_<?php echo $linhapropriedades['id'];?>" value="<?php echo $linha_associativa['p.field_order']; ?>"></td>					
<?php						
					}
					else
					{
?>						
						<td><input type="checkbox" name="check[]" value="<?php echo $linhapropriedades['id'];?>"></td>
						<td><input type="text" name="order_<?php echo $linhapropriedades['id'];?>"></td>					
<?php							
					}
?>									
					</tr>
<?php
				}
				
			}
?>			
			</tbody>
			</table>
	
			<p>	
			<input type="submit" value="Atualizar formulário">
			</p>
			</fieldset>	
			</form>			

<?php			
		}
		else if($_REQUEST['estado'] == "atualizar_form_custom")
		{
			//Get id do formulário
			$custom_form_id = $_GET['id'];
			//Nome do formulário
			$nome_formulario = $_REQUEST['form_name'];
			//Get valor do checkbox
			$check= $_REQUEST['check'];
			
			if(empty($nome_formulario))
			{
				//Preenche o nome do formulário
?>
				<p>Tem de escolher o nome do formulário.</p>
<?php			
				back();
			}
				// Se o valor da checkbox 
			elseif(is_null($check))
			{
?>			
				<p>Tem de escolher pelo menos uma propriedade.</p>
<?php	
				back();				
			}
			else
			{	
				//Faço a atualização do valor do formulário
				$update_custom_form= " UPDATE `custom_form` SET `name` = '$nome_formulario' WHERE `id` = '$custom_form_id'";
											
											
				$resultado_update = mysqli_query($update_custom_form);
				// Apago a coluna que foi actualizada
				$apagar_custom_form = "DELETE FROM `custom_form_has_property` WHERE `custom_form_id` = "$custom_form_id" ";
													
				$resultado_apagar_custom_form = mysqli_query($link, $apagar_custom_form); 
				
				//Vai percorrer o array da checkbox e verifica o indice do valor escolhido 
				foreach($check as $chave => $valor)  //percorre o array $check sendo $chave o indice do array e $valor os dados associados a esse indice
				{
					//Busca o valor do escolhido 
					$order = $_REQUEST['order_by'.$valor];
					if(empty($order)) // Se o valor de ordenação não foi inserido então inserimos o id do formulario e a propriedade do id
					{											
						$update_property_has_no_order = "INSERT INTO custom_form_has_property (`custom_form_id`,`property_id`) 
																	VALUES ("$custom_form_id", "$valor")";
																	
																	
						$result_update_property_has_no_order = mysqli_query($link, $update_property_has_no_order);
					}
					else
					{
						$update_has_order = "INSERT INTO custom_form_has_property (`custom_form_id`, `property_id`, `field_order`) 
																	VALUES ("$custom_form_id", "$valor","$order")";
																	
						$result_$update_has_order = mysqli_query($link, $update_has_order);																	
					}								
				}
				#Se o formulário foi actualizado a sua ordem, foi actualizado sem a sua ordem  e se o resultado é apagar formulário
				if($result_update_property_has_no_order && $result_$update_has_order && $resultado_apagar_custom_form)
				{			
					mysqli_commit($link);
?>
					<p> Os dados do formulário customizado foram atualizados com sucesso.</p>
					<p>Clique em <a href="gestao-de-formularios">Continuar</a> para avancar.</p>
<?php										
				}
				else{
					
					?>			
					<p>Ocorreu um erro ao inserir os dados</p>
<?php					mysqli_query('ROLLBACK');
				back();
					}	
			}
		}					
	}
}	
?>