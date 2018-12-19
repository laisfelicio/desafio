<?php

	function remover_caracter($string) 
	{
   
		$string = preg_replace( '/[`^~\'"]/', null, iconv( 'UTF-8', 'ASCII//TRANSLIT', $string ) );
		return $string;
	}
	$palavrasPriAlta = "/(?i)reclam|data|precis|procon|reclameaqui|cheg|assin|tentativa|cancel|debit|defeito|sistema|senha|envia|pedido|confirm|produto|problema|ainda|troc|troq|solucao|solução|aguard|diferen|tent|receb|entreg|process|reclam|atras|prazo|compr|provid|estrag/";
	$palavrasPriNormal = "/(?i)elog|procur|duvida|dúvida|obrigad|saber|gentil|satisfeit|gost|recomend|sugeri|sugiro|sugest|parab|otimo|ótimo|informa|gostaria|disponivel|acompanha/";
	$expressoesNormais = "/(?i)bom\sdia|boa\starde|boa\snoite|sem\sassunto|como\sfazer|como\sposso|queria\ssaber|como\sest/";
	$expressoesAlta = "/(?i)nao\sfunciona|até\sagora|ate\sagora|ja\stent|já\stent/";
	$palavrasPrincAlta = "/(?i)reclam|defeito|problema|diferen|process|reclam|provid|estrag/";
	$palavrasPrincNormal = "/(?i)duvida|dúvida|como\sposso|quer\ssaber/";


	
	
	//pego o conteudo do arquivo json para trabalhar com a string
	$stringJson = implode ('', file ('.\tickets.json'));
	$json_arr = json_decode($stringJson, true);
	$conta = 0;
	
	foreach ($json_arr as $key => $value)
	{
		
		
		$contaAlta = 0;
		$contaNormal = 0;
		$pontuacao = 0;
		$prioridade ="";
		$auxKey = $key;
		foreach ($json_arr[$key]["Interactions"] as $mensagem)
		{
				$mens = remover_caracter($mensagem["Message"]);
				
				$assunto = remover_caracter($mensagem["Subject"]);
				
			if ($mensagem["Sender"] == "Customer")
			{
				
				$words = preg_split( '/ /', $mens, -1, PREG_SPLIT_NO_EMPTY );
				$wassunto = preg_split( '/ /', $assunto, -1, PREG_SPLIT_NO_EMPTY );
				foreach ( $words as $key => $value ) 
				{ 
						$words[ $key ] = trim( $value ); 
				}
				
				foreach ( $wassunto as $key => $value ) 
				{ 
						$wassunto[ $key ] = trim( $value ); 
				}

				$matchesPriAlta = preg_grep($palavrasPriAlta,$words);
				$matchesPriNormal = preg_grep($palavrasPriNormal, $words);
				$matchesAssunAlta = preg_grep($palavrasPriAlta, $wassunto);
				$matchesAssunNormal = preg_Grep($palavrasPriNormal, $wassunto);
				
				
				//confere para ver quantos matchs da com palavras de prioridade alta
				foreach($matchesPriAlta as $aux)
				{
					
					$contaAlta = $contaAlta +1;
				}
				 
				//confere para ver quantos matchs da com palavras de prioridade normal
				foreach($matchesPriNormal as $aux)
				{
					
					
					$contaNormal = $contaNormal +1;
				}
				
				//confere para ver quantos matchs da com expressões de prioridade normal
				if (preg_match($expressoesNormais, $mens) == 1 )
				{
					$contaNormal = $contaNormal + 2; 
				}
				
				//confere para ver quantos matchs da com expressões de prioridade alta
				if (preg_match($expressoesAlta, $mens) == 1 )
				{
					$contaAlta = $contaAlta + 2; 
				}
				
				
				//matchs do assunto:
				foreach($matchesAssunNormal as $aux)
				{
					
					$contaNormal = $contaNormal +2;
				}
				
				foreach($matchesAssunAlta as $aux)
				{
					
					$contaAlta = $contaAlta +2;
				}
				
				if (preg_match($expressoesNormais, $assunto) == 1 )
				{
					$contaNormal = $contaNormal + 2; 
				}
				
				if (preg_match($expressoesAlta, $assunto) == 1 )
				{
					$contaAlta = $contaAlta + 2; 
				}
				
			
			}
			
			
		}
			$key = $auxKey;
			//coloquei o 50 para não ter uma pontuação negativa, poderia ser qualquer numero grande em relaçao as qtds de matches
				
			$pontuacao = 50 + $contaAlta - $contaNormal;
				
				if ($pontuacao > 50)
				{
					$prioridade = "Alta";
				}
				else
				{
					$prioridade = "Normal";
				}
				
				$json_arr[$key]["Prioridade"] = $prioridade;
				$json_arr[$key]["Pontuacao"] = $pontuacao;
				
				
	}
		
	
	$json_str = json_encode($json_arr);
	file_put_contents('.\tickets.json', null);
	file_put_contents('.\tickets.json', $json_str);
	
	$stringJson = implode ('', file ('.\tickets.json'));
	$json_arr = json_decode($stringJson, true);

	
?> 