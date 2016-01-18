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
			
				$queryform = "SELECT name, id
									FROM custom_form, custom_form_has_property 
									WHERE custom_form_has_property.custom_form_id = custom_form.id
									GROUP BY name";
				$resultform = mysqli_query($link, $queryform);				
?>				
				<table/* class="mytable"*/>
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
									FROM property, custom_form_has_property
									WHERE property.id = custom_form_has_property.property_id
									AND custom_form_has_property.custom_form_id = ".$linha['id']."
									GROUP BY id";
					$resultproper = mysqli_query($link, $queryproper);
					$num_rows_property = mysqli_num_rows($rresultproper);

?>					
					
					<tr>
					
					<td colspan = "1" rowspan = "<?php echo $num_rows_property; ?>">
					
					<?php echo '<a href="gestao-de-formularios?estado=editar_form&id='.$linha['id'].'">
					
					'.$linha['name'].' </a>'; ?>					
					</td>					
<?php					
					while($linhapropriedade = mysqli_fetch_array($result_property))
					{
?>
						<td><?php echo $linhapropriedade['id']; ?></td>
						<td><?php echo $linhapropriedade['name']; ?></td>
						<td><?php echo $linhapropriedade['value_type']; ?></td>
						<td><?php echo $linhapropriedade['form_field_name']; ?></td>
						<td><?php echo $linhapropriedade['form_field_type']; ?></td>
<?php					
						if($linhapropriedade['unit_type_id'] != ' ' )
						{
							$queryunidade = "SELECT name
												FROM prop_unit_type
												WHERE property.unit_type_id=prop_unit_type.id and  property.unit_type_id=".$linhapropriedade['unit_type_id']."";
							$result_unit_type = mysqli_query($link, $query_unit_type);
							$linhaunidade = mysqli_fetch_array($result_unit_type);
?>								
							<td> <?php echo $linhaunidade['name']; ?> </td>
<?php	
						}						
						else
						{
?>						
							<td> - </td>
<?php							
						}
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
		}
	}
?>