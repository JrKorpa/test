<?php
/**
 * @Author: anchen
 * @Date:   2015-07-13 19:45:21
 * @Last Modified by:   anchen
 * @Last Modified time: 2016-04-11 17:10:56
 */
for ($i=0; $i < 100000; $i++) { 
    check_ss($i);
}

function check_ss($num) {
    for($i=2; $i < $num ; $i++) {
        if($num % $i == 0) {
            continue;
        }
    }
    echo $num. '<br />';
}


function my_scandir($dir)  
{  
   $files = array();  
   if ( $handle = opendir($dir) ) { 
      while ( ($file = readdir($handle)) !== false ) {  
        if ( $file != ".." && $file != "." ) {  
           if ( is_dir($dir . "/" . $file) ) {  
             $files[$file] = scandir($dir . "/" . $file);  
           }else {  
             $files[] = $file;  
          }  
       }  
   }  
   closedir($handle);  
   return $files;  
 }  
}

//$files=my_scandir('D:\wamp\www');
//echo '<pre>';
//print_r($files);