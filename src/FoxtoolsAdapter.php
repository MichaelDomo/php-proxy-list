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
    private $_url = 'http://api.foxtools.ru/v2/Proxy?uptime=1';

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
     */
    public function getResponse()
    {
        if (null === $this->_response) {
            $response = file_get_contents($this->_url);
            $this->_response = json_decode((string) $response, true);
        }

        return $this->_response;
    }

    /**
     * @inheritdoc
     * @throws \InvalidArgumentException
     */
    protected function generateProxyList()
    {
        if (false === (bool) ($list = $this->getResponse())) {
            throw new \InvalidArgumentException('JSON list of proxies not set');
        }
        $proxyList = [];
        /* @var $items array */
        $items = $list['response']['items'];
        foreach ($items as $item) {
            $proxyList[] = $item['ip'] . ':' . $item['port'];
        }

        return $proxyList;
    }
}
