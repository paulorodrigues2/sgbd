<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<?php 
	require_once("custom/php/common.php");
	$estado = $_POST["estado"];
	if ( is_user_logged_in() && current_user_can('manage_properties') )
	{
		if(empty($estado))
		{
			?><h3>Gestão de Propriedades - Introdução</h3><?php
			$nome = $_POST["nome"];
			$tipovalor = $_POST["tipovalor"];
			$componente = $_POST["componente"];
			$tipocampo = $_POST["tipodocampo"];
			$nomecampo = $_POST["nomecampo"];
			$tipounidade = $_POST["tipounidade"];
			$ordemcampo = $_POST["ordemcampo"];
			$obrigatorio = $_POST["obrigatório"];
			$estadoform = $_POST["estadoform"];
			
			$query_propriedades = "SELECT * 
									FROM property 
									ORDER BY name";
			$result_propriedades = $wpdb->get_results($query_propriedades);
			if($result_propriedades)
			{?>
			<table>
				<tr>
					<td><strong>Componente</strong></td>
					<td><strong>ID</strong></td>
					<td><strong>Propriedade</strong></td>
					<td><strong>Tipo de valor</strong></td>
					<td><strong>Nome do campo no formulário</strong></td>
					<td><strong>Tipo do campo no formulário</strong></td>
					<td><strong>Tipo de unidade</strong></td>
					<td><strong>Ordem do campo no formulário</strong></td>
					<td><strong>Obrigatório</strong></td>
					<td><strong>Estado</strong></td>
					<td><strong>Ação</strong></td>
				</tr>
            <?php foreach($result_propriedades as $row)
				{
					?>
				<tr>
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
			echo 'Não há propiedades especificadas.<br>';
		}?>
        		<form method="post" action="">
        <table>
        <tr><td><label>Nome da propriedade: </label></td>
        <td><input type = 'text' name='nomePropriedade' placeholder='Nome da propriedade'/></td></tr>
        <tr><td><label>Tipo de valor: </label></td>
        	<td>
        		<ul>
        			<input type="radio" name="tipovalor" value="int">int<br>
        			<input type="radio" name="tipovalor" value="enum">enum<br>
        			<input type="radio" name="tipovalor" value="text">text<br>
        			<input type="radio" name="tipovalor" value="bool">bool<br>
        <?php
		/*$enumValue = getEnumValues('property', 'value_type');
			foreach($enumValue as $tipovalor)
			{	
				?><li><?php echo $tipovalor;?><input type='radio' name='tipovalor' value='<?php echo $tipovalor;?>'/> <?php echo $tipovalor;?></li>
				<?php
			}*/
		?></ul></td></tr>
        <tr><td><label>Componente: </label></td>
        <td><select name="idComponente">
		<?php
		
		$query_componente = 'SELECT id, name 
							FROM component';
		$result_componente = $wpdb->get_results($query_componente);
		echo $result_componente;
		
		if ($result_componente != 0) 
		{
			foreach($result_componente as $row) 
			{
				?><option value="<?php echo $row->id; ?>"> <?php echo $row->name; ?></option>
			<?php
			}} 
			else
			echo '<option value="">Nenhum componente encontrado.</option>';
		
		?>
		</select></td>
		</tr>
        <tr>
        	<td><label>Tipo do campo do formulário: </label></td>
        	<td>
        		<ul>
        			<input type="radio" name="tipocampo" value="text">text
		        <?php
				/*$enum_array = getEnumValues('property', 'form_field_type'); 
					foreach($enum_array as $tipocampo)
					{
						?><input type='radio' name='tipocampo' 
						<?php if (isset($tipo) && $tipo==$tipocampo) 
						echo "checked";?> value='<?php echo $tipocampo;?>' />
						<?php echo $tipocampo;?>
						<?php
					}*/
				?>
				</ul>
			</td>
        <tr>
        	<td><label>Tipo de unidade: </label></td>
        	<td><select name="tipounidade">
		<?php
		// Dropdown - tipo unidade
		$query_tipounidade = "SELECT id, name 
								FROM prop_unit_type";
								$result_tipounidade = $wpdb->get_results($query_tipounidade);
		
		if ($wpdb->num_rows > 0) 
		{
			foreach($result_tipounidade as $tipounidade) 
			{
				?><option value="<?php echo $tipounidade->id?>"><?php echo $tipounidade->name?></option>
			<?php
			}
		}else{
			?><option value="">Nenhum tipo de unidade encontrado.</option>

		<?php } ?>
			
		</select></td>
		</tr>
        <tr>
        	<td><label>Ordem do campo no formulário: </label></td>
        	<td><input type = 'text' name='ordemcampo' placeholder='Número maior que 0'/></td></tr>
        	<tr><td><label>Obrigatório: </label></td>
        	<td><input type = 'radio' name='obrigatorio' value="1"/>Sim   <input type = 'radio' name='obrigatorio' value="0"/>Não</td></tr>
        <tr>
        	<td><td></td></td></tr>
        	<tr><td><input type ="submit" name="submit" value="Inserir propriedade"/></td>
        	<td><input type = "hidden" name = "estado" value ="inserir"/></td>
        </tr>
        </table>
        </form>
        
         <?php
		 
		}
	
		if($estado == "inserir")
		{
			
			$erro = false;
		
			/*//Verificações
			if(empty($nome) || empty($tipovalor) || empty($componente) || empty($tipocampo) || empty($ordemcampo) || empty($obrigatorio))
			{
				$erro = true;
						echo "Não tem os campos minimos preenchidos.";
	}
			
			if(!empty($ordemcampo) && !is_numeric($ordemcampo) && ($ordemcampo) < 1)
			{
				$erro = true;
				echo "Introduza um número válido no campo ordem do campo no formulário.";
			}*/
			
			if(!$erro)
			{
				global $wpdb;
				$nomePropriedade = $_POST['nomePropriedade'];
				$string = preg_replace('/[^a-z0-9_ ]/i', '', $nomePropriedade);
				$idComponente = $_POST['idComponente'];
				$idPropriedadeQuery = $wpdb->get_results("SELECT id 
														  FROM property 
														  ORDER BY id DESC LIMIT 1");
				$idPropriedade = $idPropriedadeQuery['0']->id;
				$nomeComponente = $wpdb->get_results("SELECT id, name 
													  FROM component 
													  WHERE id = '$idComponente'");
				$nomeComponenteCurto = mb_substr($nomeComponente['0']->name, 0, 3);
				$nomecampo = "" .$nomeComponenteCurto. "-" .$idPropriedade. "-" .preg_replace('/\s/', '_', $string);
				$tipovalor = $_POST['tipovalor'];
				$tipocampo = $_POST['tipocampo'];
				$tipounidade = $_POST['tipounidade'];
				$ordemcampo = $_POST['ordemcampo'];
				$obrigatorio = $_POST['obrigatorio'];

				?><h3>Gestão de Propriedades - Inserção</h3><?php
				if($tipounidade == "NULL"){
					$query = $wpdb->query("INSERT INTO property (id, name, component_id, value_type, form_field_name, form_field_type, unit_type_id, form_field_size, form_field_order, mandatory, state, comp_fk_id) 
						VALUES (NULL, '$nomePropriedade', '$idComponente', '$tipovalor', '$nomecampo', '$tipocampo', NULL, NULL, '$ordemcampo', '$obrigatorio', 'active', '$idComponente')");
					$idPropriedade = $wpdb->insert_id;
					$nomecampo = "" .$nomeComponenteCurto. "-" .$idPropriedade. "-" .preg_replace('/\s/', '_', $string);
					$wpdb->query("UPDATE  property 
								  SET  form_field_name =  '$nomecampo' 
								  WHERE  property.id =$idPropriedade;");
				}
				else{
					$query = $wpdb->query("INSERT INTO property (id, name, component_id, value_type, form_field_name, form_field_type, unit_type_id, form_field_size, form_field_order, mandatory, state, comp_fk_id) 
						VALUES (NULL, '$nomePropriedade', '$idComponente', '$tipovalor', '$nomecampo', '$tipocampo', '$tipounidade', NULL, '$ordemcampo', '$obrigatorio', 'active', '$idComponente')");
					$idPropriedade = $wpdb->insert_id;
					$nomecampo = "" .$nomeComponenteCurto. "-" .$idPropriedade. "-" .preg_replace('/\s/', '_', $string);
					$wpdb->query("UPDATE  property 
								  SET  form_field_name =  '$nomecampo' 
								  WHERE  property.id =$idPropriedade;");
				}
				if(!$query){ 
					echo $nomePropriedade; echo "<br>";
					echo $idComponente; echo "<br>"; 
					echo $tipovalor; echo "<br>";
					echo $nomecampo; echo "<br>";
					echo $tipocampo; echo "<br>";
					echo $ordemcampo; echo "<br>";
					echo $obrigatorio; echo "<br>"; 
					$erro = true;
					echo "Ocorreu um erro: ".mysql_error()."<br>";
				}
			
		
				if($erro){
					voltar();
				}
				else{
					echo 'Inseriu os dados de novo tipo de unidade com sucesso.<br>';
					echo 'Clique em <strong><a href="gestao-de-propriedades">Continuar</a></strong> para avançar.';
				}
			}
			else
			{
				voltar();
			}
		}	
	}	
	else
	{
		echo 'Não tem autorização para aceder a esta página.';
	}
?>
</html>
