<?php
date_default_timezone_set("Europe/Lisbon");
class NasaConsumer {
    private  $keyword ; // key word da pesquisa
    const NASA_CONSUMER_SYSTEM_FOLDER_PATH = "C:/Users/Public/Documents/NasaConsumerFiles/";
    const NASA_CONSUMER_PATH_FOR_JSON_PAGES_URLS ="C:/Users/Public/Documents/NasaConsumerFiles/JsonPagesUrls/";
    const NASA_CONSUMER_PATH_FOR_JSON_IMAGES_URLS ="C:/Users/Public/Documents/NasaConsumerFiles/JsonImagesUrls/";
    Const BASE_IMAGE_URL = "https://www.jpl.nasa.gov";

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
                 if(($iterators == $this->limit) && ($this->limit!=0)){
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

    private function confirmExistenceOfFolderOrFile($Path):bool{
        return  file_exists($Path);



    }//confirmExistenceOfFolderOrFile





    public function saveAllPossibleJsonUrl(){
        $JsonUrls = $this->builderOfViableUrls();
        $JsonUrlEncoded = json_encode(array('JsonUrls' => $JsonUrls));
        if($this->confirmExistenceOfFolderOrFile(self::NASA_CONSUMER_SYSTEM_FOLDER_PATH)===true){
            if($this->confirmExistenceOfFolderOrFile(self::NASA_CONSUMER_PATH_FOR_JSON_PAGES_URLS)===true &&
                $this->confirmExistenceOfFolderOrFile(self::NASA_CONSUMER_PATH_FOR_JSON_PAGES_URLS.$this->JSON_FILE_NAME)===true){
               $JsonFile =  file_get_contents(self::NASA_CONSUMER_PATH_FOR_JSON_PAGES_URLS.$this->JSON_FILE_NAME);
               $JsonContent = json_decode($JsonFile, true);
               $JsonArray = $JsonContent['JsonUrls'];
               foreach ($JsonUrls as $url){
                   array_push($JsonArray, $url);
               }
               file_put_contents(self::NASA_CONSUMER_PATH_FOR_JSON_PAGES_URLS.$this->JSON_FILE_NAME, json_encode(array('JsonUrls' =>$JsonArray)));
                echo "Added data JSON file successfully".PHP_EOL;
            }else{
                mkdir(self::NASA_CONSUMER_PATH_FOR_JSON_PAGES_URLS , 755);
                echo "Json Pages Urls  folder created successfully".PHP_EOL;
                file_put_contents(self::NASA_CONSUMER_PATH_FOR_JSON_PAGES_URLS.$this->JSON_FILE_NAME, $JsonUrlEncoded);
                echo "JSON file created successfully".PHP_EOL;
            }
        }else{
            mkdir(self::NASA_CONSUMER_PATH_FOR_ALL_SUB_FOLDERS  , 755);
            echo "System folder created successfully".PHP_EOL;
            mkdir(self::NASA_CONSUMER_PATH_FOR_JSON_PAGES_URLS , 755);
            echo "Json Pages Urls  folder created successfully".PHP_EOL;
            file_put_contents(self::NASA_CONSUMER_PATH_FOR_ALL_SUB_FOLDERS.$this->JSON_FILE_NAME, $JsonUrlEncoded);
            echo "JSON file created successfully".PHP_EOL;
        }
    }//saveAllPossibleJsonUrl

    public function extractImagesUrlsFromJson(){
        $ImageUrlArray = [];
        $JsonFile =  file_get_contents(self::NASA_CONSUMER_PATH_FOR_JSON_PAGES_URLS.$this->JSON_FILE_NAME);
        $JsonContent = json_decode($JsonFile, true);
        $item = $JsonContent['JsonUrls'];
        foreach ($item as $url){
            $JsonImageFile =  file_get_contents($url);
            $JsonImageContent = json_decode($JsonImageFile, true);
            $imageArray = $JsonImageContent['items'];
            foreach ($imageArray as $image){
             array_push($ImageUrlArray ,self::BASE_IMAGE_URL.$image['images']['full']['src']);
            }
        }
        if($this->confirmExistenceOfFolderOrFile(self::NASA_CONSUMER_PATH_FOR_JSON_IMAGES_URLS)==true){
        file_put_contents(self::NASA_CONSUMER_PATH_FOR_JSON_IMAGES_URLS.$this->JSON_FILE_NAME , json_encode(array('JsonImageUrls' =>$ImageUrlArray)));
        echo "Image url saved successfully";
        }else{
            mkdir(self::NASA_CONSUMER_PATH_FOR_JSON_IMAGES_URLS  , 755);
            file_put_contents(self::NASA_CONSUMER_PATH_FOR_JSON_IMAGES_URLS.$this->JSON_FILE_NAME , json_encode(array('JsonImageUrls' =>$ImageUrlArray)));
            echo "Image url saved successfully";
        }

    }//extractImagesUrlsFromJson

}//NasaConsumer