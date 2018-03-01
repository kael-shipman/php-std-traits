<?php
namespace KS;

/**
 * This abstract config class is based on a model of runtime configuration that
 * utilizes an arbitrary stack of configuration files, directories and arrays.
 *
 * The original concept was that a typical application will have the following
 * config sources:
 *
 *  1. A mandatory, internal, system default config file;
 *  2. An optional global config file (usually `/etc/[name]/config`);
 *  3. An optional directory of global config fragment files (usually `/etc/[name]/config.d/*`);
 *  4. An optional user config file (`/home/[user]/.config/[name]/config`); and
 *  5. An array of config parameters
 *
 *  While this is probably a good and safe arrangement, I've found there are too
 *  many permutations to usefully limit the arguments to known values. Instead, I've
 *  adapted the constructor to require a single array of arbitrary config arguments.
 *  (Read the constructor documentation for information on those arguments.)
 */
abstract class AbstractCliConfig extends AbstractConfig
{
    protected $configSources = [];
    protected $optional = [];

    /**
     * Constructor for CLI Config object
     *
     * This constructor takes one argument, the `$configs` array, which should contain
     * any of the following:
     *
     * * The string pathname of a config file
     * * The string pathname of a directory containing config files
     * * An array of key -> value config values
     *
     * See class description for suggested usage
     *
     * @param array $configSources An array containing configuration sources
     * @param array $optional An array defining files and directories that are optional
     */
    public function __construct(array $configSources, array $optional = [])
    {
        $this->configSources = $configSources;
        $this->optional = $optional;
        $this->reload();
    }

    /**
     * Reload the config from it's sources (usually done on SIGHUP to load in
     * configuration changes in files
     *
     * @return void
     */
    public function reload(): void
    {
        $config = [];
        foreach($this->configSources as $src) {
            // If it's a string, then it's a file or directory path
            if (is_string($src)) {
                // If directory, get all config files within it, recursively
                if (is_dir($src)) {
                    $getConfigFiles = function(string $path) use (&$getConfigFiles) {
                        $fragments = [];
                        if (is_dir($path)) {
                            $d = dir($path);
                            while (($f = $d->read()) !== false) {
                                if ($f[0] === '.') {
                                    continue;
                                }
                                if (is_dir("$path/$f")) {
                                    $fragments = array_merge($fragments, $getConfigFiles("$path/$f"));
                                } else {
                                    $fragments[] = "$path/$f";
                                }
                            }
                        }
                        return $fragments;
                    };
                    $fragments = $getConfigFiles($src);
                    sort($fragments);
                    foreach($fragments as $c) {
                        $config = array_replace_recursive($config, $this->parseConfig(file_get_contents($c)));
                    }

                // If it's a file, just merge it's contents over the current config array
                } elseif (is_file($src)) {
                    $config = array_replace_recursive($config, $this->parseConfig(file_get_contents($src)));

                // If it doesn't exist in the filesystem, and it's not optional, throw an exception
                } elseif (array_search($src, $this->optional) === false) {
                    throw (new MissingConfigFileException("Required config file or directory `$src` is missing."))
                        ->setMissingPath($src);
                }

            // If the source is an array, just merge it in
            } elseif (is_array($src)) {
                $config = array_replace_recursive($config, $src);
            }
        }
        $this->config = $config;
    }

    /**
     * An overridable method that allows parsing arbitrary strings into config arrays
     *
     * @param string $config The contents of a config file (or sometimes the filename itself)
     * @return array The config array resulting from the parse
     */
    protected function parseConfig(string $config): array
    {
        $config = json_decode($config, true);
        if ($config === null) {
            throw new ConfigFileFormatException("Config files should be written in valid JSON");
        }
        return $config;
    }
}

