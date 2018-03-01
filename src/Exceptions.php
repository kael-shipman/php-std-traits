<?php
namespace KS;

class HaltEventPropagationException extends \RuntimeException { }

class NonexistentFileException extends \RuntimeException { }

class ConfigException extends \RuntimeException { }
class ConfigFileFormatException extends ConfigException { }

class MissingConfigFileException extends ConfigException
{
    protected $missingPath;

    public function setMissingPath(string $path)
    {
        $this->missingPath = $path;
        return $this;
    }

    public function getMissingPath()
    {
        return $this->missingPath;
    }
}

class InvalidConfigException extends ConfigException
{
    protected $errors = [];

    public function addConfigError($error)
    {
        $this->errors[] = $error;
        return $this;
    }

    public function getConfigErrors(): array
    {
        return $this->errors;
    }
}


