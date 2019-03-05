<?php
// namespace Logger;
Class Log {
	public static function print( $str ) {
		echo "<pre>";
		print_r($str);
		echo "</pre>";
	}
}