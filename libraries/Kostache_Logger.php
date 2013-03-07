<?php

/*
 * This file is part of Mustache.php.
 *
 * (c) 2012 Justin Hileman
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * A Kohana Mustache Logger.
 *
 * The Kohana Mustache Logger uses the Kohana Logger to log
 *
 * Hint: Try `php://stderr` for your stream URL.
 */
class Kostache_Logger extends Mustache_Logger_AbstractLogger
{
    protected static $levels = array(
        self::DEBUG     => 100,
        self::INFO      => 200,
        self::NOTICE    => 250,
        self::WARNING   => 300,
        self::ERROR     => 400,
        self::CRITICAL  => 500,
        self::ALERT     => 550,
        self::EMERGENCY => 600,
    );
	
	// Log levels
	protected static $kohana_log_levels = array
	(
		'test',
		'error',
		'alert',
		'info',
		'debug',
	);

    /**
     * @throws InvalidArgumentException if the logging level is unknown.
     *
     * @param string  $stream Resource instance or URL
     * @param integer $level  The minimum logging level at which this handler will be triggered
     */
    public function __construct($level = Mustache_Logger::ERROR)
    {
        $this->setLevel($level);
    }

    /**
     * Set the minimum logging level.
     *
     * @throws InvalidArgumentException if the logging level is unknown.
     *
     * @param  integer $level The minimum logging level which will be written
     */
    public function setLevel($level)
    {
        if (!array_key_exists($level, self::$levels)) {
            throw new InvalidArgumentException('Unexpected logging level: ' . $level);
        }

        $this->level = $level;
    }

    /**
     * Get the current minimum logging level.
     *
     * @return integer
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * Logs with an arbitrary level.
     *
     * @throws InvalidArgumentException if the logging level is unknown.
     *
     * @param mixed $level
     * @param string $message
     * @param array $context
     */
    public function log($level, $message, array $context = array())
    {
        if (!array_key_exists($level, self::$levels)) {
            throw new InvalidArgumentException('Unexpected logging level: ' . $level);
        }

        if (self::$levels[$level] >= self::$levels[$this->level]) {
            $this->writeLog($level, $message, $context);
        }
    }

    /**
     * Write a record to the log.
     *
     * @param  integer $level   The logging level
     * @param  string  $message The log message
     * @param  array   $context The log context
     */
    protected function writeLog($level, $message, array $context = array())
    {
		Kohana::log(self::getKohanaLevel($level), self::interpolateMessage($message, $context));
    }
	
	/**
	 * convert Mustache error level to Kohana Error Level
	 *
	 * @param string $level 
	 * @return void
	 * @author George McGinley Smith
	 */
	protected function getKohanaLevel($level)
	{
		$level = strtolower($level);

		switch ($level)
		{
			case 'debug':
				break;

			case 'info':
			case 'notice':
				$level = 'info';
				break;
			
			case 'warning':
				$level = 'alert';
				break;
			
			case 'error':
			case 'critical':
			case 'alert':
			case 'emergency':
				$level = 'error';
				break;
			
		}
		if ( ! in_array($level, self::$kohana_log_levels))
		{
			$level = 'test';
		}
		return $level;
	}

    /**
     * Gets the name of the logging level.
     *
     * @throws InvalidArgumentException if the logging level is unknown.
     *
     * @param  integer $level
     *
     * @return string
     */
    protected static function getLevelName($level)
    {
        return strtoupper($level);
    }

    /**
     * Format a log line for output.
     *
     * @param  integer $level   The logging level
     * @param  string  $message The log message
     * @param  array   $context The log context
     *
     * @return string
     */
    protected static function formatLine($level, $message, array $context = array())
    {
        return sprintf(
            "%s: %s\n",
            self::getLevelName($level),
            self::interpolateMessage($message, $context)
        );
    }

    /**
     * Interpolate context values into the message placeholders.
     *
     * @param  string $message
     * @param  array  $context
     *
     * @return string
     */
    protected static function interpolateMessage($message, array $context = array())
    {
        $message = (string) $message;

        // build a replacement array with braces around the context keys
        $replace = array();
        foreach ($context as $key => $val) {
            $replace['{' . $key . '}'] = $val;
        }

        // interpolate replacement values into the the message and return
        return strtr($message, $replace);
    }
}
