<?php
namespace KS;

trait ArrayAccessTrait {
    protected $elements = [];

    public function offsetExists($offset) {
        return array_key_exists($offset, $this->elements);
    }
    public function &offsetGet($offset) {
        return $this->elements[$offset];
    }
    public function offsetSet($offset, $value) {
        if ($offset === null) $offset = count($this);
        $this->elements[$offset] = $value;
        if (is_array($this->iteratorKeys) && !in_array($offset, $this->iteratorKeys)) $this->iteratorKeys[] = $offset;
    }
    public function offsetUnset($offset) {
        $data = [];
        foreach($this->elements as $k => $e) {
            if ($k == $offset) continue;
            $data[] = $e;
        }
        $this->elements = $data;

        if (is_array($this->iteratorKeys)) {
            $keys = [];
            foreach($this->iteratorKeys as $k) {
                if ($k == $offset) continue;
                $keys[] = $k;
            }
            $this->iteratorKeys = $keys;
        }
    }
}

