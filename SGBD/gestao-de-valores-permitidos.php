<?php
	require_once("custom/php/common.php");
	if(!is_user_logged_in() || !current_user_can('manage_allowed_value'))
	{
?>
		<p>Não tem autorização para aceder a esta página.</p>
<?php
	}
	else
	{
		if(!isset($_REQUEST['estado']) || $_REQUEST['estado'] == "") 
		{
			
									//Seleciona o id da component e o nome da component
			$query_components	= "	 SELECT DISTINCT component_id, c.name AS 'c.name'
									 FROM component AS c, property AS p
									 WHERE component_id = c.id AND value_type = 'enum'";
			$resultado_components = mysqli_query($link, $query_components);
			
			if(mysqli_num_rows($resultado_components) == 0)
				echo "Não há propriedades especificadas cujo tipo de valor seja enum. Especificar primeiro nova(s) propriedade(s) e depois voltar a esta opção.";
			else
			{
?>
				<table class="mytable">
				<thead><tr>
				<th>Componente</th>
				<th>ID</th>
				<th>Propriedade</th>
				<th>ID</th>
				<th>Valores permitidos</th>
				<th>Estado</th>
				<th>Acção</th>
				</tr></thead>			
				<tbody>
<?php		
				while($row_components = mysqli_fetch_array($resultado_components))
				{
					$i = 0;
					
										// Seleciona o nome da propriedade e o valor do id da propriedade igualando a chave estrangeira de propriedade com a query anterior
					$query_rows_comp = "SELECT  p.name AS 'p.name', property_id
										FROM component AS c, property AS p, prop_allowed_value
										WHERE value_type = 'enum' AND c.id = ".$row_components['component_id']." AND property_id = p.id AND component_id = c.id" ;
										
					$result_rows_comp = mysqli_query($link, $query_rows_comp);
					$num_rows_comp = mysqli_num_rows($result_rows_comp);
					
					
										// Seleciona o nome da propriedade e o valor do id da propriedade, igualando a chave estrangeira de component.
					$query_comp_prop = "SELECT DISTINCT p.name AS 'p.name', property_id AS property_id
										FROM component AS c, property AS p, prop_allowed_value
										WHERE value_type = 'enum' AND c.id = ".$row_components['component_id']." AND property_id = p.id AND component_id = c.id";
										
					$result_comp_prop = mysqli_query($link, $query_comp_prop);
?>					
					<tr><td colspan = "1" rowspan =" <?php /*Junta os valores das linhas seguintesecho*/ echo $num_rows_comp; ?>" > <?php echo $row_components['c.name']; ?> </td>
<?php		
					while($row_comp_prop = mysqli_fetch_array($result_comp_prop))
					{
						$j = 0;
						$i++;
						
									// Seleciona o estado e o valor do valor permitido, igualando a chave estrangeira de propriedade.
						$query_prop = "SELECT DISTINCT pav.*
										FROM prop_allowed_value AS pav, property AS p, component AS c
										WHERE value_type ='enum' AND p.id = ".$row_comp_prop['property_id']." AND component_id = c.id AND property_id = p.id";
										
						$result_prop = mysqli_query($link, $query_prop);
						$num_rows_prop = mysqli_num_rows($result_prop);
						
						if($i > 1)
							echo '<tr>';
?>							
						<td colspan = "1" rowspan = "<?php echo $num_rows_prop; ?>" > <?php echo $row_comp_prop['property_id']; ?> </td>
						<td colspan = "1" rowspan = "<?php echo $num_rows_prop; ?>" >
							<?php echo '<a href = "gestao-de-valores-permitidos?estado=introducao&propriedade='.$row_comp_prop['property_id'].'">'.$row_comp_prop['p.name'].'</a>'; ?>
						</td>					 
<?php 	
						while($row_prop = mysqli_fetch_array($result_prop))
						{
							$j++;
							if($j > 1)
							echo '<tr>';
?>
							<td> <?php echo $row_prop['id']; ?> </td>
							<td> <?php echo $row_prop['value']; ?> </td>
<?php				
							if($row_prop['state'] == "active")
							{
?>							
								<td>ativo</td>
								<td>[editar]<br>[desactivar]</td>
								</tr>
<?php 			
							}
							else
							{	
?>						
								<td>inativo</td>
								<td>[editar]<br>[ativar]</td>
								</tr>
<?php					
							}
						}
					}
				}
?>				
				</tbody>
				</table>
<?php				
			}
		}				
		elseif($_GET['estado'] == "introducao")
		{
			$_SESSION['property_id'] = $_REQUEST['propriedade'];		
?>		
			<h3><b>Gestão de valores permitidos - introdução</b></h3>
			
			<form name = "gestao de valores permitidos" onsubmit="return validateform()" method = "REQUEST">
			
			<fieldset>
			<legend>Introduzir valores:</legend>
			
			<p>
			<label><b>Valor:</b></label>
			<input type = "text" name = "value_name">
			</p>
			
			<input type = "hidden" name = "estado" value = "inserir">
			<p>
			<input type = "submit" name = "inserir valor permitido">
			</p>
			</fieldset>
			</form>		
<?php					
		}
		elseif($_REQUEST['estado'] == "inserir")
		{
?>
			<h3>Gestão de valores permitidos - inserção</h3><br>
<?php
			$property_id = $_SESSION['property_id'];
			$valor = $_REQUEST['value_name'];
			$state = "active";
			if(empty($valor))
			{
?>				
				<p>Não introduzio o nome para o valor</p>
<?php				
				back();
			}
			elseif (empty($property_id)) {
				?>
				<p>Não introduzio o nome para o valor</p>
<?php				
				back();
			}
			else
			{
				//Começa a transição apenas de escrita(inserção de valores) Returns TRUE on success or FALSE on failure.
				mysqli_begin_transaction($link, MYSQLI_TRANS_START_READ_WRITE);
				$inserir = sprintf('INSERT INTO `prop_allowed_value` (`property_id`, `value`, `state`)
								VALUES ("%s", "%s", "%s");',
						mysqli_real_escape_string($link, $property_id),
						mysqli_real_escape_string($link,$valor),
						mysqli_real_escape_string($link, $state));				
				$query_insere = mysqli_query($link, $inserir);
				//Transição feita com sucesso.
				mysqli_commit($link);
				
				if(mysqli_query($link, $inserir))
				{
?>
					<p>Inseriu os dados de novo valor permitido com sucesso.Clique em
					<a href = "gestao-de-valores-permitidos"> Continuar </a> para avançar</p>
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
	}
?>
<script>
function validateForm() {
    var x = document.forms["gestao de valores permitidos"]["value_name"].value;
    if (x == null || x == "" || x.search(/^[A-Z ]+$/i)) {
        alert("Não insira valores numericos.");
        return false;
    }
}   
</script>


