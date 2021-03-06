<?php
namespace spec\fixture\plugin\monkey;

use kahlan\util\String;

function rand($min, $max) {
	return ($max - $min) / 2;
}

class Foo {

	public function time() {
		return time();
	}

	public function rand($min = 0, $max = 100) {
		return rand($min, $max);
	}

	public function datetime($datetime = 'now') {
		return new \DateTime($datetime);
	}

	public function hash($value) {
		return String::hash($value);
	}
}

?>