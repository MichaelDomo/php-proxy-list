<?php

namespace michaeldomo\proxylist;

/**
 * Class ProxyList
 * @package app\modules\parser\models\base
 *
 * @property array $list
 */
class FoxtoolsAdapter extends BaseProxyList
{
    /**
     * File name
     *
     * @var string
     */
    const FILE_NAME = 'foxtools-proxy.txt';

    /**
     * Url to remote proxy list
     *
     * @var string
     */
    private $_url = 'http://api.foxtools.ru/v2/Proxy.txt?uptime=1';

    /**
     * Json list of proxies
     *
     * @var string
     */
    private $_response;

    /**
     * BaseProxyList constructor.
     *
     * @inheritdoc
     * @param string $filePath
     * @param $cacheTime
     */
    public function __construct($filePath = '.', $cacheTime = 7200)
    {
        $this->filePath = $filePath;
        $this->cacheTime = $cacheTime;
        $this->fileLocation = $this->filePath . '/' . self::FILE_NAME;
        parent::__construct();
    }

    /**
     * @inheritdoc
     * @return array|null
     */
    public function getResponse()
    {
        if (null === $this->_response) {
            $this->_response = file($this->_url);
        }

        return $this->_response;
    }

    /**
     * @inheritdoc
     * @throws \InvalidArgumentException
     */
    protected function generateProxyList()
    {
        if (false === (bool) ($items = $this->getResponse())) {
            throw new \InvalidArgumentException('List of proxies not set');
        }
        $result = [];
        foreach ($items as $item) {
            $result[] = trim($item);
        }

        return $result;
    }
}
