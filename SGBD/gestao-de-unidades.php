<?php	
	require_once("custom/php/common.php");
	
	if(!is_user_logged_in() || !current_user_can('manage_unit_types'))
	{
?>
		<p>Não tem permissão para aceder a esta página. Tem de efetuar <a href=><?php wp_loginout("gestao-de-unidades")?> </a>.</p>
		
		
/*<script>
function validateForm() {
    var x = document.forms["gestao_de_unidades"]["name"].value;
    if (x == null || x == "" || x.search(/^[A-Z ]+$/i)) {
        alert("Não insira valores numericos.");
        return false;
    }
}   
</script>*/
<?php         
	}
	else
	{
		if(!isset($_REQUEST['estado']) || $_REQUEST['estado'] == "") 
		{
			$sql_prop_unit_type = "SELECT * FROM prop_unit_type ORDER BY name";
			$res_prop_unit_type = mysqli_query($link, $sql_prop_unit_type);
			if(mysqli_num_rows($res_prop_unit_type) == 0)
				echo "Não há tipos de unidades.";
			else
			{		
?>
				<table>
				 <tr>
				  <th>id</th>
				  <th>unidade</th>
				 </tr>
<?php	
					while($linha = mysqli_fetch_array($res_prop_unit_type))//é guardado na variavel linha um array de resultado.
					{
?>
						<tr>
						 <td> <?php echo $linha['id']; ?> </td>
						 <td> <?php echo $linha['name']; ?> </td>
						</tr>
<?php 
					} 
?>
				</table>
<?php				//inserção de um novo tipo de unidade
			} 

		}				
		if($_GET['estado'] == "introducao")
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
				<input type="submit" value="Inserir unidade"onclick="alphanumeric(document.form1.text1)">
				</p>
			    
				</fieldset>
			    </form>
			
			

			
<?php           
		}
		else if($_REQUEST['estado'] == "inserir") // Verifica o estado se é igual a inserir.
		{
?>
			<h3><b>Gestao de unidades - Insercao</b></h3>
<?php
			$nome_inserido = mysqli_real_escape_string($link, $_REQUEST['nome']);
			$submit = mysqli_real_escape_string($link, $_REQUEST['submit']);
			
			if(empty($nome_inserido))
			{
?>			

<?php	
				back();
				
			}
		else if(empty($submit))
			{
?>			

<?php	
				back();
				
			}
			else
			{
						
				
			$inserir_unidade ="INSERT INTO prop_unit_type (name) VALUES ('$nome_inserido')";
			$resultado_inserido = mysqli_query($link, $inserir_unidade);
?>
				<p>Inseriu os dados de novo tipo de unidade com sucesso.</p>
				<p>Clique em <a href="gestao-de-unidades">Continuar</a> para avancar.</p>
<?php	
	
			}
		}
	}
?>



