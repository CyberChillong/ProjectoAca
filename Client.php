<?php

/*
 * Utilizar o argv para buscar a keyword //Client.php
 * NasaConsumer(pArgumentosArgV) Miguel ainda em desenvolvimento:
 * -> O metodo que retorna um array com todos os urls de json possiveis com conteudos unicos;
 * Neste ficheiro é onde vai configurado variaveis como
 * -> Download Folder
 * -> HTML File Path
 * -> Entre outros
 * É aqui que vao ser chamadas todas as classe para:
 * -> Processar o JSON e construir o url das  imagens (NasaConsumer);
 * -> Guardar a pesquisa na base dados e os urls das imagens;
 * -> Fazer o download das Imagens(AmUtil);
 * // imagesDownload(pData);
 * // historyBuilderOfDay(); // construia o documento html com as respetivas images;
 * -> Construir o histórico em HTML(HtmlBuilder);
 *Pesquisa -> Imagens
 * P.S "tentar usar biblioteca de interfaces php-gtk.net"
 *
 *
 */

include("NasaConsumer.php");
define ("SLOT_DA_ULTIMA_PALAVRA", count($argv)-1);


function recolherTexto(){
    global $argc, $argv;
    $iQuantidadeDeArgumentosRecebidos = $argc;
    $strTextoAcrescentado = "";
    $bHaTextoParaJuntar =  $iQuantidadeDeArgumentosRecebidos>1;

    if($bHaTextoParaJuntar){
        for($i = 1; //inicialização da variavel
            $i <= SLOT_DA_ULTIMA_PALAVRA; //condição para percorrer a(s) string(s) de palavras recebidas
            $i++){
            $strPalavra = $argv[$i];

            if($i === SLOT_DA_ULTIMA_PALAVRA)
                /*
                 * caso o numero de palavras seja 1 como i = 1
                 * não será necessário acrescentar nada
                 * logo a palavra ficará igual sem qualquer
                 * acrescento
                 */
                $strTextoAcrescentado .= $strPalavra;
                else
                    //caso tenha mais que uma palavra irá adicionar um "+"
                    $strTextoAcrescentado .= $strPalavra."+";
        }//for
    }//if
    return $strTextoAcrescentado;
}//recolherTexto

//-----------------------------Protocologo de execução
//$NasaImages = new NasaConsumer(recolherTexto());
//$NasaImages->setLimitOfPages(2);
//$NasaImages->saveAllPossibleJsonUrl();
//$NasaImages->extractImagesUrlsFromJson();
//NasaConsumer::justDownloadTheImagesDirectlyFromJsonPagesUrls("20191228");

//-----------------------------Protocologo de execução