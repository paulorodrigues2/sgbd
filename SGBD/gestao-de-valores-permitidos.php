<?php
	require_once("custom/php/common.php");
	if(!is_user_logged_in() || !current_user_can('manage_allowed_value'))
		//Verifica se o utilizador está ou não com o login efectuado 
		// !is_user_logged_in() - User não tem o login efectuado
		// e se possui a capability "manage componentes"
		// !current_user_can("manage_allowed_value") - user não pode
	{
?>
		<p>Não tem autorização para aceder a esta página.</p>
<?php
	}
	else
	{

//isset - Verifica se a variável é definida. retorna FALSE se for usada uma variável com o valor NULL
// $_REQUEST - array associativo que por padrão contém informações de $_GET, $_POST e $_COOKIE.
		if(!isset($_REQUEST['estado']) || $_REQUEST['estado'] == "") 
		// !isset($_REQUEST['estado'])  - variavel $_REQUEST não está defenida OU
		// $_REQUEST['estado'] == "" - a variavel $_REQUEST é NULA
		{
		{
			
									
			$query_components	= "	 SELECT DISTINCT component_id, c.name AS 'c.name'
									 FROM component AS c, property AS p
									 WHERE component_id = c.id AND value_type = 'enum'";
			// Defenição de uma variavel($query_components) igualada uma Query que seleciona
			// os atributos ID e nome distintos existentes na tabela component

// mysqli_query - corre uma query na base de dados - estilo procedural. retorna TRUE ou FALSE
// mysql"i" (como os acima) pois correspondem à versão mais recente e a 
// sem o "i" nem sempre funciona como esperado, estando em fase de ser deprecada.
// $link - Variavél GLOBAL de coneção à Base de Dados. Link identificador retornado pelo mysqli_connect()	
			$resultado_components = mysqli_query($link, $query_components);
			// Defenição de uma variavel ($result_components) igualada um comando sql que corre uma query na base de dados
			
// mysqli_num_rows - retorna o numero de linhas resultante do resultado da query
			if(mysqli_num_rows($resultado_components) == 0)
				// Caso não existam componentes (== 0) na tabela component
				echo "Não há propriedades especificadas cujo tipo de valor seja enum. Especificar primeiro nova(s) propriedade(s) e depois voltar a esta opção.";
			else
			{
?>
<!-- 
<table> - Cria uma tabela  
<tr> - Cria uma linha 
<thead> - define o conteúdo do cabeçalho de uma tabela
<tbody> - Agrupa o conteúdo do corpo de uma tabela 
<th> - Definição do "title heads"
-->
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

//mysqli_fetch_array retorna os resultados de uma linha do array e move o "ponteiro" para a linha seguinte
				while($row_components = mysqli_fetch_array($resultado_components))
				// Defenição de uma variavel ($row_components) igualada um comando sql que percorre os tuplos da BD
				// Enquanto $row_components for igual mysqli_fetch_array($resultado_components) este efectuar o seguinte
				{
					$i = 0;
					
					$query_rows_comp = "SELECT  p.name AS 'p.name', property_id
										FROM component AS c, property AS p, prop_allowed_value
										WHERE value_type = 'enum' AND c.id = ".$row_components['component_id']." AND property_id = p.id AND component_id = c.id" ;
					// Defenição de uma variavel ($query_rows_comp) igualada uma Query que seleciona
					// os atributos ID, NAME existentes na tabela property igualando a chave estrangeira de property com a query anterior
										
					$result_rows_comp = mysqli_query($link, $query_rows_comp);
					// Defenição de uma variavel ($result_rows_comp ) igualada um comando sql que corre uma query na base de dados

//$mysqli_num_rows - Retorna o numero de linhas resultante de um resultado
					$num_rows_comp = mysqli_num_rows($result_rows_comp);
					// Defenição de uma variavel ($num_rows_comp) igualada um comando SQL que retorna o numero de linhas presentes no tuplo
					
					
										
					$query_comp_prop = "SELECT DISTINCT p.name AS 'p.name', property_id AS property_id
										FROM component AS c, property AS p, prop_allowed_value
										WHERE value_type = 'enum' AND c.id = ".$row_components['component_id']." AND property_id = p.id AND component_id = c.id";
					// Defenição de uma variavel ($query_comp_prop) igualada uma Query que seleciona
					// os atributos ID e valor existentes na tabela property igualando a chave estrangeira de component			
					$result_comp_prop = mysqli_query($link, $query_comp_prop);
					// Defenição de uma variavel ($result_comp_prop) igualada um comando sql que corre uma query na base de dados
?>					
					<tr><td colspan = "1" rowspan =" <?php echo $num_rows_comp; ?>" > <?php echo $row_components['c.name']; ?> </td>
					<!-- rowspan = echo $num_rows_comp - Define o rowspan com o numero resultante do comando 
						mysqli_num_rows($result_rows_comp) que está igualado à variavel $num_rows_comp.
						echo $row_components['c.name'] - imprime o nome do component-->

<?php	
//mysqli_fetch_array retorna os resultados de uma linha do array e move o "ponteiro" para a linha seguinte	
					while($row_comp_prop = mysqli_fetch_array($result_comp_prop))
					// Defenição de uma variavel ($row_comp_prop) igualada um comando sql que percorre os tuplos da BD
					// Enquanto $row_comp_prop for igual mysqli_fetch_array($result_comp_prop) este efectuar o seguinte
					{
						$j = 0;
						$i++;

						$query_prop = "SELECT DISTINCT pav.*
										FROM prop_allowed_value AS pav, property AS p, component AS c
										WHERE value_type ='enum' AND p.id = ".$row_comp_prop['property_id']." AND component_id = c.id AND property_id = p.id";
						// Defenição de uma variavel ($query_prop) igualada uma Query que seleciona
						// os atributos estado e valor do valor permitido,igualando a chave estrangeira de property
										
						$result_prop = mysqli_query($link, $query_prop);
						// Defenição de uma variavel ($result_prop) igualada um comando sql que corre uma query na base de dados

//$mysqli_num_rows - Retorna o numero de linhas resultante de um resultado
						$num_rows_prop = mysqli_num_rows($result_prop);
						// Defenição de uma variavel ($num_rows_prop) igualada um comando SQL que retorna o numero de linhas presentes no tuplo
						
						if($i > 1)
							echo '<tr>';
?>							
						<td colspan = "1" rowspan = "<?php echo $num_rows_prop; ?>" > <?php echo $row_comp_prop['property_id']; ?> </td>
						<!-- rowspan = echo $num_rows_comp - Define o rowspan com o numero resultante do comando 
						mysqli_num_rows($result_rows_comp) que está igualado à variavel $num_rows_comp.
						echo $row_comp_prop['property_id'] - imprime o id da propriedade-->

						<td colspan = "1" rowspan = "<?php echo $num_rows_prop; ?>" >
							<?php echo '<a href = "gestao-de-valores-permitidos?estado=introducao&propriedade='.$row_comp_prop['property_id'].'">'.$row_comp_prop['p.name'].'</a>'; ?>
						<!--Imprime o Nome da propriedade da tabela prop_unit_type  sendo que este(nome do componente)
								 fica com uma hiperligação para o ID desta propriedade -->
						</td>					 
<?php 	
//mysqli_fetch_array retorna os resultados de uma linha do array e move o "ponteiro" para a linha seguinte	
						while($row_prop = mysqli_fetch_array($result_prop))
						// Defenição de uma variavel ($row_prop) igualada um comando sql que percorre os tuplos da BD
						// Enquanto $row_comp_prop for igual mysqli_fetch_array($result_prop) este efectuar o seguinte
						{
							$j++;
							if($j > 1)
							echo '<tr>';
?>
							<td> <?php echo $row_prop['id']; ?> </td>
							<!--imprime o valor do ID do valor permitido resultante da query_prop-->

							<td> <?php echo $row_prop['value']; ?> </td>
<?php						//imprime o value do valor permitido resultante da query_prop
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
			//Guarda numa variável de sessão chamada property_id o valor que vem no vector REQUEST associado à variável propriedade		
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
			// Se o valor da variavel estado for inserir
		{
?>
			<h3>Gestão de valores permitidos - inserção</h3><br>
<?php

// $_REQUEST - array associativo que por padrão contém informações de $_GET, $_POST e $_COOKIE.
			$property_id = $_SESSION['property_id'];
			$valor = $_REQUEST['value_name'];
			$state = "active";
			// defenição de variaveis igualando-as ao valor inseridos no input do formularios acima defenidos

			if(empty($valor))
			{ // caso não tenha sido inserido o valor da propriedade
?>				
				<p>Não introduzio o nome para o valor</p>
<?php				
				back(); // função para voltar atrás na página (Fornecida no Moodle)
			}
			elseif (empty($property_id)) {
				// caso não tenha sido inserido id da propriedade ?>
				<p>Não introduzio o nome para o valor</p>
<?php				
				back(); // função para voltar atrás na página (Fornecida no Moodle)
			}
			else
			{
//Começa a transição apenas de escrita(inserção de valores) Retorna TRUE caso esta seja bem sucedida ou FALSE caso falhe.
				mysqli_begin_transaction($link, MYSQLI_TRANS_START_READ_WRITE);

// sprintf escreve uma string formatada numa variável
// os parametros serão inseridos em porcentagem (%). Esta função funciona "passo-a-passo". Ao primeiro 
//sinal%, é introduzido o primeiro argumento, no segundo sinal%, o segundo argumento é inserido, etc.
				$inserir = sprintf('INSERT INTO `prop_allowed_value` (`property_id`, `value`, `state`)
									VALUES ("%s", "%s", "%s");',

//mysqli_real_escape_string(connection,escapestring) - Escapa caracteres especiais de uma string para ser utilizada muma instrução SQL
						mysqli_real_escape_string($link, $property_id),
						mysqli_real_escape_string($link,$valor),
						mysqli_real_escape_string($link, $state));	

				$query_insere = mysqli_query($link, $inserir);
				// Defenição de uma variavel ($query_insere) igualada um comando sql que corre uma query de inserção na base de dados

//mysqli_commit - guarda a transação na base de dados	
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
<?php		
//mysqli_query('ROLLBACK')-  reverte a transação atual 
			mysqli_query('ROLLBACK');
				back();// função para voltar atrás na página (Fornecida no Moodle)
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


