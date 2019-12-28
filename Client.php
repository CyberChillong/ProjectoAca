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

function pastaExiste($folder){
    $path = realpath($folder);
    // Se existir verifica o caminho senão afirma que não existe
    if($path !== (false AND is_dir($path)))
    {
        // Returna o caminho absoluto
        return $path;
    }
    // Path/folder não existe
    return false;
}//pastaExiste

function guardarDownload(){
    //se a pasta não existir é criada com aquele path
    if(pastaExiste("C:\Users\Public\Downloads") == false){
         mkdir("C:\Users\Public\Downloads");
    }else {
        $FileToSave = "C:\Users\Public\Downloads"; // variavel que recebe o path da pasta criada
        //TODO : Criar uma variavel boolean que verifique que o dito ficheiro existe e só depois avançar
        $Content = file_get_contents("teste.txt"); // recebe o conteudo
        file_put_contents($FileToSave, $Content); // guarda na pasta com o dito path estabelecido anteriormente e o conteudo anteriormente existente
    }
}//guardarDownload

function criarFicheiro(){
    file_put_contents("C:\Users\Public\Downloads\Links.txt", "Conteudo");
}//criarFicheiro

//echo (pastaExiste("C:\Users\Public\Downloads"));
//guardarDownload();
criarFicheiro();
//echo (recolherTexto());

//-----------------------------Protocologo de execução
$NasaImages = new NasaConsumer(recolherTexto());
//$NasaImages->setLimitOfPages(2);
//$NasaImages->saveAllPossibleJsonUrl();
$NasaImages->extractImagesUrlsFromJson();



//-----------------------------Protocologo de execução