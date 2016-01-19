<?php	
	require_once("custom/php/common.php");
?>
<?php
	$state = $_POST["state"];	
	if ( is_user_logged_in() && current_user_can('Insert_values') ){ 
?>
		<p>Não tem autorização para aceder a esta página. Tem de efetuar <?php wp_loginout("insercao-de-valores")?>.</p>
<?php        
	
	}

	else
	{
		if(!isset($_REQUEST['state']) || $_REQUEST['state'] == "") 
		{
	
?>						
			<br>
			<h3><b><center>Inserção de valores - escolher componente/formulário customizado</center></b></h3>
			<br> 
<?php
			$comp_query = "SELECT comp_type.id as id_componente, comp_type.name as tipo_componente
			from comp_type ";
				
			$comp_query_resultado = mysqli_query($link, $comp_query);

?>
			<ul sytle = "list-style-type:circle">
			<li>Componentes:</li>
<?php
			echo "<ul>";

			while($linha = mysqli_fetch_array($comp_query_resultado))
			{
?>		
				<li><?php echo $linha['tipo_componente'];?></li>

<?php
				$tipo_comp_query = "SELECT comp.id as component_id, comp.name as component_name
				from component as comp
				where comp.comp_type_id = ".$linha['id_componente']." ";

		        $tipo_comp_query_resultado = mysqli_query($link, $tipo_comp_query); //mostra $tipo_comp_query

				echo "<ul>";
				while($tipo_comp_query_resultado_array = mysqli_fetch_array($tipo_comp_query_resultado))
				{

?>	
					<li><a href="insercao-de-valores?state=introducao&comp=<?php echo $tipo_comp_query_resultado_array['component_id'];?>" >
				        		<?php echo '['.$tipo_comp_query_resultado_array['component_name'].']'; ?></a></li> 

<?php
				
				}

				echo "</ul>";

		  	}
	        
			echo"</ul>";


?>
			<li>Formulários customizados</li>

<?php 
			$form_query= "SELECT custom_f.name as custom_form_name, custom_f.id as custom_form_id 
			from custom_form as custom_f";

			$form_query_resultado = mysqli_query($link,$form_query);

			echo"<ul>";

			while($form_query_resultado_array = mysqli_fetch_array($form_query_resultado))
			{
?>
				<li><a href="insercao-de-valores?state=introducao&form=<?php echo $form_query_resultado_array['custom_form_id'];?>" >
				<?php echo '['.$form_query_resultado_array['custom_form_name']." ".']';
				?>
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
			$comp_id = mysqli_real_escape_string($link, $_SESSION['comp_id']);

			$sql_query_componente_nome = "SELECT comp.name as component_name 
			from component as comp 
			where comp.id = $comp_id";
			$sql_query_componente_nome_resultado = mysqli_query($sql_query_componente_nome);

			$sql_query_componente_nome_resultado_array = mysqli_fetch_array($sql_query_componente_nome_resultado);

			$_SESSION['comp_name'] = $sql_query_componente_nome_resultado_array['component_name'];
			$comp_name = mysqli_escape_string($_SESSION['comp_name']);

			$sql_query_componente_tipo = "SELECT comp.comp_type_id as component_type_id
			from component as comp 
			Where comp.id = $comp_id";

			$sql_query_componente_tipo_resultado = mysqli_query($link, $sql_query_componente_tipo);
			$sql_query_componente_tipo_resultado_array = mysqli_fetch_array($sql_query_componente_tipo_resultado);

			$_SESSION['comp_type_id'] = $sql_query_componente_tipo_resultado_array['component_type_id'];
			$component_type_id = mysqli_real_escape_string($link, $_SESSION['comp_type_id']);
?>			
			<h3><b><center>Insercao de valores - <?php echo $comp_name; ?></center></b></h3>		
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
			from property as prop, prop_unit_type as p_unit_type
			where prop.component_id = '$comp_id' and state = 'active' and prop.unit_type_id = p_unit_type.id";    
			
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




	    }


	    	if(empty($form_id))
			{
?>			

<?php	
				back();
				
			}
		else if(empty($comp_id))
		{
<?php

				back();

		}
		else if(empty($from_name))
			{
?>			

<?php	
				back();
				
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
