<?php
/**
 * User: HungNT
 * Date: 6/22/2018
 */

class LogFTP
{
    /**
     * @var string
     */
    private $host;
    /**
     * @var string
     */
    private $username;
    /**
     * @var string
     */
    private $password;
    /**
     * @var string
     */
    private $rootDir = '~/';
    /**
     * @var resource
     */
    private $connectId;
    /**
     * @var int
     */
    private $port = 21;
    /**
     * @var bool
     */
    private $isAuthenticated = false;
    /**
     * @var array
     */
    private $errors = [];

    /**
     * @param string $host
     * @param string $username
     * @param string $password
     */
    public function __construct($host = '', $username = '', $password = '')
    {
        $this->host = $host;
        $this->username = $username;
        $this->password = $password;
    }

    /**
     * @return bool
     */
    public function connect()
    {
        $connectId = ftp_connect($this->getHost(), $this->getPort());
        if (!$connectId) {
            $this->setErrors('Cannot connect to FTP server at ' . $this->getHost());

            return false;
        }
        $this->connectId = $connectId;

        return true;
    }

    /**
     * @return bool
     */
    public function authenticate()
    {
        if (!$this->getConnectId()) {
            if (!$this->connect()) {
                return false;
            }
        }
        $login = ftp_login($this->getConnectId(), $this->getUsername(), $this->getPassword());
        if (!$login) {
            $this->setErrors('Invalid username or password!');

            return false;
        }
        $this->isAuthenticated = true;

        return true;
    }

    /**
     * @param array|string $files
     *
     * @return void
     */
    public function upload($files)
    {
        if (!$this->isAuthenticated) {
            $this->authenticate();
        }
        if (!is_array($files)) {
            $files = array($files);
        }
        ftp_pasv($this->getConnectId(), true);
        foreach ($files as $file) {
            $fileName = pathinfo($file, PATHINFO_BASENAME);
            $upload = ftp_put($this->getConnectId(), $this->getRootDir() . $fileName, $file, FTP_BINARY);
            if (!$upload) {
                $this->addError('Cannot upload file ' . $file);
            }
        }
    }

    /**
     * @return void
     */
    public function close()
    {
        ftp_close($this->getConnectId());
    }

    /**
     * @return string
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * @param string $host
     */
    public function setHost($host)
    {
        $this->host = $host;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return int
     */
    public function getPort()
    {
        return $this->port;
    }

    /**
     * @param int $port
     */
    public function setPort($port)
    {
        $this->port = $port;
    }

    /**
     * @return string
     */
    public function getRootDir()
    {
        return $this->rootDir;
    }

    /**
     * @param string $rootDir
     */
    public function setRootDir($rootDir)
    {
        if (substr($rootDir, -1) != '/') {
            $rootDir .= '/';
        }
        $this->rootDir = $rootDir;
    }

    /**
     * @return resource
     */
    public function getConnectId()
    {
        return $this->connectId;
    }

    /**
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * @return string
     */
    public function getPrettyError()
    {
        $prettyError = '';
        foreach ($this->getErrors() as $error) {
            $prettyError .= $error . PHP_EOL;
        }
        return trim($prettyError, PHP_EOL);
    }

    /**
     * @param array|string $errors
     */
    public function setErrors($errors)
    {
        if (!is_array($errors)) {
            $errors = array($errors);
        }
        $this->errors = $errors;
    }

    /**
     * @param string $error
     */
    public function addError($error)
    {
        $this->errors[] = $error;
    }
}