<?php
namespace Core\Service;

use Zend\Log\Writer\Stream;
use Zend\Log\Logger as ZendLogger;
use Zend\Log\Filter\Priority;

/**
 * Class Logger
 *
 * @package Component\Library
 * @author sumiyoshi
 */
class Logger
{

    private static $logger;

    public static $log_path;

    /**
     * @return ZendLogger
     */
    public static function getLogger()
    {
        $log_path = self::$log_path;

        if (!self::$logger) {
            #region ディレクトリ生成
            $date = date('Ym');
            $error_dir = $log_path . 'error/' . $date . '/';
            $info_dir = $log_path . 'info/' . $date . '/';

            if (!file_exists($error_dir)) {
                @mkdir($error_dir, 0777);
            };

            if (!file_exists($info_dir)) {
                @mkdir($info_dir, 0777);
            };
            #endregion

            @chmod($info_dir, 0777);
            @chmod($error_dir, 0777);

            #region log初期設定
            $logger = new ZendLogger();

            $file_name = date('Y-m-d') . '.log';
            $writer_err = new Stream($error_dir . $file_name);
            $writer_info = new Stream($info_dir . $file_name);

            //エラーログの出力レベル変更
            $filter = new Priority(ZendLogger::WARN);
            $writer_err->addFilter($filter);

            $logger->addWriter($writer_err);
            $logger->addWriter($writer_info);
            #endregion

            if (file_exists($error_dir . $file_name)) {
                @chmod($error_dir . $file_name, 0777);
            }

            if (file_exists($info_dir . $file_name)) {
                @chmod($info_dir . $file_name, 0777);
            }

            self::$logger = $logger;
        }

        return self::$logger;
    }


}