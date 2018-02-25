<?php
namespace KS;

interface WebappConfigInterface extends ConfigInterface {
    /**
     * Get the execution profile of the current app instance
     *
     * @return string The execution profile string (usually 'production', 'development', 'staging', 'sandbox', etc...)
     */
    public function getExecutionProfile(): string;
}

