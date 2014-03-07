<?php
/*
 * php mail函数发邮件简单示例
 *
 */

$from = 'junjie@sina.com';
$to = array('junjie@sina.com','test@sina.com');
$cc = '';
$emailBody = "附件为奖品兑换结果，请查收！";
$subject = "奖品兑奖结果";
$subject = "=?utf-8?B?" . base64_encode($subject) . "?=";
$file = array('/tmp/test.txt');
sendmail($from,$to,$cc,$emailBody,$subject,$file);

/*函数介绍：发送邮件
  输入参数：$from——发信人，array $to——收件人，$emailBody——发送内容，
           $subject——主题，array $file附件地址
  返回值：1成功，0失败
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