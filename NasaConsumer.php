<?php
/*
 * @SuppressWarnings(PHPMD.LongVariable)
 * @SuppressWarnings(PHPMD.UnusedLocalVariable)
 * */
date_default_timezone_set("Europe/Lisbon");
include("Downloader.php");
class NasaConsumer {
    private  $keyword ; // key word da pesquisa

    const NASA_CONSUMER_SYSTEM_FOLDER_PATH = "C:/Users/Public/Documents/NasaConsumerFiles/";
    const NASA_CONSUMER_PATH_FOR_JSON_PAGES_URLS ="C:/Users/Public/Documents/NasaConsumerFiles/JsonPagesUrls/";
    const NASA_CONSUMER_PATH_FOR_JSON_IMAGES_URLS ="C:/Users/Public/Documents/NasaConsumerFiles/JsonImagesUrls/";
    const BASE_IMAGE_URL = "https://www.jpl.nasa.gov";

    private $TSV_FILE_NAME;
    private $limit;

  function __construct($pKeyWord) // construtor que recebe uma string que é a Keyword da pesquisa e um limit para a procura
    {
        $this->keyword = $pKeyWord;
        $this->limit=0;
        $this ->TSV_FILE_NAME = date("Ymd").".tsv";

    }//__construct

    public function setLimitOfPages($pLimit){
      $this->limit=$pLimit;
    }//setLimitOfPages


    public function builderOfViableUrls(){
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

    private function confirmExistenceOfFolderOrFile($Path){
        return  file_exists($Path);
    }//confirmExistenceOfFolderOrFile

    public function saveAllPossibleJsonUrlInTsv($pIsActive){
      if($pIsActive!= false){
        $UrlArray = $this->builderOfViableUrls();
        if($this->confirmExistenceOfFolderOrFile(self::NASA_CONSUMER_SYSTEM_FOLDER_PATH)===true){
            if($this->confirmExistenceOfFolderOrFile(self::NASA_CONSUMER_PATH_FOR_JSON_PAGES_URLS)===true &&
                $this->confirmExistenceOfFolderOrFile(self::NASA_CONSUMER_PATH_FOR_JSON_PAGES_URLS.$this->TSV_FILE_NAME)===true){
                $TSVFile = fopen(self::NASA_CONSUMER_PATH_FOR_JSON_PAGES_URLS.$this->TSV_FILE_NAME, "a");
                foreach ( $UrlArray as $url){
                    fwrite($TSVFile, $url.PHP_EOL);
                }
                echo "TSV file created successfully".PHP_EOL;

            }else{
                $TSVFile = fopen(self::NASA_CONSUMER_PATH_FOR_JSON_PAGES_URLS.$this->TSV_FILE_NAME, "w");
                mkdir(self::NASA_CONSUMER_PATH_FOR_JSON_PAGES_URLS , 755);
                foreach ( $UrlArray as $url){
                    fwrite($TSVFile, $url.PHP_EOL);
                }
                echo "TSV file created successfully".PHP_EOL;

            }
        }else{
            $TSVFile = fopen(self::NASA_CONSUMER_PATH_FOR_JSON_PAGES_URLS.$this->TSV_FILE_NAME, "w");
            mkdir(self::NASA_CONSUMER_PATH_FOR_ALL_SUB_FOLDERS  , 755);
            echo "System folder created successfully".PHP_EOL;
            mkdir(self::NASA_CONSUMER_PATH_FOR_JSON_PAGES_URLS , 755);
            echo "Json Pages Urls  folder created successfully".PHP_EOL;
            foreach ( $UrlArray as $url){
                fwrite($TSVFile, $url.PHP_EOL);
            }
            echo "TSV file created successfully".PHP_EOL;
        }
      }
    }//saveAllPossibleJsonUrl

    public function extractImagesUrlsFromJson($pIsActive){
            $ImageUrlArray = [];
            if($pIsActive!=false){
            $TsvFile = fopen(self::NASA_CONSUMER_PATH_FOR_JSON_PAGES_URLS.$this->TSV_FILE_NAME, "r");
            while (!feof($TsvFile)){
                        $JsonContent = file_get_contents(fgets($TsvFile));
                        $JsonImageContent = json_decode($JsonContent, true);
                        $imageArray = $JsonImageContent['items'];
                        foreach ($imageArray as $image){
                            echo "image url : ".self::BASE_IMAGE_URL.$image['images']['full']['src']." saved".PHP_EOL;
                            array_push($ImageUrlArray ,self::BASE_IMAGE_URL.$image['images']['full']['src']);
                        }
            }

            if($this->confirmExistenceOfFolderOrFile(self::NASA_CONSUMER_PATH_FOR_JSON_IMAGES_URLS)==true){
                if($this->confirmExistenceOfFolderOrFile(self::NASA_CONSUMER_PATH_FOR_JSON_IMAGES_URLS.$this->TSV_FILE_NAME==true)){
                    $TsvFile = fopen(self::NASA_CONSUMER_PATH_FOR_JSON_IMAGES_URLS.$this->TSV_FILE_NAME, "a");
                    foreach ($ImageUrlArray as $image){
                        fwrite($TsvFile, $image.PHP_EOL);
                    }
                }else{
                    $TsvFile = fopen(self::NASA_CONSUMER_PATH_FOR_JSON_IMAGES_URLS.$this->TSV_FILE_NAME, "w");
                    foreach ($ImageUrlArray as $image){
                        fwrite($TsvFile, $image.PHP_EOL);
                    }
                }
                echo "Image url saved successfully";
            }else{
                mkdir(self::NASA_CONSUMER_PATH_FOR_JSON_IMAGES_URLS  , 755);
                $TsvFile = fopen(self::NASA_CONSUMER_PATH_FOR_JSON_IMAGES_URLS.$this->TSV_FILE_NAME, "w");
                foreach ($ImageUrlArray as $image) {
                    fwrite($TsvFile, $image . PHP_EOL);
                }
                echo "Image url saved successfully";
            }
            }
    }//extractImagesUrlsFromJson

    public static  function  justDownloadTheImagesDirectlyFromJsonPagesUrls($pTsvFileName){
        $ImageUrlArray = [];
        $i=0;
        if($pTsvFileName!=null){
        $TsvFileName = sprintf("%s.tsv",$pTsvFileName);
        $TsvFile =  fopen(self::NASA_CONSUMER_PATH_FOR_JSON_PAGES_URLS.$TsvFileName,"r");
        while(!feof($TsvFile)){
            echo(fgets($TsvFile));
            $JsonImageFile =  file_get_contents(fgets($TsvFile));
            $JsonImageContent = json_decode($JsonImageFile, true);
            $imageArray = $JsonImageContent['items'];
            foreach ($imageArray as $image){

                $ImageDownloaded = new Downloader(stristr($image['images']['full']['src'],"PIA"), self::BASE_IMAGE_URL.$image['images']['full']['src']);
                $ImageDownloaded->SaveDownload();
                echo stristr(self::BASE_IMAGE_URL.$image['images']['full']['src'],"PIA").PHP_EOL;
                echo stristr($image['images']['full']['src'],"PIA")." was saved".PHP_EOL;
                echo self::BASE_IMAGE_URL.$image['images']['full']['src'].PHP_EOL;
            }

        }
        }

    }//justDownloadTheImagesDirectlyFromJsonPagesUrls

    public function directDownloadFromComposedUrls($pIsActive){
        if($pIsActive != false){
        $AllUrl = $this->builderOfViableUrls();
        foreach ($AllUrl as $url){
            echo("Extracting images from  ".$url.PHP_EOL );
            $JsonContent = file_get_contents($url);
            $JsonImageContent = json_decode($JsonContent, true);
            $imageArray = $JsonImageContent['items'];
            foreach ($imageArray as $image){
                    echo("Downloading image ".stristr($image['images']['full']['src'],"PIA").PHP_EOL);
                 $imagesDownload = new Downloader(stristr($image['images']['full']['src'],"PIA"), self::BASE_IMAGE_URL.$image['images']['full']['src']);
                 $imagesDownload->saveDownload();
            }
        }

        }
    }//directDownloadFromComposedUrls



}//NasaConsumer