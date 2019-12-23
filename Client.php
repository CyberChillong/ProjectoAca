<?php

/*
 * Neste ficheiro é onde vai configurado variaveis como
 * -> Download Folder
 * -> HTML File Path
 * -> Entre outros
 * É aqui que vao ser chamadas todas as classe para :
 * -> Processar o JSON e construir o url das imagens;
 * -> Fazer o download das Imagens;
 * -> Construir o histórico em HTML;
 *
 * P.S "tentar usar biblioteca de interfaces php-gtk.net"
 *
 *
 */

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
echo (recolherTexto());