<?php


class Downloader
{
     const NASA_CONSUMER_PATH_FOR_DOWNLOADED_IMAGES ="C:/Users/Public/Pictures/";
     private  $Name;
     private  $imageUrl;

    function __construct($pName , $pImageUrl) // construtor que recebe uma string que é a Keyword da pesquisa e um limit para a procura
    {
        $this->Name = $pName;
        $this->imageUrl = $pImageUrl;
    }//__construct


    function download(){
        $ch = curl_init(); //inicialização
        if ($ch){

            curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_URL, $this->imageUrl);
            curl_setopt($ch, CURLOPT_USERAGENT, "My bot");


            $bin = curl_exec($ch);

            return $bin;
        }//if
        return false;



    }//download

    function saveDownload(){
        //se a pasta não existir é criada com aquele path
        if(file_exists(self::NASA_CONSUMER_PATH_FOR_DOWNLOADED_IMAGES) == false){
            mkdir(self::NASA_CONSUMER_PATH_FOR_DOWNLOADED_IMAGES);
        }else {
            $FileToSave = self::NASA_CONSUMER_PATH_FOR_DOWNLOADED_IMAGES.$this->Name;
            file_put_contents($FileToSave, $this->download()); // guarda na pasta com o dito path estabelecido anteriormente e o conteudo anteriormente existente

        }
    }//guardarDownload
}