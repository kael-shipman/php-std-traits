<?php
namespace KS;

trait ErrorHandlerTrait {
    protected $errors = array();

    public function getErrors(string $field=null) {
        if ($field) return $this->errors[$field] ?: array();
        else {
            $errors = array();
            foreach($this->errors as $field => $e) $errors = array_merge($errors, $e);
            return $errors;
        }
    }
    public function numErrors(string $field=null) {
        if ($field) {
            if (!array_key_exists($field, $this->errors)) return 0;
            else return count($this->errors[$field]);
        } else {
            $num = 0;
            foreach($this->errors as $field => $errors) $num += count($errors);
        }
        return $num;
    }
    public function hasErrors(string $field=null) {
        return (bool)$this->numErrors($field);
    }
    protected function clearError(string $field, string $which=null) {
        if (!array_key_exists($field, $this->errors)) return $this;
        if ($which && !array_key_exists($which, $this->errors[$field])) return $this;
        if ($which) {
            unset($this->errors[$field][$which]);
            if (count($this->errors[$field]) == 0) unset($this->errors[$field]);
        } else unset($this->errors[$field]);
        return $this;
    }
    protected function clearAllErrors() {
        $this->errors = [];
    }
    protected function setError(string $field, string $which=null, string $val) {
        if (!array_key_exists($field, $this->errors)) $this->errors[$field] = array();
        if ($which) $this->errors[$field][$which] = $val;
        else $this->errors[$field][] = $val;
        return $this;
    }
}

