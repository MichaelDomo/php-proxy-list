<?php

namespace michaeldomo\proxylist;

/**
 * Interface BaseProxyList
 * @package app\modules\parser\components
 *
 * @property null|array $proxyList
 * @property null|string $response
 */
abstract class BaseProxyList
{
    /**
     * Path to proxy list local storage
     *
     * @var string
     */
    protected $filePath;

    /**
     * Cache time in seconds or null if infinity.
     *
     * @var string
     */
    protected $cacheTime;

    /**
     * Path to proxy list
     *
     * @var string
     */
    protected $fileLocation;

    /**
     * Json list of proxies
     *
     * @var string
     */
    protected $proxyList;

    /**
     * BaseProxyList constructor.
     *
     * @throws \Exception
     */
    public function __construct()
    {
        if (null === $this->fileLocation) {
            throw new \Exception('Invalid configuration exception. File location must be set');
        }
        if (null === $this->filePath) {
            throw new \Exception('Invalid configuration exception. File path must be set');
        }
    }

    /**
     * Get the response from remote host.
     *
     * @return string|null
     */
    abstract public function getResponse();

    /**
     * Generate array of proxies from response.
     * Must return like ip:port.
     *
     * @return array
     */
    abstract protected function generateProxyList();

    /**
     * Get proxy list from cache or generate new.
     *
     * @return array|null
     */
    public function getProxyList()
    {
        if ($this->isCacheExists()) {
            $this->proxyList = $this->proxyListFile();
        } else {
            $this->proxyList = $this->save();
        }

        return $this->proxyList;
    }

    /**
     * Process function. Do what you want.
     *
     * @return array|bool
     */
    protected function save()
    {
        $proxyList = $this->generateProxyList();
        if (0 === count($proxyList)) {
            return $this->proxyListFile();
        }
        $this->saveProxyList($proxyList);

        return $proxyList;
    }

    /**
     * Checks if cache is expired. Return true if expired.
     * FileSystem work but who care.
     *
     * @return bool
     */
    protected function isCacheExpired()
    {
        if (null === $this->cacheTime) {
            return false;
        } else {
            return (time() - filemtime($this->fileLocation)) > $this->cacheTime;
        }
    }

    /**
     * Checks if cache is exists.
     * FileSystem work but who care.
     *
     * @return bool
     */
    protected function isCacheExists()
    {
        if (!is_readable($this->fileLocation)) {
            return false;
        }

        return !$this->isCacheExpired();
    }

    /**
     * @return array
     */
    protected function proxyListFile()
    {
        return file($this->fileLocation);
    }

    /**
     * Save file
     * FileSystem work but who care.
     *
     * @param array $proxyList
     * @throws \Exception
     */
    protected function saveProxyList($proxyList)
    {
        try {
            unlink($this->fileLocation);
        } catch (\Exception $e) {
        }
        if ($file = fopen($this->fileLocation, 'wb')) {
            for ($i = (count($proxyList) - 1); $i >= 0; $i--) {
                fwrite($file, $proxyList[$i] . PHP_EOL);
            }
            fclose($file);
        } else {
            throw new \Exception('Something wrong');
        }
    }
}
