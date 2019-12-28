<?php


class Downloader
{
     private string $path;
     private array $imageArray;

    function __construct($path , $imageArray) // construtor que recebe uma string que é a Keyword da pesquisa e um limit para a procura
    {
        $this->path = $path;
        $this->imageArray = $imageArray;
    }//__construct


    function download(){
        $curlHandler = curl_init();

        for($i = 0;
        $i <= $this->imageArray.sizeof();
        $i++){
            $strUrl = $this->imageArray[$i];

            $bResult = curl_setopt(
                $curlHandler,
                CURLOPT_URL,
                $strUrl
            );
        }//for

        $bResult = curl_setopt(
            $curlHandler,
            CURLOPT_SSL_VERIFYPEER,
            false
        );

        $bResult = curl_setopt(
            $curlHandler,
            CURLOPT_RETURNTRANSFER,
            true
        );
        $bResult = curl_setopt(
            $curlHandler,
            CURLOPT_BINARYTRANSFER,
            true
        );

        $bResult = curl_setopt(
            $curlHandler,
            CURLOPT_ENCODING,
            "" //automatic encoding handling
        );

        $bResult = curl_setopt(
            $curlHandler,
            CURLOPT_USERAGENT,
            "Mozilla\/5.0 (Windows NT 6.3; WOW64; rv:54.0) Gecko\/20100101 Firefox\/54.0"
        );
        $bin = curl_exec($curlHandler);
        return $bin;
    }//download

    function guardarDownload(){
        //se a pasta não existir é criada com aquele path
        if(file_exists($this->path) == false){
            mkdir($this->path);
        }else {
            $FileToSave = $this->path;
            $Content = file_get_contents($this->download()); // recebe o conteudo
            file_put_contents($FileToSave, $Content); // guarda na pasta com o dito path estabelecido anteriormente e o conteudo anteriormente existente
        }
    }//guardarDownload
}