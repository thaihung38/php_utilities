<?php
/**
 * User: HungNT
 * Date: 7/20/2018
 */

/** Usage:
require_once('libs_local/RemoteLogger.php');
RemoteLogger::log('test');
*/

require_once 'StaticLogger.php';
require_once 'LogFTP.php';

class RemoteLogger
{
    const FTP_CRED = [
        'host' => 'your_host',
        'username' => 'your_username',
        'password' => 'your_password',
        'port' => 21,
        'rootdir' => '/',
    ];
    
    const LOG_PATH = '/absolute/log/path';

    /**
     * @param mixed $content
     *
     * @return void
     */
    public static function log($content)
    {
        $fileName = time().rand(1000, 10000) . '.log';
        $file = self::LOG_PATH . $fileName;
        StaticLogger::logToFile($content, $fileName, self::LOG_PATH);

        $ftp = new LogFTP(self::FTP_CRED['host'], self::FTP_CRED['username'], self::FTP_CRED['password']);
        $ftp->setRootDir(self::FTP_CRED['rootdir']);
        $ftp->upload($file);
        $ftp->close();

        unlink($file);
    }
}
