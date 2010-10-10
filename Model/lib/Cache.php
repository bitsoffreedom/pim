<?php
/**
 * Cache::get('the-cache-key', function() {
 *   # this gets executed when cache is stale
 *   return "your cacheable datas";
 * });
 */
class Cache
{
	static $adapter = null;
	static $options = array();

	/**
	 * Initializes the cache.
	 *
	 * @param string $url URL to your cache server
	 * @param array $options Specify additional options
	 */
	public static function initialize($url, $options=array())
	{
		if ($url)
		{
			$url = parse_url($url);
			$file = ucwords(Inflector::instance()->camelize($url['scheme']));
			$class = "$file";
			require_once dirname(__FILE__) . "/cache/$file.php";
			self::$adapter = new $class($url);
		}
		else
			self::$adapter = null;

		self::$options = array_merge(array('expire' => 30),$options);
	}

	public static function flush()
	{
		if (self::$adapter)
			self::$adapter->flush();
	}

	public static function get($key, $closure)
	{
		if (!self::$adapter)
			return $closure();

		if (!($value = self::$adapter->read($key)))
			self::$adapter->write($key,($value = $closure()),self::$options['expire']);

		return $value;
	}
}
?>
