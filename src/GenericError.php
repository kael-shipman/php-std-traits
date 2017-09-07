<?php
namespace KS;

class GenericError implements ErrorInterface {
    protected $status;
    protected $title;
    protected $detail;

    public function __construct(string $title, int $status=null, string $detail=null) {
        $this->status = $status;
        $this->title = $title;
        $this->detail = $detail;
    }

    public function getStatus() { return $this->status; }
    public function getTitle() { return $this->title; }
    public function getDetail() { return $this->detail; }
}

