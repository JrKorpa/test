 <html>
      <body>
          <?php
              function traverse($path = '.') {
                  $current_dir = opendir($path);    //opendir()����һ��Ŀ¼���,ʧ�ܷ���false
                  while(($file = readdir($current_dir)) !== false) {    //readdir()���ش�Ŀ¼����е�һ����Ŀ
                    $sub_dir = $path . DIRECTORY_SEPARATOR . $file;    //������Ŀ¼·��
                   if($file == '.' || $file == '..') {
                         continue;
                     } else if(is_dir($sub_dir)) {    //�����Ŀ¼,���еݹ�
                         echo 'Directory ' . $file . ':<br>';
                         traverse($sub_dir);
                    } else {    //������ļ�,ֱ�����
                        echo 'File in Directory ' . $path . ': ' . $file . '<br>';
                    }
                }
            }
            
            traverse('../cuteframe');
        ?>  
   </body>
 </html>