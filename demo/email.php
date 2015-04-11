<?php

/*
 * This demo uses rfussien/leboncoin-crawler to send an email when the first result page returns one or several new ads.
 *
 * When running it the first time, it will send all results from the result page
 * Later, it will only send an email when there will be new results.
 *
 * If this script is put inside a cron, such as,  * * * * * /usr/bin/php /home/username/leboncoin/email.php , then leboncoin
 * will be crawled every minute and you'll be immediately warned of a new result by email.
 */

require_once("../vendor/autoload.php");

$leboncoin_name = 'Locations Paris 17e';
$leboncoin_result_url = 'http://www.leboncoin.fr/locations/offres/ile_de_france/?f=a&th=1&mre=1000&sqs=5&ros=2&ret=2&location=Paris%2075017';

$mail_smtp = 'smtp.gmail.com';
$mail_user = '';
$mail_pass = '';
$mail_from = '';
$mail_to = '';

$file = sha1($leboncoin_name) . ".srz";
if (!file_exists($file))
{
    file_put_contents($file, serialize(array ()));
}
$known = unserialize(file_get_contents($file));
$unknown = array ();

$data = Lbc\GetFrom::search($leboncoin_result_url, true);
if (count($data['ads']) > 0)
{
    foreach ($data['ads'] as $id => $data)
    {
        if (in_array($id, $known))
        {
            continue;
        }
        $unknown[] = $data;
    }
}

if (count($unknown) == 0)
{
    return;
}

$body = '';
foreach ($unknown as $key => $ad)
{
    if ($key > 0)
    {
        $body .= str_repeat('-', 80);
        $body .= "\n\n";
    }
    $known[] = $ad->id;
    foreach ($ad as $attr => $value)
    {
        $body .= "{$attr}: {$value}\n";
    }
    $body .= "\n";

}
file_put_contents($file, serialize($known));

echo $body;

$transport = Swift_SmtpTransport::newInstance($mail_smtp, 465, 'ssl')
   ->setUsername($mail_user)
   ->setPassword($mail_pass)
;
$mailer = Swift_Mailer::newInstance($transport);

$message = Swift_Message::newInstance()
   ->setSubject('LeBonCoin: ' . count($unknown) . ' nouvelles annonces pour ' . $leboncoin_name)
   ->setFrom(array ($mail_from))
   ->setTo(array ($mail_to))
   ->setBody($body)
 ;

$result = $mailer->send($message);
