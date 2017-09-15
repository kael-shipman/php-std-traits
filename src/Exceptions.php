<?php
namespace KS;

class HaltEventPropagationException extends \RuntimeException { }

class NonexistentFileException extends \RuntimeException { }

class ConfigException extends \RuntimeException { }
class ConfigFileFormatException extends ConfigException { }
class MissingConfigException extends ConfigException { }


