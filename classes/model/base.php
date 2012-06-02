<?php

class Model_Base {
	public function __get($key) {
		if(!property_exists($this, $key)) {
			throw new Kohana_Exception('Doctrine: Model :class has no property :key', array(':class' => get_class($this), ':key' => $key), 0);
		}

		$getter = 'get' . ucfirst($key);
		if(method_exists($this, $getter)) return $this->$getter();

		return $this->$key;
	}

	public function __set($key, $value) {
		if(!property_exists($this, $key)) {
			return false;
		}

		if(is_string($value)) {
			$value = strip_tags($value);
		}

		$setter = 'set' . ucfirst($key);
		if(method_exists($this, $setter)) return $this->$setter($value);

		$this->$key = $value;
		return true;
	}

	public static function load($id) {
		$model = Doctrine::instance()->getRepository(get_called_class())->findOneById($id);

		if ($model === null) return false;
		return $model;
	}

	public function fromArray(array $array) {
		foreach($array as $key => $value) {
			$this->__set($key, $value);
		}

		return $this;
	}

	public function save() {
		$em = Doctrine::instance();
		$em->persist($this);
		$flush = $em->flush();
	}
}
