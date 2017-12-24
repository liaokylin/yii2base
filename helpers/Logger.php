<?php
/**
 * Created by PhpStorm.
 * User: junping
 * Date: 2015/2/2
 * Time: 22:29
 */

namespace app\helpers;

class Logger extends Singleton
{
    const UNDEFINE = 0;
    const DEBUG = 1;
    const INFO = 2;
    const WARN = 3;
    const ERROR = 4;
    const FATAL = 5;

    private $desc = ['undefined', 'debug', 'info', 'warn', 'error', 'fatal'];
    private $level = Logger::UNDEFINE;
    private $fp = null;
    private $file;

    public function __destruct()
    {
        if ($this->fp)
        {
            fclose($this->fp);
            $this->fp = null;
        }
    }

    public function init($file, $level = Logger::INFO)
    {
        $this->file = $file;
        $this->level = $level;
    }

    public function debug($content, $file = __FILE__, $line = __LINE__, $func = __FUNCTION__)
    {
        return $this->log($content, Logger::DEBUG, $file, $line, $func);
    }

    public function info($content, $file = __FILE__, $line = __LINE__, $func = __FUNCTION__)
    {
        return $this->log($content, Logger::INFO, $file, $line, $func);
    }

    public function warn($content, $file = __FILE__, $line = __LINE__, $func = __FUNCTION__)
    {
        return $this->log($content, Logger::WARN, $file, $line, $func);
    }

    public function error($content, $file = __FILE__, $line = __LINE__, $func = __FUNCTION__)
    {
        return $this->log($content, Logger::ERROR, $file, $line, $func);
    }

    public function fatal($content, $file = __FILE__, $line = __LINE__, $func = __FUNCTION__)
    {
        return $this->log($content, Logger::FATAL, $file, $line, $func);
    }

    private function log($content, $level, $file, $line, $func)
    {
        if ($this->level > $level)
        {
            return false;
        }
        if (!$this->fp)
        {
            $this->fp = fopen($this->file, 'w');
            if (!$this->fp)
            {
                return false;
            }
        }
        $time = date('Y-m-d H:i:s');
        $msg = "{$time} {$this->desc[$level]} {$func}@{$file}:{$line} {$content}\n";
        return fwrite($this->fp, $msg) ? true : false;
    }
}