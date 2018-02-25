<?php
namespace KS;

class HaltEventPropagationException extends \RuntimeException { }

class NonexistentFileException extends \RuntimeException { }

class ConfigException extends \RuntimeException { }
class ConfigFileFormatException extends ConfigException { }
class InvalidConfigException extends ConfigException {
    protected $errors;

    public function setConfigErrors(array $errors)
    {
        $this->errors = $errors;
        return $this;
    }

    public function getConfigErrors(): array
    {
        return $this->errors;
    }
}


