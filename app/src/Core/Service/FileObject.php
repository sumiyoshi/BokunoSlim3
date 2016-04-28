<?php

namespace Core\Service;

/**
 * Class FileObject
 *
 * @package Component\Service
 * @author sumiyoshi
 */
class FileObject
{
    /**
     * @param $file_path
     * @return string
     */
    public function putFile($file_path)
    {
        setlocale(LC_ALL, 'ja_JP.UTF-8');


        $dir = TMP_ROOT . '/' . date('Ymd') . '/';
        if (!file_exists($dir)) {
            @mkdir($dir, 0777);
        };

        # 一時ファイル名
        $tmp_file = $dir . microtime(true) . '_tmp.csv';

        # 改行の削除
        $content = file_get_contents($file_path);
        mb_convert_variables('UTF-8', 'SJIS-win', $content);
        file_put_contents($tmp_file, $content);

        return $tmp_file;
    }

    /**
     * @param $tmp_file
     * @return \SplFileObject
     */
    public function getFileObject($tmp_file)
    {
        if (!$tmp_file) {
            return array();
        }

        $file = new \SplFileObject($tmp_file);
        $file->setFlags(\SplFileObject::READ_CSV);

        return $file;
    }
}