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
			
				$queryform = "SELECT cf.name AS 'cf.name', cf.id AS 'cf.id'
									FROM custom_form as cf, custom_form_has_property AS cfhp 
									WHERE cfhp.custom_form_id = cf.id
									GROUP BY name";
				$resultform = mysqli_query($link, $queryform);				
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
				while($linha = mysqli_fetch_array($resultform))
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
												WHERE property.unit_type_id=prop_unit_type.id and  property.unit_type_id=".$linhapropriedade['unit_type_id']."";
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
			$query_unidades="SELECT put.name AS 'put.name'
			FROM prop_unit_type AS put, propert AS p
			WHERE p.unit_type_id=put.id and  p.unit_type_id=".$linhapropriedades['unit_type_id']."";
			$resultado_unidades = mysqli_query($link, $query_unidades);
			$array_unidades = mysqli_fetch_array($resultado_unidades);
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
	
			<p>	
			<input type="submit" value="Criar formulario">
			</p>
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
				<p>Tem de escolher o nome do formulário.</p>
<?php			
				back();
			}
			#Validações server side
			elseif(is_null($check))
			{
?>			
				<p>Tem de escolher pelo menos uma propriedade.</p>
<?php	
				back();				
			}
			else
			{	
				
				$inserir = sprintf('INSERT INTO custom_form ('name') VALUES("$form_name");', mysqli_real_escape_string($form_name));
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
		elseif($_REQUEST['estado'] == "editar_form")
		{