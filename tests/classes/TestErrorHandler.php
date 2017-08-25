<?php
namespace Test;

class TestErrorHandler {
    use \KS\ErrorHandlerTrait;

    public function produceError($field, $msg, $errorType=null, $new=false) {
        if ($new) $this->clearError($field);
        $this->setError($field, $msg, $errorType);
    }

    public function deleteError($field, $which=null) {
        $this->clearError($field, $which);
    }

    public function deleteAllErrors() {
        $this->clearAllErrors();
    }
}

