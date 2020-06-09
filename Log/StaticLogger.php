<?php
/**
 * User: HungNT
 * Date: 5/29/2018
 */

class StaticLogger
{
    const DEFAULT_LOG_FILENAME = 'dev.log';
    
    const DEFAULT_ERROR_FILENAME = 'error.log';
    
    const DEFAULT_LOG_PATH = '/mnt/c/www/fuse5/logs/static/';

    /**
     * @param mixed $content
     *
     * @return void
     */
    public static function log($content)
    {
        static::logToFile($content);
    }

    /**
     * @param mixed $content
     *
     * @return void
     */
    public static function append($content)
    {
        static::appendToFile($content);
    }

    /**
     * @param mixed $content
     *
     * @return void
     */
    public static function error($content)
    {
        static::appendToFile($content, self::DEFAULT_ERROR_FILENAME . '_' . date('Y-m-d'));
    }

    /**
     * @param mixed $content
     * @param string $fileName
     * @param string $path
     *
     * @return void
     */
    private static function logToFile($content, $fileName = self::DEFAULT_LOG_FILENAME, $path = self::DEFAULT_LOG_PATH)
    {
        $content = static::format($content);

        $logFile = fopen($path . $fileName, "w");

        fwrite($logFile, $content);
        fclose($logFile);
    }

    /**
     * @param string $content
     * @param string $fileName
     * @param string $path
     *
     * @return void
     */
    private static function appendToFile($content, $fileName = self::DEFAULT_LOG_FILENAME, $path = self::DEFAULT_LOG_PATH)
    {
        $content = PHP_EOL . static::format($content);

        $logFile = fopen($path . $fileName, "a") or fopen($path . $fileName, "w");

        fwrite($logFile, $content);
        fclose($logFile);
    }

    /**
     * @param mixed $content
     *
     * @return string
     */
    private static function format($content)
    {
        if (is_array($content)) {
            $content = print_r($content, 1);
        } elseif (is_object($content)) {
            $content = get_class($content);
        }
        return $content;
    }
}
