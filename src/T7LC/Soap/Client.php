<?php

namespace T7LC\Soap;

use SoapClient;
use T7LC\Soap\Contracts\CacheInterface;

/**
 * Class Client
 * Wraps SoapClient calls and caches results.
 * @package T7LC\Soap
 */
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
     * @var CacheInterface;
     */
    private $cache;

    /**
     * @var SoapClient
     */
    private $soap;

    /**
     * @var array
     */
    private $options;

    public function __construct(SoapClient $soap, CacheInterface $cache, array $options)
    {
        $defaults = array(
            'contentUrl'  => 'https://content.777live.com/soap/1_4/getcontent.php',
            'quitUrl'     => '/?chatExit',
            'cache'       => array(
                'ttl'     => array(
                    'categories' => 3600,
                    'cams'       => 60,
                    'sedcards'   => 3600,
                ),
            )
        );

        $this->cache      = $cache;
        $this->options    = array_replace_recursive($defaults, $options);
        $this->soap       = $soap;
        $this->reqId      = $this->options['reqId'];
        $this->secretKey  = $this->options['secretKey'];
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
            'quiturl='       . urlencode($this->options['quitUrl']),
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
                $this->updateCache('categories_' . $lang, $this->categories, $this->options['cache']['ttl']['categories']);
            }

        }

        return $this->categories;
    }

    /**
     * Returns an array with online cams for given category (category=0 => all categories)
     * @param int $categoryId
     * @param string $lang
     * @return mixed
     */
    public function getOnlineCams($categoryId, $lang)
    {

        if (!isset($categoryId) || $categoryId <= 0) {
            $categoryId = 0;
        }

        if (!isset($this->cams[$categoryId])) {

            $cached = $this->getFromCache('cams' . '_' . $categoryId . '_' . $lang);

            if ($cached['data']) {
                $this->cams[$categoryId] = $cached['data'];
            } else {
                $this->cams[$categoryId] = $this->getSoapClient()->getOnlineCamsI18N($this->reqId, $categoryId, $lang);
                $this->updateCache('cams' . '_' . $categoryId . '_' . $lang, $this->cams[$categoryId], $this->options['cache']['ttl']['cams']);
            }

        }
        return $this->cams[$categoryId];
    }

    public function getSedcard($camId, $lang)
    {

        $cached = $this->getFromCache('sedcard_' .$camId . '_' . $lang);

        if ($cached['data']) {
            $sedcard =  $cached['data'];
        } else {
            $sedcard = $this->getSoapClient()->getSetcardI18N($this->reqId, $camId, $lang);
            $this->updateCache('sedcard_' .$camId . '_' . $lang, $sedcard, $this->options['cache']['ttl']['sedcards']);
        }

        return $sedcard;
    }

    public function getFreePictureGallery($camId, $size = 'm')
    {

        $cached = $this->getFromCache('previewpics_' .$camId . '_' . $size);

        if ($cached['data']) {
            $pics =  $cached['data'];
        } else {
            $pics = $this->getSoapClient()->getFreePictureGallery($this->reqId, $camId, $size);
            $this->updateCache('previewpics_' .$camId . '_' . $size, $pics, $this->options['cache']['ttl']['sedcards']);
        }

        return $pics;
    }

    public function getFreeVideo($camId)
    {
        $countpreview = 1;

        $cached = $this->getFromCache('freevideo_' .$camId . '_' . $countpreview);

        if ($cached['data']) {
            $video =  $cached['data'];
        } else {
            try {
                $video = $this->getSoapClient()->getFreeVideo($this->reqId, $camId, $countpreview);
            } catch (\SoapFault $x) {
                $video = null;
            }
            $this->updateCache('freevideo_' .$camId . '_' . $countpreview, $video, $this->options['cache']['ttl']['sedcards']);
        }

        return $video;
    }

    /**
     * @return SoapClient
     */
    protected function getSoapClient()
    {
        return $this->soap;
    }

    /**
     * @param $contentType
     * @param $params
     * @return string
     */
    protected function getContentURL($contentType, $params)
    {
        $url       = $this->options['contentUrl'].'?';
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
