<?php	
	require_once("custom/php/common.php");
	
	if(!is_user_logged_in() || !current_user_can("manage_components"))
	{
?>
		<p>Nao tem autorizacao para aceder a esta pagina. Tem de efetuar <a href=><?php wp_loginout("gestao-de-componentes")?> </a>.</p>
<?php	
	}	
	else
	{
		if(!isset($_REQUEST['estado']) || $_REQUEST['estado'] == "") 
		{
			$query_component = "SELECT * FROM component";
			$result_component = mysqli_query($link, $query_component);
			
			if(mysqli_num_rows($result_component) == 0)
			{
				echo "Nao ha componentes.";
			}			
			else
			{
?>			
				<table class="mytable">
				 <thead>
				  <tr>
				   <th>tipo</th>
				   <th>id</th>
				   <th>nome</th>
				   <th>estado</th>
				   <th>acao</th>
				  </tr>
				 </thead>
			 
				 <tbody>
<?php
			
					$query_comp_type = "SELECT id, name FROM comp_type GROUP BY id";
					$result_comp_type = mysqli_query($link, $query_comp_type);
			
					while($row_component_comp_type = mysqli_fetch_array($result_comp_type))
					{
						
						$query_componentes_final = "SELECT c.id, c.name, c.state
												FROM component AS c
												WHERE c.comp_type_id = ".$row_component_comp_type["id"]."
												GROUP BY c.id";
														
						$result_componentes_final = mysqli_query($link, $query_componentes_final);
				
				//Número de linhas de componentes
						$rows_componentes = mysqli_num_rows($result_componentes_final);
						
						if($rows_componentes > 0)
						{
?>						 
							<tr>
							 <td colspan = "1" rowspan = "<?php echo $rows_componentes; ?>"> <?php echo $row_component_comp_type["name"]; ?> </td>
<?php						
							while($array_componentes_final = mysqli_fetch_array($result_componentes_final))
							{
?>
								<td> <?php echo $array_componentes_final["id"] ?></td>
								<td><?php echo '<a href = "gestao-de-componentes?estado=introducao&propriedade='. $array_componentes_final["id"].'">'.$array_componentes_final["name"].'</a>';?></td>
<?php						
								if($array_componentes_final["state"] == "active")
								{
?>							
									<td>ativo</td>
									<td>[editar] [desativar]</td>
<?php
								}
								else
								{
?>							
								<td>inativo</td>
								<td>[editar] [desativar]</td>
<?php
								}							
?>
							</tr>
<?php						}									
						}				
					}			
?>
				 </tbody>
				</table>			
<?php					
			}
			}		
		elseif($_GET['estado'] == "introducao"){
		
?>			
			<h3><b>Gestao de componentes - Introducao<b></h3>
			
			<form name="gestao_de_componentes" method "POST">
			<fieldset>
			<legend>Introduzir dados:</legend>
			
			<p>
			<label><b>Nome:</b></label>
			<input type="text" name="component_name">
			</p>
			
			<p>
			<label><b>Tipo:</b></label>
			<br>
			<input type="radio" name="comp_type" value="1"> <label>Propriedade</label>
			<br>
			<input type="radio" name="comp_type" value="2"> <label>Canal de venda</label>
			<br>
			<input type="radio" name="comp_type" value="3"> <label>Fornecedor</label>
			</p>
			
			<p>
			<label><b>Estado:</b></label>
			<br>
			<input type="radio" name="component_state" value="active"> <label>Ativo</label>
			<br>
			<input type="radio" name="component_state" value="inactive"> <label>Inativo</label>
			</p>
			
			<input type="hidden" name="estado" value="inserir">
			<p>
			<input type="submit" value="Inserir componente">
			</p>
			
			</fieldset>
			</form>		
	
	
	<?php	}			
		elseif($_REQUEST['estado'] == "inserir")
		{
?>		
			<h3><b>Gestao de componentes - Insercao</b></h3>
<?php	
			$component_name = $_REQUEST["component_name"];
			$comp_type = $_REQUEST["comp_type"];
			$component_state = $_REQUEST["component_state"];
			
			if(empty($component_name))
			{
?>			
				<p>Tem de inserir o nome do componente.<p>
<?php			
				back();
				
			}						
			elseif(empty($comp_type))
			{
?>			
				<p>Tem de inserir o tipo de componente.<p>
<?php			
				back();
				
			}			
			elseif(empty($component_state))
			{
?>			
				<p>Tem de inserir o estado do componente.<p>
<?php			
				back();
				
			}
			else
			{
				
				
				$query_insert_string = sprintf('INSERT INTO `component` (`name`, `comp_type_id`, `state`) VALUES ("%s", "%s", "%s");',
				mysqli_real_escape_string($link, $component_name),
				mysqli_real_escape_string($link, $comp_type), 
				mysqli_real_escape_string($link, $component_state));
			
				$result_insert = mysqli_query($link, $query_insert_string);
				//Transição feita com sucesso.
				mysqli_commit($link);
?>
				<p>Inseriu os dados de novo componente com sucesso.</p>
				<p>Clique em <a href="gestao-de-componentes">Continuar</a> para avancar.</p><br>
<?php			
				}			
			}
		}
?>
