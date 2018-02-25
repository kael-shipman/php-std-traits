<?php
namespace KS;

interface ConfigInterface {

    /**
     * A method that may be used to check that all necessary configuration keys are present.
     *
     * Should throw an InvalidConfigException when a config key is missing or invalid.
     *
     * @return void
     * @throws InvalidConfigException
     */
    public function checkConfig() : void;

    /**
     * Reload config from original files provided
     *
     * @return void
     */
    public function reload(): void;

    /**
     * Dump the full parsed configuration to string.
     *
     * @return string The dumped configuration
     */
    public function __toString(): string;

    /**
     * Dump the full parse configuration to an array.
     *
     * @return array The dumped configuration
     */
    public function toArray();
}

