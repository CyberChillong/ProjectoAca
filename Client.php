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

$b = new NasaConsumer("Mars");
var_dump($b->builderOfViableUrls());