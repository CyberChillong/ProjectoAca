<?php
date_default_timezone_set("Europe/Lisbon");
class NasaConsumer {
    private  $keyword ; // key word da pesquisa
    const NASA_CONSUMER_SYSTEM_FOLDER_PATH = "C:/Users/Public/Documents/NasaConsumerFiles";
    const NASA_CONSUMER_PATH_FOR_ALL_SUB_FOLDERS ="C:/Users/Public/Documents/NasaConsumerFiles/";
    private $JSON_FILE_NAME;
    private $limit;

  function __construct($pKeyWord) // construtor que recebe uma string que é a Keyword da pesquisa e um limit para a procura
    {
        $this->keyword = $pKeyWord;
        $this->limit=0;
        $this ->JSON_FILE_NAME = date("Ymd").".json";
    }//__construct

    public function setLimitOfPages($pLimit){
      $this->limit=$pLimit;
    }//setLimitOfPages


    public function builderOfViableUrls():Array{
        /*
         * função que vai construir todos os urls
         * relatiovs ao json que contem as imagens
         */
        $bUrlIsValid = true; //paremtro de continuação do ciclo
        $iterators = 0; // parametro que servira com itenerador que percorrera as paginas
        $previousUrlIdentity="";//varialvel que guarda o sha1 do conteudo do json anterios
        $AllValidUrls =[];//variavel que ira guardar todos os urls possiveis
        echo("Loading");
        while($bUrlIsValid){
            $url = sprintf("https://www.jpl.nasa.gov/assets/json/getMore.php?images=true&search=%s&category=&page=%o",
            $this->keyword,
                $iterators
            );// construção do url
            $urlIdentity = sha1(file_get_contents($url));//codificação do json em sha1
             if (($urlIdentity !== $previousUrlIdentity)){//verivicação se o json é unico
                 if(($iterators == $this->limit) && ($this->limit !=0)){
                     echo(".".PHP_EOL);
                     $bUrlIsValid = false;
                 }else{
                     echo(".");
                     array_push($AllValidUrls, $url);//adiciona-se o url ao array
                     $previousUrlIdentity = $urlIdentity;// guarda-se o sha1 par comparar com o proxim
                     $iterators++;//adiciona-se o itenerador
                 }
             }else{
                 echo(".".PHP_EOL);
                 $bUrlIsValid = false;//caso o json não seja unico acaba-se o cilco
             }

        }


        return $AllValidUrls; // retorna o array com os url conseguidos

    }//builderOfViableUrls

    private function confirmExistenceOfSystemFolder():bool{
        return  file_exists(self::NASA_CONSUMER_SYSTEM_FOLDER_PATH  );



    }//confirmExistenceOfFolder

    private function confirmExistenceOfJsonFile():bool{
        return  file_exists(self::NASA_CONSUMER_PATH_FOR_ALL_SUB_FOLDERS.$this->JSON_FILE_NAME);


    }//confirmExistenceOfFolder
    public function saveAllPossibleJsonUrl(){
        $JsonUrls = $this->builderOfViableUrls();
        $JsonUrlEncoded = json_encode(array('JsonUrls' => $JsonUrls));
        if($this->confirmExistenceOfSystemFolder()===true){
            if($this->confirmExistenceOfJsonFile()===true){
               $JsonFile =  file_get_contents(self::NASA_CONSUMER_PATH_FOR_ALL_SUB_FOLDERS.$this->JSON_FILE_NAME);
               $JsonContent = json_decode($JsonFile, true);
               $JsonArray = $JsonContent['JsonUrls'];
               foreach ($JsonUrls as $url){
                   array_push($JsonArray, $url);
               }
               file_put_contents(self::NASA_CONSUMER_PATH_FOR_ALL_SUB_FOLDERS.$this->JSON_FILE_NAME, json_encode(array('JsonUrls' =>$JsonArray)));
                echo "Added data JSON file successfully".PHP_EOL;
            }else{
                file_put_contents(self::NASA_CONSUMER_PATH_FOR_ALL_SUB_FOLDERS.$this->JSON_FILE_NAME, $JsonUrlEncoded);
                echo "JSON file created successfully".PHP_EOL;
            }
        }else{
            mkdir(self::NASA_CONSUMER_PATH_FOR_ALL_SUB_FOLDERS  , 755);
            echo "System folder created successfully".PHP_EOL;
            file_put_contents(self::NASA_CONSUMER_PATH_FOR_ALL_SUB_FOLDERS.$this->JSON_FILE_NAME, $JsonUrlEncoded);
            echo "JSON file created successfully".PHP_EOL;
        }
    }//saveAllPossibleJsonUrl


}//NasaConsumer