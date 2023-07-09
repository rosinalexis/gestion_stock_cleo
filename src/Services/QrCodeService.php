<?php

namespace App\Services;


use Endroid\QrCode\Builder\BuilderInterface;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;

class QrCodeService
{
    protected BuilderInterface $builder;

    public function __construct(BuilderInterface $builder)
    {

        $this->builder = $builder;
    }

    public function qrCode($query)
    {
        $url = 'https://192.168.1.41:8000/items/ref/';
        $objDateTime = new \DateTime('NOW');
        $dateString = $objDateTime->format('d-m-Y H:i:s');

        $result = $this->builder
            ->data($url . $query)
            ->encoding(new Encoding('UTF-8'))
            ->errorCorrectionLevel(new ErrorCorrectionLevelHigh())
            ->labelText($dateString)
            ->size(200)
            ->margin(10)
            ->build();

        $namePng = uniqid('') . '.png';
        $url = (\dirname(__DIR__, 2) . '/public/qr-code/' . $namePng);

        $result->saveToFile($url);

        return 'qr-code/' . $namePng;
    }
}