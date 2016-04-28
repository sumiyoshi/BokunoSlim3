<?php

namespace Core\Service;

use Core\SlimServiceLocator\ServiceLocator;
use Zend\Mail\Message;
use Zend\Mail\Transport\Smtp;
use Zend\Mail\Transport\SmtpOptions;

/**
 * Class ErrorHandler
 *
 * @package Component\Service
 * @author sumiyoshi
 */
class ErrorHandler
{

    private static $from = '';

    private static $mailList = [

    ];

    public static function shutdownHandler()
    {
        $e = error_get_last();

        if (!$e) {
            return;
        }

        if ($e['type'] == E_NOTICE || $e['type'] == E_STRICT) {
            self::errorLogNotice($e['message'], $e['file'], $e['line']);
            return;
        } else {
            self::errorLog($e['message'], $e['file'], $e['line']);
            self::send($e['message'], $e['file'], $e['line']);
        }

        echo 'エラー';

    }

    /**
     * @param null $message
     * @param null $file
     * @param null $line
     * @throws \Exception
     */
    public static function errorLog($message = null, $file = null, $line = null)
    {
        /** @var \Zend\Log\Logger $logger */
        $logger = \Core\Service\Logger::getLogger();
        $logger->err('##ERROR START##');
        $logger->err('##File## ' . $file);
        $logger->err('##Line## ' . $line);
        $logger->err('##Message## ' . $message);
        $logger->err('##ERROR ERROR END##');
    }

    /**
     * @param null $message
     * @param null $file
     * @param null $line
     * @throws \Exception
     */
    private static function errorLogNotice($message = null, $file = null, $line = null)
    {
        /** @var \Zend\Log\Logger $logger */
        $logger = \Core\Service\Logger::getLogger();
        $logger->err('##NOTICE START##');
        $logger->err('##File## ' . $file);
        $logger->err('##Line## ' . $line);
        $logger->err('##Message## ' . $message);
        $logger->err('##NOTICE ERROR END##');
    }


    /**
     * @param null $message
     * @param null $file
     * @param null $line
     * @return bool
     */
    public static function send($message = null, $file = null, $line = null)
    {

        /** @var \Exception $e */
        try {
            $transport = new Smtp();

            $server_name = (isset($_SERVER["SERVER_NAME"])) ? $_SERVER["SERVER_NAME"] : 'bat';

            $body =  '##ERROR      ##' . PHP_EOL;
            $body .= '##SERVER     ##' . $server_name . PHP_EOL;
            $body .= '##File       ## ' . $file . PHP_EOL;
            $body .= '##Line       ## ' . $line . PHP_EOL;
            $body .= '##Message    ## ' . $message . PHP_EOL;

            $message = new Message();
            $message->setFrom(self::$from)
                ->setSubject("件名")
                ->setBody($body);

            foreach (self::$mailList as $mail) {
                $message->addTo($mail);
            }

            $transport->send($message);

        } catch (\Exception $e) {
            //エラーは発生させない
            return true;
        }

        return true;
    }
}