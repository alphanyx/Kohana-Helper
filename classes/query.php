<?php defined('SYSPATH') or die('No direct script access.');

class Query {
	public static $get;
	public static $post;
	public static $path;

	public static function drawG() {
		if (!isset(self::$get)) {
			self::$get = $_GET;
		}
	}

	public static function G($var = false) {
		self::drawG();

		if ($var === false) {
			return self::$get;
		}

		if (self::isG($var)) {
			return self::$get[$var];
		}
		return false;
	}

	public static function isG($var) {
		self::drawG();

		if (isset(self::$get[$var])) {
			return true;
		}
		return false;
	}

	public static function drawP() {
		if (!isset(self::$post)) {
			self::$post = $_POST;
		}
	}

	public static function P($var = false) {
		self::drawP();

		if ($var === false) {
			return self::$post;
		}

		if (self::isP($var)) {
			return self::$post[$var];
		}
		return false;
	}

	public static function isP($var) {
		self::drawP();

		if (isset(self::$post[$var])) {
			return true;
		}
		return false;
	}

	public static function drawQ() {
		if (!isset(self::$path)) {
			if (!isset($_SERVER['PATH_INFO'])) {
				$_SERVER['PATH_INFO'] = '';
			}
			self::$path = array_merge(array_filter(explode("/",$_SERVER['PATH_INFO']),function ($var) { return !empty($var); }));
		}
	}

	public static function isQ($index) {
		self::drawQ();

		if (isset(self::$path[$index])) {
			return true;
		}
		return false;
	}

	public static function Q($index = false) {
		self::drawQ();

		if ($index === false) {
			return self::$path;
		}

		if (self::isQ($index)) {
			return self::$path[$index];
		}
		return false;
	}

	public static function inQ($var) {
		self::drawQ();

		if (in_array($var, self::$path)) {
			return true;
		}
		return false;
	}
}