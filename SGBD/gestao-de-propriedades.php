<?php	
	require_once("custom/php/common.php");
	
	if(!is_user_logged_in() || !current_user_can('manage_properties'))
	{
?>
	    <p>Não tem autorização para aceder a esta página. Tem de efetuar <a href=><?php wp_loginout("wordpress/gestao-de-componentes")?> </a>.</p>
<?php
	}
	else
	{
		if(!isset($_REQUEST['state']) || $_REQUEST['state'] == "") 
		{
			$query_proper = "SELECT * FROM property";
			$result_proper = mysqli_query($link, $query_proper);
			
			if (mysqli_num_rows($result_proper) == 0)
			{
				echo "Não há propiedades especificadas";
			}
			else
			{
				while($row_comp = mysqli_fetch_array($result_proper)){
				//Query de componentes agroupados pelo nome
				$query_comp = " SELECT component.id, component.name
								FROM component, property, comp_type
								WHERE property.component_id=component.id and component.id=".$row_comp["id"]."";
				$result_comp = mysqli_query($link, query_comp);
?>								
				<table>
				<thead>
				<tr>
				<th>componente</th>
				<th>id</th>
				<th>propriedade</th>
				<th>tipo de valor</th>
				<th>nome do formulario</th>
				<th>tipo de formulario</th>
				<th>tipo de unidade</th>
				<th>ordem do formulário</th>
				<th>tamanho do formulario</th>
				<th>obrigatorio</th>
				<th>estado</th>
				</tr></thead>
				
				<tbody>
<?php
				while($row_comp = mysqli_fetch_array($result_comp))
				{
					$query_prop = " SELECT property.*
									FROM property, component
									WHERE property.component_id = ".$row_comp["id"]."
									GROUP BY property.id";
					$result_prop = mysqli_query($link,$query_prop);
					$num_prop = mysqli_num_rows($result_prop);
				
?>
					<tr><td colspan ="1" rowspan="<?php echo $num_prop; ?>" > <?php echo $row_comp['name']; ?> </td> 
<?php

					while($row_prop = mysqli_fetch_array($result_prop))
					{    //Agrupar valores da propriedade
?> 							
						<td> <?php echo $row_prop['id']; ?> </td>
						<td> <?php echo $row_prop['name']; ?> </td>
						<td> <?php echo $row_prop['value_type']; ?> </td>
						<td> <?php echo $row_prop['form_field_name']; ?> </td>
						<td> <?php echo $row_prop['form_field_type']; ?> </td>
<?php
						if($row_prop['unit_type_id'] != null)
						{
							$prop_unit_type = "SELECT name
												FROM prop_unit_type
												WHERE ".$row_prop['unit_type_id']." = id";
							$query_prop_unit_type = mysqli_query($prop_unit_type);
?>								
							<td> <?php echo $query_prop_unit_type; ?> </td>
<?php	
						}
						
						else
						{
?>						
							<td> - </td>
<?php							
						}
?>
						<td> <?php echo $row_prop['form_field_order']; ?> </td>
<?php 						
						if($row_prop['mandatory'] == 1)
						{
?>
						<td> sim</td>
<?php
						}
						else
						{
?>
						<td> não </td>
<?php
						}
						if($row_prop['state']== "active")
						{
?>						
						<td> activo </td>
						<td> [editar]<br>[desactivar]</td>
						</tr> 
<?php
						}
						else
						{
?>
						<td> inactivo </td>
						<td> [editar]<br>[activar]</td>
						</tr>
<?php
						}
					}	
				}
?>
				</tbody>
				</table>
<?php
				}
			}
		
			//inserção dos dados
?>		
			<br>
			<h3>Gestão de propriedades - introdução</h3>
			<br>
			<div style = "color:#000000">
			<form name = "gestão de propriedades" method = "POST" action = "">
			<fieldset class = "fieldset pages">
			<legend><b><u> Introduzir propriedades</b></u></legend>
			<br>
			<label class = "label_50px"><b>Nome:</b></label>
				<input class="input_pages" type = "text" name = "proper_name" ><br><br>
		
			<label><b>Tipo de valor</b></label><br><br>
	
<?php				
			
			$enum_array = getEnumValues("property", "value_type");
			$check = 0;
			foreach($enum_array as $artigo)
			{
				if($check == 0)
				{
					echo '<input class = "radio" type = "radio" name = "value_type" value = "'.$artigo.'" checked>';
					$check = 1;
				}else
					echo '<input class = "radio" type = "radio" name = "value_type" value ="'.$artigo.'">';
					echo '<label class = "label_radio" for = "'.$artigo.'" > '.$artigo.' </label><br>';
				
			}
		
				
?>	
			<br><br>	
			<label><b>Componente</b></label><br><br>
		
			<select name = "component_id">
<?php
			$query_component = "SELECT name, id FROM component";
			$resultado_component = mysqli_query($query_component);					
		
			while($row = mysqli_fetch_array($resultado_component))
			{
			echo '<option value = "'.$row['id'].'"> "'.$row['name'].'"</option>';
			}
?>		
			</select><br><br>
	
			<label><b>Tipo do campo do formulário</b></label><br><br>
		
<?php		
			$enum_array = getEnumValues("property", "form_field_type");
			$check02 = 0;
			foreach($enum_array as $artigo1)
			{
				if($check02 == 0)
				{
					echo '<input class = "radio" type = "radio" name = "form_field_type" value = "'.$artigo1.'" checked>';
					$check02 = 1;
				}
				else
				echo '<input class = "radio" type = "radio" name = "form_field_type" value = "'.$artigo1.'">';
				echo '<label class = "label_radio" for = "'.$artigo1.'"> '.$artigo1.'</label><br>';
			}
?>	
			<br><br>
			<label><b>Tipo de unidade</b></label><br><br>
			<select name = "unit_type_id">
	
<?php		
			$query_unidade = "SELECT name, id FROM prop_unit_type";
			$result_unidade = mysqli_query($query_unidade);
		
			echo '<option value = ""></option>';
			while($row = mysqli_fetch_array($result_unidade))
			{
				echo '<option value = ""'.$row['id'].'"">'.$row['name'].'</option>';
			}
?>
			</select><br><br>
	
			<label><b>Ordem do campo no formulário</b></label><br><br>
			<input class = "input_pages" type = "text" name = "form_field_order" required>
			<br><br>
		
			<label class = "label_150px"><b>Obrigatorio</b></label><br><br>
	
			<input type = "hidden" name = "estado" value = "inserir">
			<input class = "button_150px" type = "submit" value = "Inserir propriedades"><br><br>
	
			</fieldset>
			</form>
			</div>
<?php	
		
		}
		else if($_REQUEST['state'] == "inserir")
		{
			$form_field_order = $_REQUEST['form_field_order'];
			if(is_numeric($form_field_order) && is_int(($form_field_order + 0)))
			{
?>		
				<h3>Gestão de propriedades - inserção</h3>
<?php		
				$proper_name = $_REQUEST['proper_name'];
				$prop_value_type = $_REQUEST['value_type'];
				$form_field_name = "something";
				
				//$form_field_name = $_REQUEST['form_field_name'];
				//$new_form_field_name = preg_replace('/[â-z0-9_ ]/i', '_', $form_field_name);
				
				$form_field_type = $_REQUEST['form_field_type'];
				$mandatory_prop = $_REQUEST['mandatory_prop'];
				$component_id = $_REQUEST['component_id'];
				$unit_type_id = $_REQUEST['unit_type_id'];
			
			
				//start of transaction
				mysqli_query('START TRANSACTION');
				if($unit_type_id == "") // da erro qdo unit_type_id é null
				{
					$inserir = sprintf('INSERT INTO `property` (`name`, `value_type`, `form_field_name`, `form_field_type`, `form_field_order`, `mandatory` ,`component_id`,`unit_type_id`) 
										VALUES ("%s", "%s", "%s", "%s", "%s", "%s", "%s", "%s");',
					mysqli_real_escape_string($proper_name),
					mysqli_real_escape_string($prop_value_type),
					mysqli_real_escape_string($form_field_name),
					mysqli_real_escape_string($form_field_type),
					mysqli_real_escape_string($form_field_order),
					mysqli_real_escape_string($mandatory_prop),
					mysqli_real_escape_string($component_id),
					mysqli_real_escape_string($unit_type_id));					
				}					
?>
						<p>Inseriu os dados de novo componente com sucesso.</p>
						<p>Clique em <a href = "gestao-de-propriedades">Continuar </a>para avançar.</p>
<?php
					}
					else
					{
?>				
						<p>Ocorreu um erro ao inserir os dados.</p>
<?php					
						mysqli_query('ROLLBACK');
					}
			else
			{
?>
				<p>Necessário inserir um número inteiro em <b>Ordem do campo no formulário</b> </p>
<?php 
			}
			//back();
		}
	}
?>	
}