<?php

namespace Craft\Http\Request;

class Accept
{

    /** @var array */
    public $medias = [];

    /** @var string */
    public $language;

    /** @var string */
    public $encoding;

    /** @var string */
    public $charset;


    /**
     * Custom HTTP Accept
     * @param array $medias
     * @param string $language
     * @param string $encoding
     * @param string $charset
     */
    public function __construct(array $medias, $language, $encoding, $charset)
    {
        $this->medias = $medias;
        $this->language = $language;
        $this->encoding = $encoding;
        $this->charset = $charset;
    }


    /**
     * Negociate the best media
     * @param array $medias
     * @return string|bool
     */
    public function negociate(...$medias)
    {
        foreach($this->medias as $media => $quality) {
            if(in_array($media, $medias)) {
                return $media;
            }
        }

        return false;
    }


    /**
     * Create from env
     * @return static
     */
    public static function create()
    {
        // parse medias
        $medias = [];
        if(isset($_SERVER['HTTP_ACCEPT'])) {
            $rows = explode(',', $_SERVER['HTTP_ACCEPT']);
            foreach($rows as $row) {
                @list($media, $quality) = explode(';', $row);
                if(!$quality) {
                    $quality = 1;
                }
                $medias[trim($media)] = (int)trim($quality);
            }
        }

        // parse language
        $language = null;
        if(isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
            $language = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
        }

        // parse encoding
        $encoding = null;
        if(isset($_SERVER['HTTP_ACCEPT_ENCODING'])) {
            $encoding = $_SERVER['HTTP_ACCEPT_ENCODING'];
        }

        // parse charset
        $charset = null;
        if(isset($_SERVER['HTTP_ACCEPT_CHARSET'])) {
            $charset = $_SERVER['HTTP_ACCEPT_CHARSET'];
        }

        return new static($medias, $language, $encoding, $charset);
    }

} 