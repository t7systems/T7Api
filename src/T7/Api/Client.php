<?php

namespace T7\Api;

use SoapClient;
use ArrayAccess;
use T7\Contracts\CacheInterface;

class Client
{
    const CONTENT_TYPE_LIVE_PREVIEW_PIC  = 3;
    const CONTENT_TYPE_VIDEO_PREVIEW_PIC = 4;
    const CONTENT_TYPE_CHAT              = 6;

    /**
     * @var int
     */
    private $reqId;

    /**
     * @var string
     */
    private $secretKey;

    /**
     * @var array
     */
    private $categories;

    /**
     * @var array
     */
    private $cams;

    /**
     * @var ArrayAccess
     */
    private $app;

    /**
     * @var CacheInterface;
     */
    private $cache;

    /**
     * @var array
     */
    private $config;

    public function __construct(ArrayAccess $app)
    {
        $this->app        = $app;
        $this->cache      = $app['cache'];
        $this->config     = $app['cfg'];
        $this->soap       = $app['soap'];
        $this->reqId      = $this->config['reqId'];
        $this->secretKey  = $this->config['secretKey'];
        $this->cams       = null;
        $this->categories = null;
    }

    /**
     * Start a new chat session
     * @param int $camId
     * @param int $seconds
     * @param string $nickname
     * @param boolean $startVoyeur
     * @param boolean $showCam2Cam
     * @param boolean $showSendSound
     * @return array Array with 'sessionId' and 'url' (use for redirect or iFrame)
     */
    public function getChat($camId, $seconds, $nickname, $startVoyeur, $showCam2Cam, $showSendSound, $sendsound)
    {
        $chatSession = $this->getSoapClient()->beginChatSession($this->reqId, $camId, $seconds, $nickname, $startVoyeur);

        $params = array(
            'reqid='         . $this->reqId,
            'csid='          . $chatSession->sessID,
            'quittarget='    . '_parent', //legacy parameter, just ignore that, but keep it here ;)
            'quiturl='       . urlencode($this->config['quitUrl']),
            'showcam2cam='   . ($showCam2Cam   ? '1' : '0'),
            'showsendsound=' . ($showSendSound ? '1' : '0'),
            'sendsound='     . ($sendsound     ? '1' : '0')
        );

        return array(
            'sessionId' => $chatSession->sessID,
            'url'       => $this->getContentURL(self::CONTENT_TYPE_CHAT, $params)
        );
    }

    /**
     * Send a keep alive to extend the session
     * @param int $sessionId
     * @param int $seconds
     * @return mixed
     */
    public function keepAliveChatSession($sessionId, $seconds)
    {
        return $this->getSoapClient()->keepAliveChatSession($this->reqId, $sessionId, $seconds);
    }

    /**
     * Get session status (state, start, stop)
     * @param $sessionId
     * @return mixed
     */
    public function getChatStatus($sessionId)
    {
        return $this->getSoapClient()->getChatStatus($this->reqId, $sessionId);
    }

    /**
     * End session
     * @param int $sessionId
     * @return mixed
     */
    public function endChatSession($sessionId)
    {
        return $this->getSoapClient()->endChatSession($this->reqId, $sessionId);
    }

    /**
     * Returns a URL for a live preview picture of the cam.
     * This may be a placeholder image if the cam does not support live preview!
     * @param int $camId
     * @return string
     */
    public function getLivePreviewPic($camId)
    {
        $params = array(
            'reqid='.$this->reqId,
            'camid='.$camId
        );

        return $this->getContentURL(self::CONTENT_TYPE_LIVE_PREVIEW_PIC, $params);
    }

    /**
     * Returns an array with all available categories
     * @param string $lang
     * @return null
     */
    public function getCategories($lang)
    {
        if ($this->categories == NULL) {

            $cached = $this->getFromCache('categories_' . $lang);

            if ($cached['data']) {
                $this->categories = $cached['data'];
            } else {
                $this->categories = $this->getSoapClient()->getCategoriesI18N($this->reqId, $lang);
                $this->updateCache('categories_' . $lang, $this->categories, $this->config['cache']['ttl']['categories']);
            }

        }

        return $this->categories;
    }

    /**
     * Returns an array with online cams for given category
     * @param int $categoryId
     * @param string $lang
     * @return mixed
     */
    public function getOnlineCams($categoryId, $lang)
    {

        if (!isset($categoryId) || $categoryId <= 0) {
          return $this->getAllOnlineCams($lang);
        }

        if (!isset($this->cams[$categoryId])) {

            $cached = $this->getFromCache('cams' . '_' . $categoryId . '_' . $lang);

            if ($cached['data']) {
                $this->cams[$categoryId] = $cached['data'];
            } else {
                $this->cams[$categoryId] = $this->getSoapClient()->getOnlineCamsI18N($this->reqId, $categoryId, $lang);
                $this->updateCache('cams' . '_' . $categoryId . '_' . $lang, $this->cams[$categoryId], $this->config['cache']['ttl']['cams']);
            }

        }
        return $this->cams[$categoryId];
    }

    /**
     * Returns a merged array of all online cams
     * @param string $lang
     * @return mixed
     */
    public function getAllOnlineCams($lang)
    {
        if ($this->categories == NULL) {
            $this->getCategories($lang);
        }

        foreach ($this->categories as $cat) {
            $catId              = $cat->catID;
            $this->cams[$catId] = $this->getOnlineCams($catId, $lang);
        }

        return call_user_func_array('array_merge', $this->cams);
    }

    /**
     * @return SoapClient
     */
    protected function getSoapClient()
    {
        return $this->app['soap'];
    }

    /**
     * @param $contentType
     * @param $params
     * @return string
     */
    protected function getContentURL($contentType, $params)
    {
        $url       = $this->config['urls']['content'].'?';
        $query     = 'ctype='.$contentType.'&';
        $query    .= implode('&',$params);
        $signature = sha1($query.$this->secretKey,false);
        $url      .= $query.'&signature='.$signature;

        return $url;
    }

    /**
     * @param string $key
     * @return array ('data', 'time')
     */
    protected function getFromCache($key)
    {
        return $this->cache->get($key);
    }

    /**
     * @param string $key
     * @param mixed $value Serializable value
     * @param int $ttl Time to live in seconds
     * @return void
     */
    protected function updateCache($key, $value, $ttl)
    {
        $this->cache->set($key, $value, $ttl);
    }
}
