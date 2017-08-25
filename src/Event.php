<?php
namespace KS;

class Event implements EventInterface, \JsonSerializable {
    protected $name;
    protected $target;
    protected $data;

    public function __construct(string $name, array $data=[]) {
        $this->name = $name;
        $this->data = $data;
    }

    public function getName() { return $this->name; }
    public function getTarget() { return $this->target; }
    public function getData() { return $this->data; }

    public function setTarget($obj) { $this->target = $obj; }

    public function jsonSerialize() {
        return [
            'name' => $this->name,
            'target' => $this->target,
        ];
    }
}

