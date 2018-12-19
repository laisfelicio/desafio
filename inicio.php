<?php

 require('.\classifica.php');
?>

<!DOCTYPE html> 
<html lang="pt-br">     
	<head>         
		<meta charset="utf-8" />         
		<title>Tickets</title>     
	</head>     
	
	<body>   
		<form action="inicio.php"  method="post" >
			Selecione:<br>
		    <label>Paginação<input type="radio" name="recurso" id="pag"  value="paginacao"> </label> <br>
			<label>Filtro por data <input type="radio" name="recurso" id="dat"  value="data"> </label> <br>
			<label>Filtro por prioridade <input type="radio" name="recurso" id="prio"  value="prioridade"> </label> <br>
			<label> Ordenação por data de criação <input type="radio" name="recurso" id="orddata"  value="ordena_data"> </label> <br>
			<label> Ordenação por data de alteração <input type="radio" name="recurso" id="orddataalt"  value="ordena_data_alt"> </label> <br>
			<label> Ordenação por prioridade <input type="radio" name="recurso" id="ordprio"  value="ordena_prioridade"> </label> <br>
			<label> Ordenação por pontuação <input type="radio" name="recurso" id="ordpont"  value="ordena_pontuacao"> </label> <br><br>
			 Quantidade tickets por página: <input type="number" name="qtd_tickets"><br>
			 Data inicial: <input type="text" name="data_ini" value="<?php echo date('d/m/Y');?>"> Data Final: <input type="text" name="data_fim" value="<?php echo date('d/m/Y');?>"><br>
			 Prioridade:
			 <select name="prioridade">
				<option value="Alta">Alta</option>
                <option value="Normal">Normal</option>
			 </select><br>
			 <input type="submit" value="Atualizar"> <br><br>
		</form>
	</body>
</html>

<?php 

 
 include('Ticket.php');
 $tempTicket = new Ticket();
 $tempTicket->arquivo_json('tickets.json');
 $paginacao = true;
 $tamanhoVetor = $tempTicket->conta_tickets();
 $arrayJson = $tempTicket->arrayArquivo;

 if($_POST["recurso"] == null && $_GET["auxRec"] == null)
 {
	 //mostra todos os tickets 
	 $tempTicket->mostrar(0,$tamanhoVetor-1);
 }
 if ($_POST["recurso"] == "paginacao" || $_GET["auxRec"]== "paginacao")
 {
		 if ($_POST["qtd_tickets"] == null) 
		 {
			 if ($_GET['auxQtd'] == null)
			 {
				$qtdPag = 0;
			 }
			 else
			 {
				 
				 $qtdPag = $tamanhoVetor/$_GET['auxQtd'];
			 }
			 
			 if (!is_int($qtdPag))
			 {
				 $qtdPag = intval($qtdPag) + 1;
			 }
		 }
		 else
		 {
			 
			 $qtdPag = $tamanhoVetor/$_POST["qtd_tickets"];
			 
			 if (!is_int($qtdPag))
			 {
				 $qtdPag = intval($qtdPag) + 1;
			 }
				 
			 
		 }
		 
		 
		 if ($_POST["qtd_tickets"] == null)
		 {	 
			if ($_GET["auxQtd"] <> null)
			{
				$auxQ = $_GET["auxQtd"];
			}
			else
			{
				$auxQ = $tamanhoVetor;
			}
		 }
		 else
		 {
			 $auxQ = $_POST["qtd_tickets"];
		 }
		 

		 if ($_POST["qtd_tickets"] == null && $_GET['auxT'] == null)
		 {
			 $keyInicial = 0;
		 }
		 else
		 {
			 if ($_GET['auxT'] <> null)
			 {
				 $keyInicial = $_GET['auxT'];
			 }
			 else
			 {
				 $keyInicial = 0;
			 }
		 }
		 $keyFinal = $keyInicial + $auxQ-1; 
		 
		 //fiz isso para o keyfinal não passar do tamanho do vetor
		 if ($keyFinal > $tamanhoVetor)
			 $keyFinal = $tamanhoVetor - 1;
		 
		 if ($_POST["recurso"] <> null)
		 {
			 $recursoAux = $_POST["recurso"];
		 }
		 else
	     {
			 $recursoAux = $_GET["auxRec"];		 
		 }
		 
		 
		 $tempTicket->mostrar($keyInicial, $keyFinal);
		 
		
		 if ($qtdPag <> 0)
		 {
				 for($i = 0; $i < $qtdPag; $i++)
				 {
					 $auxT = $auxQ * $i;
					 
					 ?>
					 <a href="inicio.php?auxT=<?php echo $auxT ?>&auxQtd=<?php echo $auxQ;?>&auxRec=<?php echo $recursoAux;?>"> <?php echo $i+1; ?></a>
					 <?php
				 }
		 }
  }
  
  if ($_POST["recurso"] == "data")
  {
		$tempTicket->filtra_ticket('data', $_POST['data_ini'], $_POST['data_fim']);
  }
  
   if ($_POST["recurso"] == "prioridade")
  {
		$tempTicket->filtra_ticket('prioridade', $_POST['prioridade'], $_POST['prioridade']);
  }
  
  if($_POST["recurso"] == "ordena_data")
  {
		$tempTicket->ordena_tickets('data_criacao');
  }
   
  if($_POST["recurso"] == "ordena_data_alt")
  {
		$tempTicket->ordena_tickets('data_alt');
  }
  
  if($_POST["recurso"] == "ordena_prioridade")
  {
		$tempTicket->ordena_tickets('prioridade');
  } 
 
  if($_POST["recurso"] == "ordena_pontuacao")
  {
		$tempTicket->ordena_tickets('pontuacao');
  }
 
 

?>