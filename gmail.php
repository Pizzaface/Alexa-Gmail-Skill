<?php
//fucntion to get unread emails taking username and password as parametes
function check_email($username, $password)
{ 
    //url to connect to
    $url = "https://mail.google.com/mail/feed/atom"; 

    // sendRequest 
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_USERPWD, $username . ":" . $password);
    curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($curl, CURLOPT_ENCODING, "");
    $curlData = curl_exec($curl);
    curl_close($curl);
    //returning retrieved feed
    return $curlData;

}

function removeEmoji($text) {

    $clean_text = "";

    // Match Emoticons
    $regexEmoticons = '/[\x{1F600}-\x{1F64F}]/u';
    $clean_text = preg_replace($regexEmoticons, '', $text);

    // Match Miscellaneous Symbols and Pictographs
    $regexSymbols = '/[\x{1F300}-\x{1F5FF}]/u';
    $clean_text = preg_replace($regexSymbols, '', $clean_text);

    // Match Transport And Map Symbols
    $regexTransport = '/[\x{1F680}-\x{1F6FF}]/u';
    $clean_text = preg_replace($regexTransport, '', $clean_text);

    // Match Miscellaneous Symbols
    $regexMisc = '/[\x{2600}-\x{26FF}]/u';
    $clean_text = preg_replace($regexMisc, '', $clean_text);

    // Match Dingbats
    $regexDingbats = '/[\x{2700}-\x{27BF}]/u';
    $clean_text = preg_replace($regexDingbats, '', $clean_text);

    return $clean_text;
}

//making page to behave like xml document to show feeds

if($_GET['code'] == "asldjfklajsldjfewrouqweridsafhasdkfj") {
    //calling function
    $feed = check_email("GMAIL_USERNAME", "APP_SPECIFIC_PASS");
    $xml = simplexml_load_string($feed);
    $json = json_encode($xml);
    $jsonArr = json_decode($json, true);
    if ($jsonArr['fullcount'] == "0") {
        echo "No New Mail";
    } elseif ($jsonArr['fullcount'] == "1") {
        $speech = "Here is your most recent unread email: " . $jsonArr['entry']['title'] . " from " . $jsonArr['entry']['author']['name']; 
         echo $speech;
    } else {
        $speech = "Here are your " . $jsonArr['fullcount'] ." most recent unread emails: ";
        foreach ($jsonArr['entry'] as $row) {
            $speech = $speech . removeEmoji($row['title']) . " from " . $row['author']['name'] . ", ";
        }
        echo $speech;
    }
} else {
    echo "Incorrect Code!";
}
?>
