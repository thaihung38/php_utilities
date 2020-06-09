<?php
/**
 * User: HungNT
 * Date: 1/14/2019
 */

class Process
{
    /**
     * @var int
     */
    private $pid;
    
    /**
     * @var string
     */
    private $command;

    /**
     * @param string $command
     */
    public function __construct($command = '')
    {
        if (!empty($command)) {
            $this->command = $command;
            $this->run();
        }
    }

    /**
     * @return int : the process ID of running command
     */
    public function start()
    {
        if (!empty($this->getCommand()) && empty($this->getPid())) {
            return $this->run();
        }
        return 0;
    }

    /**
     * @return bool
     */
    public function stop()
    {
        if ($this->status()) {
            return $this->kill();
        }
        return true;
    }

    /**
     * @return bool
     */
    public function status()
    {
        $command = 'ps -p ' . $this->getPid();
        exec($command, $op);
        if (!isset($op[1])) {
            return false;
        }
        return true;
    }

    /**
     * @return int
     */
    public function getPid()
    {
        return $this->pid;
    }

    /**
     * @param int $pid
     *
     * @return void
     */
    public function setPid($pid)
    {
        $this->pid = $pid;
    }

    /**
     * @return string
     */
    public function getCommand()
    {
        return $this->command;
    }

    /**
     * @param string $command
     * 
     * @return void
     */
    public function setCommand($command)
    {
        $this->command = $command;
    }

    /**
     * @return int
     */
    private function run()
    {
        $command = 'nohup ' . $this->getCommand() . ' > /dev/null 2>&1 & echo $!';
        exec($command, $op);
        $this->setPid((int)$op[0]);

        return $this->getPid();
    }

    /**
     * @return bool
     */
    private function kill()
    {
        $command = 'kill ' . $this->getPid();
        exec($command);
        if ($this->status()) {
            return false;
        }
        return true;
    }
}
