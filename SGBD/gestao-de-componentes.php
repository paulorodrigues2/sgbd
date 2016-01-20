<?php	
	require_once("custom/php/common.php");
	
	if(!is_user_logged_in() || !current_user_can("manage_components"))
		//Verifica se o utilizador está ou não com o login efectuado 
				// !is_user_logged_in() - User não tem o login efectuado
		// e se possui a capability "manage componentes"
				// !current_user_can("manage_components") - user não pode
	{
?>
		<p>Nao tem autorizacao para aceder a esta pagina. Tem de efetuar <a href=><?php wp_loginout("gestao-de-componentes")?> </a>.</p>

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
			$query_component = "SELECT * FROM component";
			// Defenição de uma variavel($query_component) igualada uma Query que seleciona
			// todos os atributos existentes na tabela component

// mysqli_query - corre uma query na base de dados - estilo procedural. retorna TRUE ou FALSE
// mysql"i" (como os acima) pois correspondem à versão mais recente e a 
// sem o "i" nem sempre funciona como esperado, estando em fase de ser deprecada.
// $link - Variavél GLOBAL de coneção à Base de Dados. Link identificador retornado pelo mysqli_connect()	

			$result_component = mysqli_query($link, $query_component);
			// Defenição de uma variavel ($result_component) igualada um comando sql que corre uma query na base de dados
			
// mysqli_num_rows - retorna o numero de linhas resultante do resultado da query
			if(mysqli_num_rows($result_component) == 0)
			// Caso não existam componentes (== 0) na tabela component
			{
				echo "Nao ha componentes.";
			}			
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
				 <thead>
				  <tr>
				   <th>Tipo</th>
				   <th>ID</th>
				   <th>Nome</th>
				   <th>Estado</th>
				   <th>Ação</th>
				  </tr>
				 </thead>
			 
				 <tbody>
<?php
			
					$query_comp_type = "SELECT id, name 
										FROM comp_type 
										GROUP BY id";
					// Defenição de uma variavel ($query_comp_type) igualada uma Query que seleciona
					// os atributos ID e NAME existentes na tabela comp_type e ordena-os por ID

					$result_comp_type = mysqli_query($link, $query_comp_type);
			 		// Defenição de uma variavel ($result_comp_type) igualada um comando sql que corre uma query na base de dados
					{

//mysqli_fetch_array retorna os resultados de uma linha do array e move o "ponteiro" para a linha seguinte
					while($row_component_comp_type = mysqli_fetch_array($result_comp_type))
					// Defenição de uma variavel ($row_component_comp_type) igualada um comando sql que percorre os tuplos da BD
					// Enquanto $row_component_comp_type for igual mysqli_fetch_array($result_comp_type) este efectuar o seguinte
						
						$query_componentes_final = "SELECT c.id, c.name, c.state
												FROM component AS c
												WHERE c.comp_type_id = ".$row_component_comp_type["id"]."
												GROUP BY c.id";
						// Defenição de uma variavel ($query_componentes_final) igualada uma Query que seleciona
						// os atributos ID, NAME e STATE existentes na tabela componet e ordena-os por ID.
						// WHERE c.comp_type_id = ".$row_component_comp_type["id"]. - Iguala o valor do ID 
						// presente na BD ao valor da variavel defenida no while
														
						$result_componentes_final = mysqli_query($link, $query_componentes_final);
						// Defenição de uma variavel ($result_componentes_final) igualada um comando sql que corre uma query na base de dados
				
//$mysqli_num_rows - Retorna o numero de linhas resultante de um resultado
						$rows_componentes = mysqli_num_rows($result_componentes_final);
						// Defenição de uma variavel ($rows_componentes) igualada um comando SQL que retorna o numero de linhas presentes no tuplo

						if($rows_componentes > 0){
?>						 
							<tr>
							
							 <td colspan = "1" rowspan = "<?php echo $rows_componentes; ?>"> <?php echo $row_component_comp_type["name"]; ?> </td>
<?php						// rowspan = echo $rows_componentes - Define o rowspan com o numero resultante do comando 
							// mysqli_num_rows($result_componentes_final) que está igualado à variavel $rows_componentes.
							// echo $row_component_comp_type["name"] - imprime o nome do comp_type

//mysqli_fetch_array retorna os resultados de uma linha do array e move o "ponteiro" para a linha seguinte
							while($array_componentes_final = mysqli_fetch_array($result_componentes_final))
							// Defenição de uma variavel ($array_componentes_final) igualada um comando sql que percorre os tuplos da BD
							// Enquanto $array_componentes_final for igual mysqli_fetch_array($result_componentes_final) este efectuar o seguinte
						
							{
?>
								<td> <?php echo $array_componentes_final["id"] ?></td>
								<!--Imprime o ID do componente da tabela componente -->
								<td><?php echo '<a href = "gestao-de-componentes?estado=introducao&propriedade='. $array_componentes_final["id"].'">'.$array_componentes_final["name"].'</a>';?></td>
								<!--Imprime o Nome do componente da tabela componente sendo que este(nome do componente)
								 fica com uma hiperligação para o ID deste componente -->
<?php						
								if($array_componentes_final["state"] == "active")
								// Caso o state do componente presente na base de dados estiver activo
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
		// Se o valor da variavel estado for introdução 
		
?>			
			<h3><b>Gestao de componentes - Introducao<b></h3>
			
			<form name="gestao_de_componentes" method "POST">
<!--fieldset - agrupa elementos relacionados enum formulário-->
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
			// se o valor da variavel estado for inserir
		{
?>		
			<h3><b>Gestao de componentes - Insercao</b></h3>

<?php	
// $_REQUEST - array associativo que por padrão contém informações de $_GET, $_POST e $_COOKIE.
			$component_name = $_REQUEST["component_name"];
			$comp_type = $_REQUEST["comp_type"];
			$component_state = $_REQUEST["component_state"];
			// defenição de variaveis igualando-as ao valor inseridos no input do formularios acima defenidos

//verificações
			if(empty($component_name))
			// caso não tenha sido inserido o nome do componente
			{
?>			
				<p>Tem de inserir o nome do componente.<p>
<?php			
				back();// função para voltar atrás na página (Fornecida no Moodle)
				
			}						
			elseif(empty($comp_type))
			// caso não tenha sido inserido o tipo de componente
			{
?>			
				<p>Tem de inserir o tipo de componente.<p>
<?php			
				back(); // função para voltar atrás na página (Fornecida no Moodle)
				
			}			
			elseif(empty($component_state))
			// caso não tenha sido inserido o estado do componente
			{
?>			
				<p>Tem de inserir o estado do componente.<p>
<?php			
				back();// função para voltar atrás na página (Fornecida no Moodle)
				
			}
			else
			{
				
// sprintf escreve uma string formatada numa variável
// os parametros serão inseridos em porcentagem (%). Esta função funciona "passo-a-passo". Ao primeiro 
//sinal%, é introduzido o primeiro argumento, no segundo sinal%, o segundo argumento é inserido, etc.
				$query_insert_string = sprintf('INSERT INTO `component` (`name`, `comp_type_id`, `state`) 
												VALUES ("%s", "%s", "%s");',

//mysqli_real_escape_string(connection,escapestring) - Escapa caracteres especiais de uma string para ser utilizada muma instrução SQL
				mysqli_real_escape_string($link, $component_name),
				mysqli_real_escape_string($link, $comp_type), 
				mysqli_real_escape_string($link, $component_state));
			
				$result_insert = mysqli_query($link, $query_insert_string);
				// Defenição de uma variavel ($result_insert) igualada um comando sql que corre uma query de inserção na base de dados
				
//mysqli_commit - guarda a transação na base de dados				
				mysqli_commit($link);
?>
				<p>Inseriu os dados de novo componente com sucesso.</p>
				<p>Clique em <a href="gestao-de-componentes">Continuar</a> para avancar.</p><br>
<?php			
				}			
			}
		}
?>
