<html>
	<head>

		<meta http-equiv="content-type" content="text/html;charset=utf-8" />
		<link rel="stylesheet" type="text/css" href="custom/css/ag.css">
	</head>
<?php 
	require_once("custom/php/common.php");
	$estado = $_POST["estado"];
		if ( is_user_logged_in() && current_user_can('manage_properties') )
		// se o utilizador está com o login efectuado e tem a capability "manage_properties"
	{
		if(empty($estado))
		//se a variavel estado "está vazia"/não tem valor
		{
			?><h3><b>Gestão de Propriedades - Introdução</b></h3><?php
			
//defenição de variaveis usadas no form			
			$nome = $_POST["nome"];
			$tipovalor = $_POST["tipovalor"];
			$componente = $_POST["componente"];
			$tipocampo = $_POST["tipodocampo"];
			//$nomecampo = $_POST["nomecampo"];
			$tipounidade = $_POST["tipounidade"];
			$ordemcampo = $_POST["ordemcampo"];
			$obrigatorio = $_POST["obrigatório"];
			$estadoform = $_POST["estadoform"];

			
//Defenição de uma variavel ($query_propriedades )	que seleciona todos 
//os atributos da entidade property e ordena-os por nome(ordem alfabetica)
			$query_propriedades = "SELECT * 
									FROM property 
									ORDER BY name";

//	$wpdb - por default é inicializada para conectar a base de dados sendo que esta variavel terá de ser
// defenida como global ou superglobal
// get_Results - obtem resultado de multiplas linhas de uma tabela presente na BD
			$result_propriedades = $wpdb->get_results($query_propriedades);

			
			if($result_propriedades)
			{
			?>
<!-- inicialização de uma tabela-->            
			<table>
				<tr>
					<th>Componente</th>
					<th></th>>ID</th>
					<th>Propriedade</th>
					<th>Tipo de valor</th>
					<th>Nome do Campo no Formulário</th>
					<th>Tipo do Campo no Formulário</th>
					<th>Tipo de Unidade</th>
					<th>Ordem do Campo no Formulário</th>
					<th>Obrigatório</th>
					<th>Estado</th>
					<th>Ação</th>
				</tr>
            <?php foreach($result_propriedades as $row)
             //redefinição da variavél $result_propriedades para $row
				{
					?>
				<tr>

<!-- "imprime" os valores presentes na tabela da base de dados em colunas-->
                    <td><?php echo "".$row->component_id."";?></td>
                    <td><?php echo "".$row->id."";?></td>
                    <td><?php echo "".$row->component_id."";?></td>
					<td><?php echo "".$row->value_type."";?></td>
                    <td><?php echo "".$row->form_field_name."";?></td>
                    <td><?php echo "".$row->form_field_type."";?></td>
                    <td><?php echo "".$row->unit_type_id."";?></td>
                    <td><?php echo "".$row->form_field_order."";?></td>
                    <td><?php echo "".$row->mandatory."";?></td>
                    <td><?php echo "".$row->state."";?></td>
                    <td><?php 
					if($row->state == 'active')
					{
						echo(
							"[editar] [desativar]"
							);
					}
					else{
						echo(
							"[editar] [ativar]"
							);
						}
				}
						?></td>
					</tr>

            </table>
            
			<?php
			
			}
		else
		{
			echo '<center><u>Não há propiedades especificadas.</center></u><br>';
//Se não houverem tuplos na tabela properties, apresentar o texto "Não há propiedades especificadas"
		}
			
		?>
<!--formulário que possibilita a inserção de uma nova propriedade-->        
		<center>
		<form method="post" action="">
	        <table>
	        	<tr align="center">
	        		<td><h2><b>Nome da propriedade</h2></b></td>
	        		<td><input class="textbox" type = 'text' name='nomePropriedade' placeholder='Nome da propriedade'/></td>
	        	</tr>
	        	<tr>
	        		<td><h2><b>Tipo de valor<h2><b></td>
	        		<td>
<!-- lista com todos os tipos de valores presentes no atributo value_type-->
		        		<ul>
		        			<input type="radio" name="tipovalor" value="int">INT<br>
		        			<input type="radio" name="tipovalor" value="enum">ENUM<br>
		        			<input type="radio" name="tipovalor" value="text">TEXT<br>
		        			<input type="radio" name="tipovalor" value="bool">BOOL<br>
<?php
				?>		</ul></td>
				</tr>
	        	<tr align="center">
	        		<td><h2><b>Componente</h2></b></td>
	        		<td><select name="idComponente">

<!--apresentar uma lista com os nomes de todos os componentes presentes na tabela component-->
					<?php
						$query_componente = 'SELECT id, name 
											FROM component';
						//defenição de uma variavel igualada a uma query que obtem o id e o name da tabela component

//	$wpdb - por default é inicializada para conectar a base de dados sendo que esta variavel terá de ser
// defenida como global ou superglobal
// get_Results - obtem resultado de multiplas linhas de uma tabela presente na BD											
						$result_componente = $wpdb->get_results($query_componente);
						echo $result_componente;
												if ($result_componente != 0) //se $result_component diferente de 0
						{
							foreach($result_componente as $row) //renomeação de $result_componente para $row
							{
								?><option value="<?php echo $row->id; ?>"> <?php echo $row->name; ?></option>
								<!-- value= $row->id - define o valor do option value para o valor actual na base de dados
								$row->name - imprime o nome do component baseado no seu ID-->

							<?php
							}} 
							else
							echo '<option value=""><center><u>Nenhum componente encontrado.</center></u></option>';
							//caso nao existam componentes mostra a mensagem acima
						
						?>
						</select></td>
					</tr>
				     <tr align="center">
				        <td><h2><b>Tipo do campo do formulário</h2></b></td>
				        <td>
				        	<ul>
				        <!-- lista com todos os tipos de campo de formulário presentes no atributo form_field_type-->
				        	<input type="radio" name="tipocampo" value="text">text
						        <?php
								?>
					</ul>
				</td>
	        <tr align="center">
	        	<td><h2><b>Tipo de unidade</h2></b></td>
	        	<td><select name="tipounidade">
			<?php
//lista com os nomes de todos os tipos de unidade presentes na tabela prop_unit_type
// Dropdown - tipo unidade
			$query_tipounidade = "SELECT id, name 
									FROM prop_unit_type";
			//defenição de uma variavel igualada a uma query que obtem o id e o name da tabela component

//	$wpdb - por default é inicializada para conectar a base de dados sendo que esta variavel terá de ser
// defenida como global ou superglobal
// get_Results - obtem resultado de multiplas linhas de uma tabela presente na BD										
			$result_tipounidade = $wpdb->get_results($query_tipounidade);
			
//num_rows numero de linhas resultantes da ultima query			
			if ($wpdb->num_rows > 0) 
			{
				foreach($result_tipounidade as $tipounidade) //renomeação de$result_tipounidade para $tipounidade
							{
					?><option value="<?php echo $tipounidade->id?>"><?php echo $tipounidade->name?></option>
					<!-- value= $tipounidade->id define o valor do option value para o valor actual na base de dados
								$tipounidade->name imprime o nome do component baseado no seu ID-->
				<?php
				}
			}else{
				?><option value=""><center><u>Nenhum tipo de unidade encontrado.</u></center></option>
			<?php } ?>
		</select></td>
			</tr>
	        <tr align="center">
	        	<td><h2><b>Ordem do campo no formulário</h2></b></td>
	        	<td><input class="textbox" type = 'text' name='ordemcampo' placeholder='Número superior a 0'/></td></tr>
	        <tr align="center">

	        	<td><h2><b>Obrigatório:</h2></b></td>

<!-- apresentar as duas opções, sendo que na primeira o valor 
do campo no formulário na 1ª opção é 1 e na 2ª opção é 0:-->
	        	<td><input type = 'radio' name='obrigatorio' value="1"/>Sim
	        	<br><input type = 'radio' name='obrigatorio' value="0"/>Não</td></tr>
	        <tr align="center">
	        	<td colspan="2">
	        	<input type ="submit" name="submit" value="Inserir propriedade"/>
	        		<input type = "hidden" name = "estado" value ="inserir"/></td>
	        </tr>
	       </table>
	     </form></center>
	        
	         <?php
			 
			}
		
			if($estado == "inserir")
			{
				
				$erro = false;
			
/*Verificações*/
				if(empty($nome) || empty($tipovalor) || empty($componente) || empty($tipocampo) || empty($ordemcampo) || empty($obrigatorio))
				// caso algum dos campos do formularios estejam vazios 
				{
					$erro = true; // define o valor da variavel $erro para true e mostra a mensagem abaixo
							echo "Não tem os campos minimos preenchidos.";
		}

//is_numeric - verifica se a variavel é um numero ou uma strig numerica				
				if(!empty($ordemcampo) && !is_numeric($ordemcampo) && ($ordemcampo) < 1)
					//caso $ordemcampo não esteja vazia(tem valor) e 
					//esta nao seja numerica e 
					//esta seja menor que 1
				{
					$erro = true; // define o valor da variavel $erro para true e mostra a mensagem abaixo
					echo "Introduza um número válido no campo ordem do campo no formulário.";
				}
				
				if(!$erro)//caso não dê erro
				{

//	$wpdb - por default é inicializada para conectar a base de dados sendo que esta variavel terá de ser
// defenida como global ou superglobal					
					global $wpdb;
					//defenição de variaveis
					$nomePropriedade = $_POST['nomePropriedade'];

//preg_replace Realiza uma pesquisa por uma expressão regular e a substitui.
					$string = preg_replace('/[^a-z0-9_ ]/i', '', $nomePropriedade);
					$idComponente = $_POST['idComponente'];

					$idPropriedadeQuery = $wpdb->get_results("SELECT id 
															  FROM property 
															  ORDER BY id DESC LIMIT 1");
					//defenição de uma variavel igualada a uma query que obtem o id da tabela property

					$idPropriedade = $idPropriedadeQuery['0']->id;
					$nomeComponente = $wpdb->get_results("SELECT id, name 
														  FROM component 
														  WHERE id = '$idComponente'");
					//defenição de uma variavel igualada a uma query que obtem o id e o name da tabela component
					//igualando o valor do id na tabela da BD ao valor da variavel $idcmoponente (formulario)

//mb_substr - obtem parte de uma string

					$nomeComponenteCurto = mb_substr($nomeComponente['0']->name, 0, 3); //obtem as 3 primeiros caracteres do nome do componente
					$nomecampo = "" .$nomeComponenteCurto. "-" .$idPropriedade. "-" .preg_replace('/\s/', '_', $string);
					//string resultante da concatenação dos três primeiros caracteres do componente a que esta propriedade vai pertencer, seguido de um traço,
					//seguido do id da propriedade, seguido de outro traço e seguido do nome da propriedade especificado no respetivo campo pelo utilizador
					//sendo que este nome deverá ser transformado numa cadeia de caracteres só com catacteres puramente ascii 
					//(usando-se por exemplo este código: $string = preg_replace('/[^a-z0-9_ ]/i', '', $string);) - MOODLE

					$tipovalor = $_POST['tipovalor'];
					$tipocampo = $_POST['tipocampo'];
					$tipounidade = $_POST['tipounidade'];
					$ordemcampo = $_POST['ordemcampo'];
					$obrigatorio = $_POST['obrigatorio'];

					?><h3><b>Gestão de Propriedades - Inserção</b></h3><?php

					if($tipounidade == "NULL"){

//$wpdb->query query que pretendemos executar
						$query = $wpdb->query("INSERT INTO property 
(id, name, component_id, value_type, form_field_name, form_field_type, unit_type_id, form_field_size, form_field_order, mandatory, state, comp_fk_id) 
											   VALUES 
(NULL, '$nomePropriedade', '$idComponente', '$tipovalor', '$nomecampo', '$tipocampo', NULL, NULL, '$ordemcampo', '$obrigatorio', 'active', '$idComponente')");
					//defenição de uma variavel igualada a uma query que insere os valores defenidos pelo utilizador no formulário na da tabela property

//insert_id - ID generado para uma coluna de AUTO_INCREMENT pela query de INSERT mas recente
						$idPropriedade = $wpdb->insert_id;
						$nomecampo = "" .$nomeComponenteCurto. "-" .$idPropriedade. "-" .preg_replace('/\s/', '_', $string);
						//variavel $nomecampo obtem o valor que resulta da cocatenção dos 3 caraters principais do componente
						// seguido do id da propriedade e o nome desta que foi expeificado pelo utilizador.
						//exemplo ABC_01_Primeirapropriedade

//$wpdb->query query que pretendemos executar
//Nota: como só se pode saber o id da propriedade depois de inseri-la, a solução mais segura e 
//simples será fazer primeiro um insert com uma string idêntica à especificada mas sem o id e, 
//logo de seguida , realizar um update já com a string contendo o id,
						$wpdb->query("UPDATE  property 
									  SET  form_field_name =  '$nomecampo' 
									  WHERE  property.id =$idPropriedade;"); //ID da propriedade da tabela é = ao valor da variavel $idpropriedade
					}
					else{//caso a variavel $tipounidade não tenha o valor NULL

						$query = $wpdb->query("INSERT INTO 
property (id, name, component_id, value_type, form_field_name, form_field_type, unit_type_id, form_field_size, form_field_order, mandatory, state, comp_fk_id) 
											   VALUES 
(NULL, '$nomePropriedade', '$idComponente', '$tipovalor', '$nomecampo', '$tipocampo', '$tipounidade', NULL, '$ordemcampo', '$obrigatorio', 'active', '$idComponente')");
						//defenição de uma variavel igualada a uma query que insere os valores defenidos pelo utilizador no formulário na da tabela property

//insert_id - ID generado para uma coluna de AUTO_INCREMENT pela query de INSERT mas recente						
						$idPropriedade = $wpdb->insert_id;
						$nomecampo = "" .$nomeComponenteCurto. "-" .$idPropriedade. "-" .preg_replace('/\s/', '_', $string);
						//variavel $nomecampo obtem o valor que resulta da cocatenção dos 3 caraters principais do componente
						// seguido do id da propriedade e o nome desta que foi expeificado pelo utilizador.
						//exemplo ABC_01_Primeirapropriedade

//$wpdb->query query que pretendemos executar
//Nota: como só se pode saber o id da propriedade depois de inseri-la, a solução mais segura e 
//simples será fazer primeiro um insert com uma string idêntica à especificada mas sem o id e, 
//logo de seguida , realizar um update já com a string contendo o id,
						$wpdb->query("UPDATE  property 
									  SET  form_field_name =  '$nomecampo' 
									  WHERE  property.id =$idPropriedade;");//ID da propriedade da tabela é = ao valor da variavel $idpropriedade
					}
					if(!$query)
					//caso a query contenha campos mal preenchidos mostra os campos preenchidos 
					//juntamente com os valores introduzidos pelo utilizados e uam mensagem de erro
					{ 
						echo "<br><center><b>Nome:</b>  "; echo $nomePropriedade; echo "<hr>";
						echo "<b>ID:</b>  "; echo $idComponente; echo "<hr>"; 
						echo "<b>Tipo de Valor:</b>  "; echo $tipovalor; echo "<hr>";
						echo "<b>Nome do Campo:</b>  "; echo $nomecampo; echo "<hr>";
						echo "<b>Tipo de Campo:</b>  "; echo $tipocampo; echo "<hr>";
						echo "<b>Ordem do Campo:</b>  "; echo $ordemcampo; echo "<hr>";
						echo "<b>Obrigatorio:</b> "; echo $obrigatorio; echo "</center><hr>"; 
						$erro = true;

//Retorna o texto da mensagem de erro da operação MySQL anterior
						echo "<br><center><u>Ocorreu um erro:</u></center><br> ".mysql_error()."<br>";
					}
				
			
					if($erro)
					{
						back();
					}
					else
					{
					echo '<center><u>Inseriu os dados do novo tipo de unidade com sucesso.</u></center><br><br>';
					echo '<center>Clique em <b>Continuar</b> para avançar para avançar.</center><br><br>';
					echo '<center><a href="gestao-de-propriedades"><input type="button" Value="Continuar"></a></center>';
				
					}
				}
				else
				{
					back();
				}
				
			}
			
		}
		
		else
		{
			echo '<center><u>Não tem autorização para aceder a esta página.</u></center>';
		}
	?>
</html>