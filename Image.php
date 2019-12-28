<?php

class Image {
    public  string $imageUrl;
    public string $imageTitle;


    public function __construct($pImageUrl, $pImageTitle)
    {
        $this->imageUrl=$pImageUrl;
        $this->imageTitle = $pImageTitle;

    }//__construct







}//Image
