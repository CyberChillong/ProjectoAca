<?php

class NasaConsumer {
    public $keyword ; // key word da pesquisa

    function __construct($pKeyWord) // construtor que recebe uma string que é a Keyword da pesquisa
    {
        $this->keyword = $pKeyWord;

    }//__construct

    public function builderOfViableUrls():Array{
        /*
         * função que vai construir todos os urls
         * relatiovs ao json que contem as imagens*/
        $bUrlIsvalid = true; //paremtro de continuação do ciclo
        $itenerator = 0; // parametro que servira com itenerador que percorrera as paginas
        $previousUrlIdentity="";//varialvel que guarda o sha1 do conteudo do json anterios
        $AllValidUrls =[];//variavel que ira guardar todos os urls possiveis
        while($bUrlIsvalid){
            $url = sprintf("https://www.jpl.nasa.gov/assets/json/getMore.php?images=true&search=%s&category=&page=%o",
            $this->keyword,
                $itenerator
            );// construção do url
            $urlIdentity = sha1(file_get_contents($url));//codificação do json em sha1

             if ($urlIdentity !== $previousUrlIdentity){//verivicação se o json é unico
                 array_push($AllValidUrls, $url);//adiciona-se o url ao array
                 $previousUrlIdentity = $urlIdentity;// guarda-se o sha1 par comparar com o proxim
                 $itenerator++;//adiciona-se o itenerador
             }else{
                 $bUrlIsvalid = false;//caso o json não seja unico acaba-se o cilco
             }

        }


        return $AllValidUrls; // retorna o array com os url conseguidos

    }//builderOfViableUrls

}//NasaConsumer