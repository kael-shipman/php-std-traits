<?php
namespace KS;

trait IteratorTrait {
    protected $currentIteratorKey = 0;
    protected $iteratorKeys = [];

    public function rewind() { $this->currentIteratorKey = 0; }
    public function current() { return $this[$this->iteratorKeys[$this->currentIteratorKey]]; }
    public function key() { return $this->iteratorKeys[$this->currentIteratorKey]; }
    public function next() { $this->currentIteratorKey++; }
    public function valid() { return array_key_exists($this->currentIteratorKey, $this->iteratorKeys); }
}

