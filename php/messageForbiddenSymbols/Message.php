<?php

declare(strict_types=1);

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

class Message
{
    /**
     * Message with forbidden characters
     * @var string
     */
    protected string $messageForbiddenSymbols = '';

    protected array $entityAttributes = [
        'number' => ['attr' => 'number', 'required' => false, 'pattern' => '/^[a-zA-Z\p{Cyrillic}\.\d\-\/_ ]{1,1024}$/u'],
        'type' => ['attr' => 'type', 'required' => true, 'pattern' => '/^[\d]{1,2}$/u'],
        'from' => ['attr' => 'from', 'required' => true, 'pattern' => '/^[\d\.-]{10}$/'],
        'to' => ['attr' => 'to', 'required' => false, 'pattern' => '/^[\d\.-]{0,10}$/'],
        'link' => ['attr' => 'fileLink', 'required' => true, 'pattern' => '/^[a-zA-Z\p{Cyrillic}\.\d\-\/_: ]{1,1024}$/u']
    ];

    /**
     * @param array $certificate
     * @return void
     * @throws Exception
     */
    public function start(array $certificate)
    {
        if (!$certificate)
            throw new Exception('Need to upload a certificate');

        foreach ($this->entityAttributes as $key => $item):
            if (isset($certificate[$key])):
                if (is_string($item['pattern']) && $this->forbiddenSymbols($item['pattern'], $certificate[$key])):
                    throw new Exception(sprintf("Parameter '%s' is not valid. ", $key) . $this->messageForbiddenSymbols);
                elseif (is_array($item['pattern'])):
                    throw new Exception(sprintf('Parameter "%s" is not valid', $key));
                endif;
            endif;

            // Validation of required fields
            if ($item['required'] && !isset($certificate[$key])):
                throw new Exception(sprintf('Not set required parameter "%s"', $key));
            endif;
        endforeach;
    }

    /**
     * Finds all forbidden characters
     * @param $pattern
     * @param $string
     * @return bool
     */
    protected function forbiddenSymbols($pattern, $string): bool
    {
        if (!preg_match($pattern, $string)):
            $arrays = preg_split('//u', $string, -1, PREG_SPLIT_NO_EMPTY);
            $arr = preg_grep($pattern, $arrays, PREG_GREP_INVERT);
            $arr = array_unique($arr);
            if (count($arr) != count(array_unique($arrays))):
                $this->messageForbiddenSymbols = "Forbidden characters: '" . implode(', ', $arr) . "'";
            else:
                $this->messageForbiddenSymbols = "Does not match a regular expression: '" . $pattern . "'";
            endif;

            return true;
        endif;

        return false;
    }
}

/**
 * Usage
 */

$certificate = [
    'type' => '1',
    'number' => 'ТС RU Д-FR.ММ06.В.00400',
    'from' => '2022-01-21',
    'to' => '2023-01-21',
    'link' => 'https://yastatic.net/morda-logo/i/citylogos/yandex_no1-logo-ru.png',
];

try {
    $message = new Message();
    $message->start($certificate);
} catch (Exception $e) {
    die($e->getMessage());
}
