<html>
	<head>
		<meta http-equiv="content-type" content="text/html;charset=utf-8" />
    	<link rel="stylesheet" type="text/css" href="custom/css/ag.css">
    </head>
<?php require_once("custom/php/common.php");
global $wpdb; ?>

<script type="text/javascript">
(function($,W,D)
{
    var JQUERY4U = {};
    JQUERY4U.UTIL =
    {
        setupFormValidation: function()
        {
            //form validation rules
            $("#dados").validate({
                rules: {
                    name_text: "required",
                    name_bool:"required",
                    name_int: "required",
                    name_double: "required",
                    name_enum: "required" 
                },
                messages: {
                    name_text: "Insira os dados correctos.",
                    name_bool:"Insira os dados correctos",
                    name_int: "Insira os dados correctos",
                    name_double: "Insira os dados correctos",
                    name_enum: "Insira os dados correctos" 
                },
                
            });
			$( "button" ).click(function() {
			  alert( "Valid: " + form.valid() );
			});
        }
    }

    //when the dom has loaded setup form validation rules
    $(D).ready(function($) {
        JQUERY4U.UTIL.setupFormValidation();
    });

})(jQuery, window, document);
</script>


<?php if(is_user_logged_in() && current_user_can ('insert_values')){
	// se o utilizador está com o login efectuado e tem a capability "insert_values"

//isset - Verifica se a variável é definida. retorna FALSE se for usada uma variável com o valor NULL		
		if(!isset($_REQUEST['estado'])){ ?> 
		<!-- caso a variavel estado não tenha valor defenido
		Se não vier nenhum valor na variável (REQUEST) sobre o estado de execução-->

				<form action="" method="POST" novalidate="novalidate">
				<h3> <b>Inserção de Valores - escolher componente/formulário customizado <b> </h3>
				<li> Componentes: </li><br>
				<?php
					$sql_comp_type = "SELECT comp_type.name, comp_type.id 
									  FROM comp_type";
					//defenição de uma variavel igualada a uma query que obtem o comp_type.name e comp_type.id  da tabela comp_type


//	$wpdb - por default é inicializada para conectar a base de dados sendo que esta variavel terá de ser
// defenida como global ou superglobal
// get_Results - obtem resultado de multiplas linhas de uma tabela presente na BD
					$resultado_comp_type = $wpdb->get_results($sql_comp_type) or die(mysql_error());
					//$nlinhas_comp_type = mysql_num_rows($resultado_comp_type);

					foreach($resultado_comp_type as $row) { 
					//apresentar uma lista dos tipos de componentes e componentes existentes na BD
						echo "<ul>";
						if ($row) {
							echo "<li>".$row->name."</li>"; //valor da variavel name é "imprimida"

 									$sql_component = "SELECT component.name, component.id 
 													  FROM component
												  	  INNER JOIN comp_type ON comp_type.id = component.comp_type_id
												  	  WHERE comp_type.id =".$row->id.""; // com_type.id da BD terá de ser igual ao falor do id da $row
									//defenição de uma variavel igualada a uma query que obtem o comp_type.name e comp_type.id  da tabela component

//	$wpdb - por default é inicializada para conectar a base de dados sendo que esta variavel terá de ser
// defenida como global ou superglobal
// get_Results - obtem resultado de multiplas linhas de uma tabela presente na BD												  	  
								$resultado_component = $wpdb->get_results($sql_component) or die(mysql_error());

									foreach ($resultado_component as $row) {
										echo "<ul>";
										if($row){
											echo "<li><a href='insercao-de-valores?estado=introducao&comp=" . $row->id . "'>[" .$row->name. "]</a></li>";
//e em que cada item é uma ligação para o seguinte endereço: "insercao-de-valores?estado=introducao&comp=c"
//onde c ($row->id) é o id do respetivo componente nessa entrada 
//$row->name - imprime o nome do componente											
										}
										echo "</ul>";
									}
						}
						echo "</ul>";
					}
					echo "<br>";
					?>
					<li> <b>Formulários Customizados: </b></li> <br>
					<?php
							$sql_custom_form = "SELECT custom_form.name, custom_form.id
												FROM custom_form";
												//defenição de uma variavel igualada a uma query que obtem o custom_form.name e custom_form.id da tabela custom_form

//	$wpdb - por default é inicializada para conectar a base de dados sendo que esta variavel terá de ser
// defenida como global ou superglobal
// get_Results - obtem resultado de multiplas linhas de uma tabela presente na BD		
							$resultado_custom_form = $wpdb->get_results($sql_custom_form) or die(mysql_error());
							
							foreach ($resultado_custom_form as $row) {
							echo "<ul>";
								if($row){
									echo "<li><a href='insercao-de-valores?estado=introducao&form=" . $row->id . "'>[" .$row->name. "]</a></li>";
//e em que cada item é uma ligação para o seguinte endereço: "insercao-de-valores?estado=introducao&form=f"
//onde f ($row->id) é o id do respetivo componente nessa entrada 
//$row->name - imprime o nome do form										
								}
								echo "</ul>";
							}
								?>
					</form>
			<?php
		}

			if($_REQUEST['estado'] == "introducao"){ //Se o estado de execução é introducao

				$_SESSION['comp_id'] = $_REQUEST['comp'];
				//Guardar numa variável de sessão chamada  comp_id o valor que vem no vector REQUEST associado à variável comp

				$sql_componente = "SELECT name, comp_type_id
									FROM component 
									WHERE id = ".$_REQUEST['comp']; //id da BD deve ser igual ao valor da variavel comp
									//defenição de uma variavel igualada a uma query que obtem o name e o comp_type_id da tabela component

//	$wpdb - por default é inicializada para conectar a base de dados sendo que esta variavel terá de ser
// defenida como global ou superglobal
// get_Results - obtem resultado de multiplas linhas de uma tabela presente na BD	
				$resultado_componente = $wpdb->get_results($sql_componente) or die(mysql_error());
				
				$_SESSION['comp_name'] = $resultado_componente[0]->name;
				//Guardar numa variável de sessão chamada comp_name o nome do componente associado ao id da linha anterior

				$_SESSION['comp_type_id'] = $resultado_componente[0]->comp_type_id;
				//Guardar numa variável de sessão chamada comp_type_id o id do respetivo tipo de componente
				?>

				
				<form name="comp_type_<?=$_SESSION['comp_type_id'];?>_comp_<?=$_SESSION['comp_id']?>" 
				action="?insercao-de-valores?estado=validar&comp=<?=$_SESSION['comp_id']?>" method="post" novalidate="novalidate">
	<!-- o NOME DO FORMULARIO é uma string do género comp_type_x_comp_y em que x é o id do tipo de componente(['comp_type_id'])
	 e y o id de componente(['comp_id']) .
	 o ACTION DO FORMULARIO tem o seguite formato insercao-de-valores?estado=validar&comp=c" onde c é o id do componente(['comp_id'])-->



				<h3> Inserção de valores - <?php echo $_SESSION['comp_name'] ?> </h3>
								
				<?php
				$sql_property = ("SELECT property.id, property.component_id, property.value_type, property.form_field_type, property.state, component.name, property.name name_property  
								FROM property
								INNER JOIN component on property.component_id = component.id
								WHERE property.component_id =".$_SESSION['comp_id']." AND property.state ='active'");
								// id presente na BD tera de ser igual ao valor da variavel $_SESSION['comp_id'] e o estado desta terá de ser activo.

				//defenição de uma variavel igualada a uma query que obtem o
				//property.id, property.component_id, property.value_type, property.form_field_type, property.state, component.name, property.name name_property 
				//da tabela property


//	$wpdb - por default é inicializada para conectar a base de dados sendo que esta variavel terá de ser
// defenida como global ou superglobal
// get_Results - obtem resultado de multiplas linhas de uma tabela presente na BD		
				$resultado_property = $wpdb->get_results($sql_property) or die(mysql_error());
				$i = 0;
				echo "<table>";
				echo "<tr>
						<td></td>
						<td></td>
						</tr>";
		
				foreach ($resultado_property as $row){
					switch ($row->value_type) {
						case 'text':
								echo "<tr>";
								echo "<td>" .$row->name_property.":"."</td>"; //imprime o nome da propriedade
								echo "<td>". "<input type =".$row->form_field_type."  name = 'name_text[]' >" ."</td>";
								//para o tipo text apresentar um input do tipo text ou textbox ("$row->form_field_type")
								//(conforme o tipo de campo especificado na BD)
								echo "</tr>";
							break;

						case 'bool':
								echo "<tr>";
								echo "<td>".$row->name_property.":"."</td>";//imprime o nome da propriedade
								echo "<td>". "<input type = 'radio' name = 'name_bool' >" ."</td>";
								//para o tipo bool apresentar um input do tipo radio
								echo "</tr>";
							break;
						
						case 'int':
								echo "<tr>";
								echo "<td>".$row->name_property.":"."</td>";//imprime o nome da propriedade
								echo "<td>"."<input type = 'text' name = 'name_int[]' >" ."</td>";
								//para os tipos intapresentar um input do tipo text
								echo "</tr>";
							break;
						
						case 'double':
								echo "<tr>";
								echo "<td>".$row->name_property.":"."</td>";
								echo "<td>". "<input type = 'text' name = 'name_double[]'  >" ."</td>";
								//para os tipos double apresentar um input do tipo text
								echo "</tr>";		
							break;
						
						case 'enum':
							echo "<tr>";
							echo "<td>".$row->name_property.":"."</td>";
							echo "<td>". "<input type =".$row->form_field_type."  name = 'name_enum[]'>". "</td>";
							//para o tipo enum apresentar um input do tipo radio, checkbox ou selectbox("$row->form_field_type")
							//(conforme o tipo de campo especificado na BD) 
							echo "</tr>";
							break;			
						
					}
				}

				echo "</table>";
				?>

				<input type = "hidden" name = "estado" value = "validar">
				<input type = "submit" type = "submit" value = "Submeter">
				<br></br>
				</form>
		<?php
		}

		if($_REQUEST['estado'] == "validar"){ 

			$_SESSION['comp_id'] = $_REQUEST['comp'];

				$sql_componente = "SELECT name, comp_type_id
									FROM component 
									WHERE id = ".$_REQUEST['comp'];
				//defenição de uma variavel igualada a uma query que obtem o name e o comp_type_id da tabela component

//	$wpdb - por default é inicializada para conectar a base de dados sendo que esta variavel terá de ser
// defenida como global ou superglobal
// get_Results - obtem resultado de multiplas linhas de uma tabela presente na BD		
				$resultado_componente = $wpdb->get_results($sql_componente) or die(mysql_error());
				
				$_SESSION['comp_name'] = $resultado_componente[0]->name; 
				//Guardar numa variável de sessão chamada comp_name o nome do componente associado ao id da linha anterior

				$_SESSION['comp_type_id'] = $resultado_componente[0]->comp_type_id;
				//Guardar numa variável de sessão chamada comp_type_id o id do respetivo tipo de componente
				?>


			<h3> Inserção de Valores - <?php echo $_SESSION['comp_name'] ?> - Validar </h3>
			<!--Apresentar o Sub-título (heading 3): Inserção de valores - xxx
			 - validar (onde xxx é o valor na variável de sessão comp_name)-->

<!-- defenicao de variaveis de verificação-->			 
			<?php
			$verificar_text = $_REQUEST['name_text']; //$a
			$verificar_bool = $_REQUEST['name_bool'];
			$verificar_int = $_REQUEST['name_int']; //$c
			$verificar_double = $_REQUEST['name_double']; //$d
			$verificar_enum = $_REQUEST['name_enum']; //$e
			$valido = true;

			foreach ($verificar_text as $a) {
				
				if (!isset($a)) { //valor da variavel seja nulo
					
					echo "<center><b>Erro: Dados inseridos estão incorrectos.</b></center>";
					$valido = false;
				}
			}

			foreach ($verificar_int as $c) {
							
				if (!isset($c)) {//valor da variavel seja nulo
					echo "<b><center>Erro: Dados inseridos estão incorrectos.</b></center>";
					$valido = false;
				}
			}			

			foreach ($verificar_double as $d) {
				
				if (!isset($d)) {//valor da variavel seja nulo
					echo "<b><center>Erro: Dados inseridos estão incorrectos.</b></center>";
					$valido = false;
				}
			}
			
			foreach ($verificar_enum as $e) {
				
				if (!isset($e)) {//valor da variavel seja nulo
					echo "<b><center>Erro: Dados inseridos estão incorrectos.</b></center>";
					$valido = false;
				}
			}
			
			if (!$valido) {
				back();
			}
			else{
				echo "Estamos prestes a inserir os dados abaixo na base de dados. Confirma que os dados estão correctos e pretende submeter os mesmos?";
				echo "<ul>";
				foreach ($verificar_text as $a) {
					echo "<li>".$a."</li>"; //imprime o valor da variavel
				}

				 if(!empty($verificar_bool)){
					echo "<li>".$verificar_bool."</li>"; //imprime o valor da variavel
				}

				foreach ($verificar_int as $c) {
					echo "<li>".$c."</li>"; //imprime o valor da variavel
				}

				foreach ($verificar_double as $d) {
					echo "<li>".$d."</li>"; //imprime o valor da variavel
				}

				foreach ($verificar_enum as $e) {
					echo "<li>".$e."</li>"; //imprime o valor da variavel
				}
				echo "</ul>";
				?>

				<form id="inserir" method="POST" action="?insercao-de-valores?estado=inserir&comp=<?=$_SESSION['comp_id']?>" >
				<!-- o action do formulário tem o seguite formato insercao-de-valores?estado=inserir&comp=c" onde c é o id do componente-->

				<?php
						foreach ($verificar_text as $a) {
							echo "<input type='hidden' name='name_text' value=".$a.">"; //defenição dos valores e nomes dos inputs
						}
							echo "<input type='hidden' name='name_bool' value=".$verificar_bool.">";//defenição dos valores e nomes dos inputs

						foreach ($verificar_int as $c) {
							echo "<input type='hidden' name='name_int' value=".$c.">";//defenição dos valores e nomes dos inputs
						}

						foreach ($verificar_double as $d) {
							echo "<input type='hidden' name='name_double' value=".$d.">";//defenição dos valores e nomes dos inputs
						}

						foreach ($verificar_enum as $e) {
							echo "<input type='hidden' name='name_enum' value=".$e.">";//defenição dos valores e nomes dos inputs
						}?>		

						<input type="hidden" name="estado" value="inserir">
						<input type="submit" name="submit" value="Submeter">
					</form>
					<?php
			}
		}

		if($_REQUEST['estado'] == "inserir"){
			$_SESSION['comp_id'] = $_REQUEST['comp'];
			//Guardar numa variável de sessão chamada comp_id o valor que vem no vector REQUEST associado à variável comp

				$sql_componente = "SELECT name, comp_type_id
									FROM component 
									WHERE id = ".$_REQUEST['comp'];
				//defenição de uma variavel igualada a uma query que obtem o name e o cpmp_type_id da tabela component

//	$wpdb - por default é inicializada para conectar a base de dados sendo que esta variavel terá de ser
// defenida como global ou superglobal
// get_Results - obtem resultado de multiplas linhas de uma tabela presente na BD	
				$resultado_componente = $wpdb->get_results($sql_componente) or die(mysql_error());
				
				$_SESSION['comp_name'] = $resultado_componente[0]->name;
				//Guardar numa variável de sessão chamada comp_name o nome do componente associado ao id da linha anterior

				$_SESSION['comp_type_id'] = $resultado_componente[0]->comp_type_id;
				//Guardar numa variável de sessão chamada comp_type_id o id do respetivo tipo de componente
				?>
			<h3> Inserção de Valores - <?php echo $_SESSION['comp_name'] ?> - Inserção </h3>
			<!--Sub-título deste estado (heading 3): Inserção de valores - xxx - inserção (onde xxx é o valor na variável de sessão comp_name)
			xxx = echo $_SESSION['comp_name']-->
		<?php



			$sql_buscar_id_property = "SELECT property.id
										FROM property 
									  	INNER JOIN component ON component.id = property.component_id 
									  	WHERE property.component_id = ".$_SESSION['comp_id']."";
			//defenição de uma variavel igualada a uma query que obtem o id da tabela property

//	$wpdb - por default é inicializada para conectar a base de dados sendo que esta variavel terá de ser
// defenida como global ou superglobal
// get_Results - obtem resultado de multiplas linhas de uma tabela presente na BD	
			$resultado_id_property = $wpdb->get_results($sql_buscar_id_property) or die(mysql_error());

			foreach ($resultado_id_property as $row){
					$sql_inserir = "INSERT INTO value (comp_inst_id, property_id, value, date, time, producer) 
									VALUES (".$_SESSION['comp_id'].", ".$row->id.", '%s', ".date('Y-m-d').", ".date('H:i:s').", ".wp_get_current_user()->user_login.")";
					//defenição de uma variavel igualada a uma query de inserção


//$wpdb->query query que pretendemos executar									
					$resultado_inserir = $wpdb->query($sql_inserir) or die(mysql_error());				
				}
				echo "<center><b>Inseriu o(s) valor(es) com sucesso.</center></b><br>";
				echo "<center><b>Clique em Voltar para voltar ao início da inserção de valores e poder escolher outro componente ou em Continuar a inserir valores neste componente se quiser continuar a inserir valores</center></b>";
				?>
					<a href="insercao-de-valores/">Voltar</a>
					<br><br>
					<a href="insercao-de-valores?estado=introducao&comp="<?php.$_SESSION['comp_id'].?>"">Continuar a inserir valores neste componente</a>
					<!-- Voltar é uma ligação para a página insercao-de-valores e 
					"Continuar a inserir valores neste componente" é uma ligação para o seguinte endereço: "insercao-de-valores?estado=introducao&comp=c" onde
					 c é o id do componente guardado em sessão 
					 c=$_SESSION[comp_id] -->
				
				<?php
		}
}

else{
	echo "Não tem autorização para aceder a esta página.";
}