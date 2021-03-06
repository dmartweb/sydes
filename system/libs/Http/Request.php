<?php
/**
 * SyDES - Lightweight CMF for a simple sites with SQLite database
 *
 * @package   SyDES
 * @copyright 2011-2017, ArtyGrand <artygrand.ru>
 * @license   GNU GPL v3 or later; see LICENSE
 */
namespace App\Http;

use Zend\Diactoros\ServerRequest;

class Request extends ServerRequest
{
    /**
     * @return bool
     */
    public function isGet()
    {
        return $this->getMethod() == 'GET';
    }

    /**
     * @return bool
     */
    public function isPost()
    {
        return $this->getMethod() == 'POST';
    }

    /**
     * @return bool
     */
    public function isPut()
    {
        return $this->getMethod() == 'PUT';
    }

    /**
     * @return bool
     */
    public function isPatch()
    {
        return $this->getMethod() == 'PATCH';
    }

    /**
     * @return bool
     */
    public function isDelete()
    {
        return $this->getMethod() == 'DELETE';
    }

    /**
     * @return bool
     */
    public function isHead()
    {
        return $this->getMethod() == 'HEAD';
    }

    /**
     * @return bool
     */
    public function isOptions()
    {
        return $this->getMethod() == 'OPTIONS';
    }

    /**
     * @return bool
     */
    public function isAjax()
    {
        return $this->getHeaderLine('X-Requested-With') == 'XMLHttpRequest';
    }

    /**
     * @return bool
     */
    public function isSecure()
    {
        return $this->getUri()->getScheme() == 'https';
    }

    /**
     * Determine if the request contains a non-empty value for an input item.
     *
     * @param string|array $key
     * @return bool
     */
    public function has($key)
    {
        $keys = is_array($key) ? $key : func_get_args();

        foreach ($keys as $value) {
            if ($this->isEmptyString($value)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Determine if the given input key is an empty string for "has".
     *
     * @param  string $key
     * @return bool
     */
    protected function isEmptyString($key)
    {
        $value = $this->input($key);
        $boolOrArray = is_bool($value) || is_array($value);
        return !$boolOrArray && trim((string)$value) === '';
    }

    /**
     * Gets a "parameter" value from request.
     *
     * @param string $key     the key
     * @param mixed  $default the default value
     * @return mixed
     */
    public function input($key, $default = null)
    {
        $postParams = $this->getParsedBody();
        $getParams = $this->getQueryParams();
        $result = $default;
        if (isset($postParams[$key])) {
            $result = $postParams[$key];
        } elseif (isset($getParams[$key])) {
            $result = $getParams[$key];
        }
        return $result;
    }

    /**
     * Gets a required "parameter" value from request or throws Exception.
     *
     * @param string $key the key
     * @return mixed
     * @throws \RuntimeException
     */
    public function getRequired($key)
    {
        $value = $this->input($key);
        if (is_null($value) || ($value === '')) {
            throw new \RuntimeException(sprintf(t('error_parameter_required'), $key));
        }
    }

    /**
     * Get a subset of the items from the input data.
     *
     * @param  array|mixed $keys
     * @return array
     */
    public function only($keys)
    {
        $keys = is_array($keys) ? $keys : func_get_args();
        $results = [];
        $input = $this->all();
        foreach ($keys as $key) {
            $results[$key] = isset($input[$key]) ? $input[$key] : null;
        }
        return $results;
    }

    /**
     * Get all of the input for the request.
     *
     * @return array
     */
    public function all()
    {
        return array_replace_recursive($this->getParsedBody(), $this->getQueryParams());
    }

    public function getIp()
    {
        $server = $this->getServerParams();
        return $server['REMOTE_ADDR'];
    }
}
