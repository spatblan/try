<?php
/*
 * php mail�������ʼ���ʾ��
 *
 */

$from = 'junjie@sina.com';
$to = array('junjie@sina.com','test@sina.com');
$cc = '';
$emailBody = "����Ϊ��Ʒ�һ����������գ�";
$subject = "��Ʒ�ҽ����";
$subject = "=?utf-8?B?" . base64_encode($subject) . "?=";
$file = array('/tmp/test.txt');
sendmail($from,$to,$cc,$emailBody,$subject,$file);

/*�������ܣ������ʼ�
  ���������$from���������ˣ�array $to�����ռ��ˣ�$emailBody�����������ݣ�
           $subject�������⣬array $file������ַ
  ����ֵ��1�ɹ���0ʧ��
*/
function sendmail($from,$to,$cc,$emailBody,$subject,$file)
{
    $boundary = uniqid("");

    foreach($file as $filename)
    {
        $ext=explode('.',$filename);
        if($ext[1]=='txt'||$ext[1]=='log')
        {
            $type='text/plain';
        }
        else
        {
            $type='application/vnd.ms-excel';
        }
        $fp = fopen($filename, "r");
        $read = fread($fp, filesize($filename));
        $read=base64_encode($read);
        $attachment = chunk_split($read);
        $file=explode('/',$filename);
        $filename=$file[count($file)-1];
        $attach="
--$boundary
Content-type: $type; name=$filename;charset=utf-8
Content-disposition: inline; filename=$filename
Content-transfer-encoding: base64

$attachment

";
        $att.=$attach;
    }


    $headers = "From: $from
CC:$cc
Content-type: multipart/mixed;charset=utf-8; boundary=".$boundary;


    $emailBody = "--$boundary
Content-type: text/plain; charset=utf-8
Content-transfer-encoding: 8bit

$emailBody".$att."
--$boundary--
";

$rtn=mail( implode(',',$to), $subject, $emailBody, $headers);
return $rtn;
}
?>