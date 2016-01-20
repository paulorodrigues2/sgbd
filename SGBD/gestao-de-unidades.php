<?php	
	require_once("custom/php/common.php");
	
	if(!is_user_logged_in() || !current_user_can('manage_unit_types'))
		//Verifica se o utilizador está ou não com o login efectuado 
		// !is_user_logged_in() - User não tem o login efectuado
		// e se possui a capability "manage componentes"
		// !current_user_can("manage_unit_types") - user não pode
	{
?>
		<p>Não tem permissão para aceder a esta página. Tem de efetuar <a href=><?php wp_loginout("gestao-de-unidades")?> </a>.</p>
		
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
			$sql_prop_unit_type = "SELECT * 
								   FROM prop_unit_type 
								   ORDER BY name";
			 // Defenição de uma variavel($sql_prop_unit_type) igualada uma Query que seleciona
			// todos os atributos existentes na tabela prop_unit_type e ordena os resultados por name

// mysqli_query - corre uma query na base de dados - estilo procedural. retorna TRUE ou FALSE
// mysql"i" (como os acima) pois correspondem à versão mais recente e a 
// sem o "i" nem sempre funciona como esperado, estando em fase de ser deprecada.
// $link - Variavél GLOBAL de coneção à Base de Dados. Link identificador retornado pelo mysqli_connect()	

			$res_prop_unit_type = mysqli_query($link, $sql_prop_unit_type);
			// Defenição de uma variavel ($res_prop_unit_type) igualada um comando sql que corre uma query na base de dados

// mysqli_num_rows - retorna o numero de linhas resultante do resultado da query			
			if(mysqli_num_rows($res_prop_unit_type) == 0)
				// Caso não existam componentes (== 0) na tabela prop_unit_type
				echo "Não há tipos de unidades.";
			else
			{		
?>
<!-- 
<table> - Cria uma tabela  
 <tr> - Cria uma linha 
<th> - Definição do "title heads"
-->
				<table class="mytable">
				 <tr>
				  <th>id</th>
				  <th>unidade</th>
				 </tr>
<?php	

//mysqli_fetch_array retorna os resultados de uma linha do array e move o "ponteiro" para a linha seguinte
					while($linha = mysqli_fetch_array($res_prop_unit_type))
					// Defenição de uma variavel ($linha) igualada um comando sql que percorre os tuplos da BD
					// Enquanto $linha for igual mysqli_fetch_array($res_prop_unit_type) este efectuar o seguinte
					{
?>
						<tr>
						 <td> <?php echo $linha['id']; ?> </td>
						 <!-- echo echo $linha['id'] - imprime o nome do ID do prop_unit_type -->

						<td><?php echo '<a href = "gestao-de-unidades?estado=introducao&unidade='. $linha["id"].'">'.$linha['name'].'</a>';?></td>
						<!--Imprime o Nome do componente da tabela prop_unit_type  sendo que este(nome do componente)
								 fica com uma hiperligação para o ID deste componente -->
						</tr>
<?php 
					} 
?>
				</table>
<?php				
			}
		}				
		elseif($_GET['estado'] == "introducao")
		// Se o valor da variavel estado for introdução
		{
			
?>		


			    <h3><b>Gestao de Unidades - Introducao</b></h3>
			
			    <form name="gestao_de_unidades" onsubmit="return validateForm()" method="POST">
			    <fieldset>
				<legend>Registar Unidade:</legend>
				
				<p>
				<label><b>Nome:</b></label>
				<input type="text" name="nome">
				</p>
				
				<input type="hidden" name="state" value="inserir">
				<p>
				<input type="submit" value="Inserir unidade">
				</p>
			    
				</fieldset>
			    </form>
			
			

			
<?php           
		}
		else if($_REQUEST['estado'] == "inserir")
		// se o valor da variavel estado for inserir
		{
?>
			<h3><b>Gestao de unidades - Insercao</b></h3>
<?php

//mysqli_real_escape_string(connection,escapestring) - Escapa caracteres especiais de uma string para ser utilizada muma instrução SQL
			$nome_inserido = mysqli_real_escape_string($link, $_REQUEST['nome']);
			$submit = mysqli_real_escape_string($link, $_REQUEST['submit']);
			// Defenição de uma variavel ($nome_inserido e $submit) igualada um comando sql Escapa caracteres especiais de uma string
			

			if(empty($nome_inserido))
			{
?>			

<?php	
				back();// função para voltar atrás na página (Fornecida no Moodle)
				
			}
		else if(empty($submit))
			{
?>			

<?php	
				back();// função para voltar atrás na página (Fornecida no Moodle)
				
			}
			else
			{
						
//Começa a transição apenas de escrita(inserção de valores) Retorna TRUE caso esta seja bem sucedida ou FALSE caso falhe.
				mysqli_begin_transaction($link, MYSQLI_TRANS_START_READ_WRITE);
				
			$inserir_unidade ="INSERT INTO prop_unit_type (name)
							   VALUES ('$nome_inserido')";
			// Defenição de uma variavel($inserir_unidade) igualada uma Query que insere
			// o atributo name existentes na tabela prop_unit_type sendo o seu valor especificado pelo utilzador no formulario de input

			$resultado_inserido = mysqli_query($link, $inserir_unidade);
			// Defenição de uma variavel ($resultado_inserido) igualada a um comando sql que corre uma query de inserção na base de dados

//mysqli_commit - guarda a transação na base de dados	
				mysqli_commit($link);
?>
				<p>Inseriu os dados de novo tipo de unidade com sucesso.</p>
				<p>Clique em <a href="gestao-de-unidades">Continuar</a> para avancar.</p>
<?php	
	
			}
		}
	}
?>
<script>
function validateForm() {
    var x = document.forms["gestao_de_unidades"]["name"].value;
    if (x == null || x == "" || x.search(/^[A-Z ]+$/i)) {
        alert("Não insira valores numericos.");
        return false;
    }
}   
</script>



