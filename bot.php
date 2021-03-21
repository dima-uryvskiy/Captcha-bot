<?php
date_default_timezone_set('Europe/Moscow');
require_once('service.php');
require_once('exel_document.php');

$token = 'you_token';
$data = file_get_contents('php://input');
$data = json_decode($data, true);

file_put_contents(__DIR__ . '/message.txt', print_r($data, true));

if (!empty($data['message']['photo'])) {
	$photo = array_pop($data['message']['photo']);
	$responce = sendPost('https://api.telegram.org/bot' . $token . '/getFile', array('file_id' => $photo['file_id']));
	$responce = json_decode($responce, true);

	if ($responce['ok']) {
		$src  = 'https://api.telegram.org/file/bot' . $token . '/' . $responce['result']['file_path'];
		$dest = __DIR__ . "/" . basename($src);
		copy($src, $dest);
	}

    $photoName = basename($src); 
    $service = new Service($photoName);
    $idPhoto = $service->sendResponce($photoName);

    sleep(30);

    $result = $service->getResponce($idPhoto);
    $resultExel = array(
        'photoName' => explode('_', basename($src))[1], 
        'userid' => $data['message']['from']['id'], 
        'date' => date('Y-m-d H:i:s'), 
        'decryption' => explode('|', $result)[1]
    );

    $exel = new ExelDocument();
    $exel->generatEexel($resultExel);

    file_put_contents(__DIR__ . '/result.txt', print_r($resultExel, true));   
            
    sendPost('https://api.telegram.org/bot' . $token . '/sendDocument' , array(
        'chat_id' => $data['message']['chat']['id'],
        'document' => curl_file_create(__DIR__ . '/Result.xls')
    ));
}


function sendPost($url, $parametr)
{
    $curl = curl_init($url);  
    curl_setopt($curl, CURLOPT_POST, 1);  
    curl_setopt($curl, CURLOPT_POSTFIELDS, $parametr);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HEADER, false);

    $responce = curl_exec($curl);

    curl_close($curl);

    return $responce;
}
?>