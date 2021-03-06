<?php
/**
 * SyDES - Lightweight CMF for a simple sites with SQLite database
 *
 * @package   SyDES
 * @copyright 2011-2017, ArtyGrand <artygrand.ru>
 * @license   GNU GPL v3 or later; see LICENSE
 */
namespace App\Exception;

class RedirectException extends \Exception
{
    protected $url;

    public function __construct($url)
    {
        $this->url = $url;
        parent::__construct();
    }

    public function getUrl()
    {
        return $this->url;
    }
}
