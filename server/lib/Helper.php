<?php

Class Helper
{
	/**
	 * Checks if the passed variable is empty, only for Objects and Arrays
	 * 
	 * @param  Object|Array  $var The value to check the contents
	 * @return boolean            Whether the value is empty
	 */
	public static function isEmpty($var)
	{
		if(is_object($var))
		{
			return count(get_object_vars($var)) == 0;
		}

		if(is_array($var))
		{
			return count($var) == 0;
		}

		return null;
	}

	/**
	 * Merges 2 objects or arrays into 1 array value
	 * 
	 * @param  Array|Object $var1 The first value to merge with
	 * @param  Array|Object $var2 The second value to merge with
	 * @return Array              The merged values as array
	 */
	public static function merge($var1, $var2)
	{
		if((!is_array($var1) && !is_object($var1)) || (!is_array($var2) && !is_object($var2)))
		{
			throw new Error("var1 or var2 are not acceptable values");
		}

		else
		{
			$var1 = (Array) $var1;
			$var2 = (Array) $var2;

			return array_merge($var1, $var2);
		}
	}

	/**
	 * Removes null data on the Object or Array
	 * 
	 * @param  Array|Object $var The value to flush
	 * @return Array|Object      The result of the flush process
	 */
	public static function flush($var)
	{
		if(is_object($var))
		{
			foreach ($var as $key => $value) {
				if(is_null($value))
				{
					unset($var->$key);
				}
			}
		}

		if(is_array($var))
		{
			foreach ($var as $key => $value) {
				if(is_null($value))
				{
					unset($var[$key]);
				}
			}
		}

		return $var;
	}

	/**
	 * Returns the ip from the client
	 * 
	 * @return String The ip value found from the client
	 */
	public static function get_ip()
	{
		$ip_address = "";

		if(getenv("HTTP_CLIENT_IP"))
		{
			$ip_address = getenv("HTTP_CLIENT_IP");
		}

		else if (getenv("HTTP_X_FORWARDED_FOR"))
		{
			$ip_address = getenv("HTTP_X_FORWARDED_FOR");
		}

		else if (getenv("HTTP_X_FORWARDED"))
		{
			$ip_address = getenv("HTTP_X_FORWARDED");
		}

		else if (getenv("HTTP_FORWARDED_FOR"))
		{
			$ip_address = getenv("HTTP_FORWARDED_FOR");
		}

		else if (getenv("HTTP_FORWARDED"))
		{
			$ip_address = getenv("HTTP_FORWARDED");
		}

		else if (getenv("REMOTE_ADDR"))
		{
			$ip_address = getenv("REMOTE_ADDR");
		}

		else
		{
			$ip_address = "UNKNOWN";
		}

		return $ip_address;
	}
}