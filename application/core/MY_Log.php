<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Logging Class rewritten, following the ideas from: 
 *   https://www.codeigniter.com/userguide3/general/core_classes.html
 *   http://stackoverflow.com/questions/9971305/customize-log-filename-of-codeigniter-log-message 
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	Logging only my info, not Codeigniter's
 * @author		Fulgencio Sanmartín
 * @link		http://codeigniter.com/user_guide/general/errors.html
 */
class MY_Log extends CI_Log {

	/**
	 * Class constructor
	 *
	 * @return	void
	 */
	public function __construct()
	{
    parent::__construct();
	}

	// --------------------------------------------------------------------

	/**
	 * NEW Write Log File
	 *
	 * called using the global log_message() function
	 *
	 * @param	string	the error level: 'error', 'debug' or 'info'
   *                if info, only printed if starts with '['
	 * @param	string	the error message
	 * @return	bool
	 */
	public function write_log($level, $msg)
	{
		if ($this->_enabled === FALSE)
		{
			return FALSE;
		}

		$level = strtoupper($level);

		if (( ! isset($this->_levels[$level]) OR ($this->_levels[$level] > $this->_threshold))
			&& ! isset($this->_threshold_array[$this->_levels[$level]]))
		{
			return FALSE;
		}
    
    // FSM 2015-12-30: My code: If "info", only printed if starts with [
    if( ( $level == 'INFO' OR $level == 'DEBUG' ) AND substr( $msg, 0, 1 ) != '[' )
    {
      // echo('nivel ' . $level . ' - ' . $msg . '<br/>' );
      return FALSE;
    }
    // FSM 2015-12-30

		$filepath = $this->_log_path.'log-'.date('Y-m-d').'.'.$this->_file_ext;
		$message = '';

		if ( ! file_exists($filepath))
		{
			$newfile = TRUE;
			// Only add protection to php files
			if ($this->_file_ext === 'php')
			{
				$message .= "<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>\n\n";
			}
		}

		if ( ! $fp = @fopen($filepath, 'ab'))
		{
			return FALSE;
		}

		// Instantiating DateTime with microseconds appended to initial date is needed for proper support of this format
		if (strpos($this->_date_fmt, 'u') !== FALSE)
		{
			$microtime_full = microtime(TRUE);
			$microtime_short = sprintf("%06d", ($microtime_full - floor($microtime_full)) * 1000000);
			$date = new DateTime(date('Y-m-d H:i:s.'.$microtime_short, $microtime_full));
			$date = $date->format($this->_date_fmt);
		}
		else
		{
			$date = date($this->_date_fmt);
		}

		$message .= $level.' - '.$date.' --> '.$msg."\n";

		flock($fp, LOCK_EX);

		for ($written = 0, $length = strlen($message); $written < $length; $written += $result)
		{
			if (($result = fwrite($fp, substr($message, $written))) === FALSE)
			{
				break;
			}
		}

		flock($fp, LOCK_UN);
		fclose($fp);

		if (isset($newfile) && $newfile === TRUE)
		{
			chmod($filepath, $this->_file_permissions);
		}

		return is_int($result);
	}

}
