<?php
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