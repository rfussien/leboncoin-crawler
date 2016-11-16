<?php

/*
 * This demo uses rfussien/leboncoin-crawler to send an email when the first
 * result page returns one or several new ads.
 *
 * When running it the first time, it will send all results from the result page
 * Later, it will only send an email when there will be new results.
 *
 * If this script is put inside a cron, such as, then leboncoin will be crawled
 * every minute and you'll be immediately warned of a new result by email.
 * e.g : * * * * * /usr/bin/env php /home/username/leboncoin/demo/email.php
 *
 * Note:
 * You must add swiftmailer to Composer. (composer require swiftmailer/swiftmailer)
 */

require_once '../vendor/autoload.php';

$leboncoin_name = 'Locations Paris 17e';
$leboncoin_result_url = 'https://www.leboncoin.fr/locations/offres/ile_de_france/?th=1&location=Paris%2075017&parrot=0&sqs=5&ros=2&ret=2';

$mailer = (object) [
    'smtp_host' => 'smtp.gmail.com',
    'smtp_user' => '',
    'smtp_pass' => '',
    'from'      => '',
    'to'        => '',
    'body'      => '',
];

$file = sha1($leboncoin_name) . ".srz";
if (!file_exists($file)) {
    file_put_contents($file, serialize([]));
}

$knownAds= unserialize(file_get_contents($file));
$newAds = 0;

$searchResults = (new Lbc\GetFrom)->search($leboncoin_result_url, true);

foreach ($searchResults['ads'] as $ad) {

    if (in_array($ad->id, $knownAds)) {
        continue;
    }
    $knownAds[] = $ad->id;

    if (!empty($mailer->body)) {
        $mailer->body .= str_repeat('-', 80) . "\n\n";
    }
    foreach ($ad as $key => $value) {
        $mailer->body .= "{$key}: {$value}\n";
    }

    $mailer->body .= "\n";
    $newAds++;
}

if (!$newAds) {
    return;
}

file_put_contents($file, serialize($knownAds));

$transport = Swift_SmtpTransport::newInstance($mailer->smtp_host, 465, 'ssl')
    ->setUsername($mailer->smtp_user)
    ->setPassword($mailer->smtp_pass);

$mailer = Swift_Mailer::newInstance($transport);

$message = Swift_Message::newInstance()
    ->setSubject('LeBonCoin: ' . count($unknown) . ' nouvelles annonces pour ' . $leboncoin_name)
    ->setFrom([$mailer->from])
    ->setTo([$mailer->to])
    ->setBody($mailer->body);

$mailer->send($message);
