<script type="text/javascript" src="<?php echo get_bloginfo('wpurl');?>/custom/js/anytime.js"></script>

<?php

function back()
	{	
		echo "<script type='text/javascript'>document.write(\"<a href='javascript:history.back()' class='backLink' title='Voltar atr&aacute;s'>Voltar atr&aacute;s</a>\");</script>
		<noscript>
		<a href='".$_SERVER['HTTP_REFERER']."‘ class='backLink' title='Voltar atr&aacute;s'>Voltar atr&aacute;s</a>
		</noscript>";
	}	
?>
<script>
function validateForm() {
	
    var x = document.forms["gestao_de_unidades"]["nome"].value;
    if (x == null || x == "" || x.search(/^[A-Z ]+$/i)) {
        alert("Insira apenas letras!");
        return false;
    }
}   
</script>
<?php
$link = mysqli_connect(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME);
 $clientsideval=0; 
 
?>
<?php error_reporting (E_ALL ^ E_NOTICE); ?>
