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
function processIniFile(){
    return parse_ini_file("NasaClientConf.ini" ,true,INI_SCANNER_NORMAL);
}//processIniFile


//-----------------------------Protocologo de execução

$getConfigurationValue = processIniFile();
$NasaImages = new NasaConsumer($getConfigurationValue["NasaConsumerConfiguration"]["keyword"]);
$NasaImages->setLimitOfPages((int)$getConfigurationValue["NasaConsumerConfiguration"]["LimitOfPages"]);
$NasaImages->saveAllPossibleJsonUrlInTsv($getConfigurationValue["NasaConsumerConfiguration"]["SaveJsonResult"]==="true"? true:false);
$NasaImages->extractImagesUrlsFromJson($getConfigurationValue["NasaConsumerConfiguration"]["ExtractImagesUrlFromJson"]==="true"? true:false);
NasaConsumer::justDownloadTheImagesDirectlyFromJsonPagesUrls($getConfigurationValue["NasaConsumerConfiguration"]["DownloadFromTsvFileName"]);
$NasaImages->directDownloadFromComposedUrls($getConfigurationValue["NasaConsumerConfiguration"]["DirectDownloads"]==="true"? true:false);
//-----------------------------Protocologo de execução