<?php	
	require_once("custom/php/common.php");
?>
<?php
	$state = $_POST["state"];	
	if ( is_user_logged_in() && current_user_can('Insert_values') )
		//Verifica se o utilizador está ou não com o login efectuado 
		// !is_user_logged_in() - User não tem o login efectuado
		// e se possui a capability "manage componentes"
		// !current_user_can("manage_components") - user não pode
	{ 
?>
		<p>Não tem autorização para aceder a esta página. Tem de efetuar <?php wp_loginout("insercao-de-valores")?>.</p>
<?php        
	
	}

	else
	{
//isset - Verifica se a variável é definida. retorna FALSE se for usada uma variável com o valor NULL
// $_REQUEST - array associativo que por padrão contém informações de $_GET, $_POST e $_COOKIE.
		if(!isset($_REQUEST['state']) || $_REQUEST['state'] == "") 
		// !isset($_REQUEST['estado'])  - variavel $_REQUEST não está defenida OU
		// $_REQUEST['estado'] == "" - a variavel $_REQUEST é NULA
		{
	
?>						
			<br>
			<h3><b><center>Inserção de valores - escolher componente/formulário customizado</center></b></h3>
			<br> 
<?php
			$comp_query = "SELECT comp_type.id as id_componente, comp_type.name as tipo_componente
						   FROM comp_type ";
			// Defenição de uma variavel($comp_query) igualada uma Query que seleciona
			// os atributos id e tipo existentes na tabela comp_type
				
// mysqli_query - corre uma query na base de dados - estilo procedural. retorna TRUE ou FALSE
// mysql"i" (como os acima) pois correspondem à versão mais recente e a 
// sem o "i" nem sempre funciona como esperado, estando em fase de ser deprecada.
// $link - Variavél GLOBAL de coneção à Base de Dados. Link identificador retornado pelo mysqli_connect()	

			$comp_query_resultado = mysqli_query($link, $comp_query);
			// Defenição de uma variavel ($comp_query_resultado) igualada um comando sql que corre uma query na base de dados

?>
			<ul sytle = "list-style-type:circle">
			<li>Componentes:</li>
<?php
			echo "<ul>";
//mysqli_fetch_array retorna os resultados de uma linha do array e move o "ponteiro" para a linha seguinte
			while($linha = mysqli_fetch_array($comp_query_resultado))
			// Defenição de uma variavel ($linha) igualada um comando sql que percorre os tuplos da BD
			// Enquanto $linha for igual mysqli_fetch_array($comp_query_resultado) efectuar o seguinte
			{
?>		
				<li><?php echo $linha['tipo_componente'];?></li>
				<!--imprime o tipo de componente-->

<?php
				$tipo_comp_query = "SELECT comp.id as component_id, comp.name as component_name
									FROM component as comp
									WHERE comp.comp_type_id = ".$linha['id_componente']." ";
				// Defenição de uma variavel ($tipo_comp_query ) igualada uma Query que seleciona
				// os atributos ID, NAME existentes na tabela component
				// WHERE comp.comp_type_id = ".$linha['id_componente']." - Iguala o valor do ID 
				// presente na BD ao valor da variavel defenida no while

// mysqli_query - corre uma query na base de dados - estilo procedural. retorna TRUE ou FALSE
// mysql"i" (como os acima) pois correspondem à versão mais recente e a 
// sem o "i" nem sempre funciona como esperado, estando em fase de ser deprecada.
// $link - Variavél GLOBAL de coneção à Base de Dados. Link identificador retornado pelo mysqli_connect()	
		        $tipo_comp_query_resultado = mysqli_query($link, $tipo_comp_query);
		        // Defenição de uma variavel ($tipo_comp_query_resultado) igualada um comando sql que corre uma query na base de dados

				echo "<ul>";
				while($tipo_comp_query_resultado_array = mysqli_fetch_array($tipo_comp_query_resultado))
				// Defenição de uma variavel ($tipo_comp_query_resultado_array) igualada um comando sql que percorre os tuplos da BD
			 	// Enquanto $tipo_comp_query_resultado_array for igual mysqli_fetch_array($tipo_comp_query_resultado) efectuar o seguinte
				{

?>	
					<li><a href="insercao-de-valores?state=introducao&comp=<?php echo $tipo_comp_query_resultado_array['component_id'];?>" >
				        		<?php echo '['.$tipo_comp_query_resultado_array['component_name'].']'; ?></a></li> 
				       <!--Imprime o Nome do componente da tabela component sendo que este(nome do componente)
							 fica com uma hiperligação para o ID deste componente -->

<?php
				
				}

				echo "</ul>";

		  	}
	        
			echo"</ul>";


?>
			<li>Formulários customizados</li>

<?php 
			$form_query= "SELECT custom_f.name as custom_form_name, custom_f.id as custom_form_id 
						  FROM custom_form as custom_f";
			 // Defenição de uma variavel ($form_query) igualada uma Query que seleciona
			// os atributos ID, NAME existentes na tabela custom-form

			$form_query_resultado = mysqli_query($link,$form_query);
			// Defenição de uma variavel ($form_query_resultado) igualada um comando sql que corre uma query na base de dados

			echo"<ul>";

			while($form_query_resultado_array = mysqli_fetch_array($form_query_resultado))
			// Defenição de uma variavel ($form_query_resultado_array ) igualada um comando sql que percorre os tuplos da BD
			 // Enquanto $form_query_resultado_array  for igual mysqli_fetch_array($form_query_resultado) efectuar o seguinte
			{
?>
				<li><a href="insercao-de-valores?state=introducao&form=<?php echo $form_query_resultado_array['custom_form_id'];?>" >
				<?php echo '['.$form_query_resultado_array['custom_form_name']." ".']';
				?>
				<!--Imprime o Nome do custom form da tabela custom_form sendo que este(nome do custom form)
							 fica com uma hiperligação para o ID deste mesmo -->
				</a>
				</li>
<?php						
			}	

		echo"</ul>";	
		}

	else if($_REQUEST['state'] == introducao)
	{
		if(isset($_REQUEST['comp']))
		{
			$_SESSION['comp_id'] = $_REQUEST['comp'];
			//Guarda numa variável de sessão chamada comp_id o valor que vem no vector REQUEST associado à variável comp

			$comp_id = mysqli_real_escape_string($link, $_SESSION['comp_id']);

			$sql_query_componente_nome = "SELECT comp.name as component_name 
										  FROM component as comp 
										  WHERE comp.id = $comp_id";
			// Defenição de uma variavel ($sql_query_componente_nome ) igualada uma Query que seleciona
			// os atributos  NAME existentes na tabela componet

			$sql_query_componente_nome_resultado = mysqli_query($sql_query_componente_nome);
			// Defenição de uma variavel (sql_query_componente_nome_resultado) igualada um comando sql que corre uma query na base de dados

//mysqli_fetch_array retorna os resultados de uma linha do array e move o "ponteiro" para a linha seguinte
			$sql_query_componente_nome_resultado_array = mysqli_fetch_array($sql_query_componente_nome_resultado);

			$_SESSION['comp_name'] = $sql_query_componente_nome_resultado_array['component_name'];
			//Guarda numa variável de sessão chamada comp_name o nome do componente associado ao id da linha anterior 

//mysqli_real_escape_string(connection,escapestring) - Escapa caracteres especiais de uma string para ser utilizada muma instrução SQL
			$comp_name = mysqli_escape_string($_SESSION['comp_name']);

			$sql_query_componente_tipo = "SELECT comp.comp_type_id as component_type_id
										  FROM component as comp 
										  WHERE comp.id = $comp_id";
			// Defenição de uma variavel ($sql_query_componente_tipo ) igualada uma Query que seleciona
			// os atributos ID existentes na tabela componet							  

			$sql_query_componente_tipo_resultado = mysqli_query($link, $sql_query_componente_tipo);
			// Defenição de uma variavel ($sql_query_componente_tipo_resultado) igualada um comando sql que corre uma query na base de dados

//mysqli_fetch_array retorna os resultados de uma linha do array e move o "ponteiro" para a linha seguinte			
			$sql_query_componente_tipo_resultado_array = mysqli_fetch_array($sql_query_componente_tipo_resultado);

			$_SESSION['comp_type_id'] = $sql_query_componente_tipo_resultado_array['component_type_id'];
			//Guarda numa variável de sessão chamada comp_type_id o id do respetivo tipo de componente

//mysqli_real_escape_string(connection,escapestring) - Escapa caracteres especiais de uma string para ser utilizada muma instrução SQL
			$component_type_id = mysqli_real_escape_string($link, $_SESSION['comp_type_id']);
?>			
			<h3><b><center>Insercao de valores - <?php echo $comp_name; ?></center></b></h3>
			<!-- imprime o nome do comp-->
			<br>
<?php 
			$nome_form_concatenacao = "comp_type_".$component_type_id."_comp_".$comp_id;

?>		




			<form 
				name = "<?php echo $nome_form_concatenacao?>" 
				action="?state=validar&comp=<?php echo $comp_id;?>"
				method = "post">
<?php	
			$sql_query_propriedades_associadas_componente = "SELECT prop.name as property_name,
																	prop.id as property_id, prop.value_type as proprety_value_type, 
																	prop.form_field_name as property_form_field_name, 
																	prop.form_field_type as property_form_field_type,
																	prop.form_field_order as property_form_field_order,
																	prop.mandatory as property_mandatory,
																	p_unit_type.name as property_unit_type_name
															FROM property as prop, prop_unit_type as p_unit_type
															WHERE prop.component_id = '$comp_id' and state = 'active' and prop.unit_type_id = p_unit_type.id";    
			
			$sql_query_propriedades_associadas_componente_resultado = mysqli_query($link, $sql_query_propriedades_associadas_componente);
			$sql_query_propriedades_associadas_componente_resultado_array = mysqli_fetch_array($sql_query_propriedades_associadas_componente_resultado);  			
			
		<?php 

		}

	    else if(isset($_REQUEST['form']))
	    {
        	$_SESSION['form_id'] = $_REQUEST['form'];  	
			$form_id = mysqli_real_escape_string($link, $_SESSION['form_id']); 

			$sql_query_formulario_nome = "SELECT custom_f.name as custom_form_name
			from custom_form as custom_f 
			where custom_f.id = $form_id";

			$sql_query_formulario_nome_resultado = mysqli_query($link, $sql_query_formulario_nome);
			$sql_query_formulario_nome_resultado_array = mysqli_fetch_array($sql_query_formulario_nome_resultado);

			$_SESSION['from_name'] = $sql_query_formulario_nome_resultado_array ['custom_form_name'];
			$from_name = mysqli_real_escape_string($link, $_SESSION['from_name']);

?>
			<br>
            <h3><b><center>Insercao de valores - <?php echo $form_name; ?></center></b></h3>
            <br>	
<?php 		
			$nome_form_concatenacao_formulario = "comp_".$form_id;

?>			
			<form 
			name = "<?php echo $nome_formulario_concatenacao_form?>" 
			action="?state=validar&form=<?php echo $form_id;?>"
			method = "post">
<?php		
			$sql_query_propriedades_associadas_formulario = "SELECT prop.name as property_name,
			prop.id as property_id, prop.value_type as proprety_value_type, 
			prop.form_field_name as property_form_field_name, 
			prop.form_field_type as property_form_field_type,
			prop.form_field_order as property_form_field_order,
			prop.mandatory as property_mandatory,
			p_unit_type.name as property_unit_type_name
			from property as prop, prop_unit_type as p_unit_type, custom_form_has_property as custom_f_has_prop
			where custom_f_has_prop.custom_form_id = '$form_id' and state = 'active' and prop.unit_type_id = p_unit_type.id and prop.id = custom_f_has_prop.property_id";	
			
			$sql_query_propriedades_associadas_formulario_resultado = mysqli_query($link,$sql_query_propriedades_associadas_formulario);
			$sql_query_propriedades_associadas_formulario_resultado_array = mysqli_fetch_array($sql_query_propriedades_associadas_formulario_resultado);



	    	if(empty($form_id))
			{
?>			

<?php	
				back();// função para voltar atrás na página (Fornecida no Moodle)
				
			}
		else if(empty($comp_id))
		{
<?php

				back();// função para voltar atrás na página (Fornecida no Moodle)

		}
		else if(empty($from_name))
			{
?>			

<?php	
				back();// função para voltar atrás na página (Fornecida no Moodle)
				
			}
			else
			{ 	
		//Falta corrigir a inserção de valores //
$property_id = $array_property_component['id'];

		$insert_value = sprintf(" INSERT INTO value (`comp_inst_id`, `property_id`, `value`, `date`, `time`, `producer`) 

																	VALUES ('%s', '%s', '%s', '%s', '%s', '%s'); ",
																	mysql_real_escape_string('NULL'), // comp_inst_id devia ser NULL
																	mysql_real_escape_string($property_id),
																	mysql_real_escape_string($valor),
																	mysql_real_escape_string($date),
																	mysql_real_escape_string($time),
																	mysql_real_escape_string($producer));
																	
							$result_insert_value = mysql_query($insert_value);

      
if($result_insert_value)
				{
?>				
					<p>Inseriu o(s) valor(es) com sucesso.</p>
				
					<p>
					Clique em <a href = "insercao-de-valores"> Voltar</a>  para voltar ao início da inserção de 
					valores e poder escolher outro formulário customizado ou em <a href = "insercao-de-valores?estado=introducao&form=<?php echo $s_form_id; ?>" > 
					Continuar a inserir valores neste formulário customizado </a>se quiser continuar a inserir valores;
					</p>
	}


				}
		}
