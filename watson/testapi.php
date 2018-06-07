<?php

    
$curl = curl_init();
    $post_args= ''; 
    unlink('data1.wav');
if(isset($_POST['submit'])){
    if(!empty($_POST['textLID'])){
        echo $data= $_POST['textLID'];
      $post_args = array("text"=>  $data); 
 echo  $dataen = json_encode($post_args);
                  
    curl_setopt($curl,CURLOPT_HTTPHEADER,array('Content-Type: application/json','Accept: audio/wav'));
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS,  $dataen);
    curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($curl, CURLOPT_USERPWD, "7bdba914-82da-400d-bdc9-e74e513c31d1: lArzYn6jXqF2");
    curl_setopt($curl, CURLOPT_URL, "https://stream.watsonplatform.net/text-to-speech/api/v1/synthesize?voice=en-US_AllisonVoice");
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_VERBOSE, 1);

    $result = curl_exec($curl);
    
    
    $fp = fopen('data1.wav', 'w');
    fwrite($fp, $result);
    fclose($fp);
    curl_close($curl);
    }
    ?>
<audio autoplay="autoplay" controls="controls">
  <source src="data1.wav" type="audio/ogg">
  </audio>
<?php
    
}
 

?>

<form method="post" action="">
Enter text to identify language: <textarea name="textLID" rows="5" cols="40"></textarea>

<input type="submit" name="submit" value="Submit">
</form>





