<?php
namespace KS;

trait CountableTrait {
    public function count() {
        if (!isset($this->elements)) throw new \RuntimeException("Don't know how to count this object! It doesn't have an `elements` array.");
        return count($this->elements);
    }
}
