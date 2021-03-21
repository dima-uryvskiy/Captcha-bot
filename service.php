<?php

class Service
{
    private $apiKey = 'you_key';
    private $imageName;


    function __construct($imageName)
    {
        $this->imageName = $imageName;
    }

    public function sendResponce()
    {
        $image = __DIR__ . "/$this->imageName" ;
        $base64 = base64_encode(file_get_contents($image));
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'http://rucaptcha.com/in.php',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => http_build_query(
                array(
                    'key' => $this->apiKey,
                    'method' => 'base64',
                    'body' => $base64
                )
            )
        ));
        
        $response = curl_exec($curl);
        curl_close($curl);

        return explode('|', $response)[1];
    }

    public function getResponce($id)
    {
        $curl = curl_init();  
        curl_setopt($curl, CURLOPT_URL, "http://rucaptcha.com/res.php?key=" . $this->apiKey . "&action=get&id=$id");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
        $out = curl_exec($curl); 
        $result = strtoupper($out);
        curl_close($curl);

        return $result;
    }
}

?>