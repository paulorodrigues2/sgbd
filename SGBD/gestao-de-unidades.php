<!--- Permite com que este script ordene cada coluna existente.

<link rel="stylesheet"  type="text/css" href="//cdn.datatables.net/1.10.10/css/jquery.dataTables.min.css"/> 
<script src="jquery-1.11.2.js"></script>
<script type=text/javascript" src="//cdn.datatables.net/1.10.10/js/jquery.dataTables.min.js"></script>
<script type="text/javascript">

	$(document).ready(function(){
		$('#datatable').dataTable();
	}
</script>
------------------------------->
<?php	
	require_once("custom/php/common.php");
	
	
	
	if(!is_user_logged_in() || !current_user_can('manage_unit_types'))
	{
?>
		<p>N�o tem permiss�o para aceder a esta p�gina. Tem de efetuar <a href=><?php wp_loginout("gestao-de-unidades")?> </a>.</p>
		
<?php         
	}
	else
	{
		if(!isset($_REQUEST['estado']) || $_REQUEST['estado'] == "") 
		{
			$sql_prop_unit_type = "SELECT * FROM prop_unit_type ORDER BY name";
			$res_prop_unit_type = mysqli_query($link, $sql_prop_unit_type);
			if(mysqli_num_rows($res_prop_unit_type) == 0)
				echo "N�o h� tipos de unidades.";
			else
			{		
?>
				
				<table class="mytable" id="datatable">
				<thead>
				 <tr>
				  <th>id</th>
				  <th>unidade</th>
				 </tr>
				 </thead>
				 <tbody>
<?php	
					while($linha = mysqli_fetch_array($res_prop_unit_type))//� guardado na variavel linha um array de resultado.
					{
?>
						<tr>
						 <td> <?php echo $linha['id']; ?> </td>
						 <td><?php echo $linha['name'];?></td>
						</tr>
<?php 
					} 
?>				</tbody>
				</table>
<?php				
			}				
			
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
		if($_REQUEST['estado'] == "inserir") // Verifica o estado se � igual a inserir.
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
						
				//Come�a a transi��o apenas de escrita(inser��o de valores) Returns TRUE on success or FALSE on failure.
				//mysqli_begin_transaction($link, MYSQLI_TRANS_START_READ_WRITE);
				
			$inserir_unidade ="INSERT INTO prop_unit_type (name) VALUES ('$nome_inserido')";
			$resultado_inserido = mysqli_query($link, $inserir_unidade);
			//Transi��o feita com sucesso.
				mysqli_commit($link);
?>
				<p>Inseriu os dados de novo tipo de unidade com sucesso.</p>
				<p>Clique em <a href="gestao-de-unidades">Continuar</a> para avancar.</p>
<?php	
	
			}
		}
	}
?>



