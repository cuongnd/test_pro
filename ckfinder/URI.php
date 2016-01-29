<?php

class URI
{
    private $_uri;

    private static function _parse($uri)
    {
        $parts = (object) array();

        if (preg_match('/\#(.*)$/', $uri, $m)) // check for fragment
        {
            $parts->fragment = $m[1];
            $uri = substr($uri, 0 - (strlen($parts->fragment) + 1));
        }

        if (preg_match('/^([a-zA-Z]\w*):/', $uri, $m)) // is absolute
        {
            // set scheme

            $parts->scheme = $m[1];
            $uri = substr($uri, strlen($parts->scheme) + 1);

            if (substr($uri, 0, 2) == '//') // is common
            {
                $uri = substr($uri, 2);

                // check for query

                $m = explode('?', $uri, 2);

                if (count($m) == 2)
                    $parts->query = $m[1];

                $uri = $m[0];

                // check for path

                $m = explode('/', $uri, 2);

                if (count($m) == 2)
                    $parts->path = $m[1];

                // set authority

                $parts->authority = $m[0];
            }

            else // is opaque
            {
                $parts->opaque = $uri;
            }
        }

        else // is relative
        {
            // check for query

            $m = explode('?', $uri, 2);

            if (count($m) == 2)
                $parts->query = $m[1];

            // set path

            $parts->path = $m[0];
        }

        return $parts;
    }

    private static function _unparse(&$parts)
    {
        if (!is_null($parts->scheme)) // is absolute
        {
            $uri = $parts->scheme. ':';

            if (is_null($parts->opaque)) // is common
            {
                $uri .= '//';
                $uri .= $parts->authority;
                $uri .= '/'. $parts->path;

                if (!is_null($parts->query))
                    $uri .= '?'. $parts->query;

                if (!is_null($parts->fragment))
                    $uri .= '#'. $parts->fragment;
            }

            else
            {
                $uri .= $parts->opaque;

                if (!is_null($parts->fragment))
                    $uri .= '#'. $parts->fragment;
            }
        }

        else // is relative
        {
            $uri = $parts->path;

            if (!is_null($parts->query))
                $uri .= '?'. $parts->query;

            if (!is_null($parts->fragment))
                $uri .= '#'. $parts->fragment;
        }

        return $uri;
    }

    private static function _parseQuery($query)
    {
        $params = array();
        $vars = explode('&', $query);

        foreach ($vars as $var)
        {
            list($key, $value) = explode('=', $var, 2);

            if (is_string($value))
                $value = urldecode($value);

            if (is_array($params[$key]))
            {
                $params[$key][] = $value;
            }

            else if (is_string($params[$key]))
            {
                $params[$key] = array($params[$key], $value);
            }

            else
            {
                $params[$key] = $value;
            }
        }

        return $params;
    }

    private static function _unparseQuery($params)
    {
        $vars = array();

        foreach ($params as $key => &$value)
        {
            if (is_null($value))
            {
                $vars[] = $key;
            }

            else
            {
                $value = array($value);

                $vars[] = implode('&', array_map(function ($value) use ($key)
                {
                    return $key. '='. urlencode($value);
                }, $value));
            }
        }

        $query = implode('&', array_filter($vars, function ($var)
        {
            return strlen($var);
        }));

        return $query;
    }

    public static function create($uri='')
    {
        return new self($uri);
    }

    public static function createFromEnv()
    {
        return self::create()->fromEnv();
    }

    public function fromEnv()
    {
        return $this->fromString($_SERVER['REQUEST_URI']);
    }

    public function __construct($uri='')
    {
        $this->_uri = $uri;
    }

    public function __toString()
    {
        return $this->_uri;
    }

    public function toString()
    {
        return $this->_uri;
    }

    public function fromString($uri)
    {
        $this->_uri = $uri;

        return $this;
    }

    public function scheme($scheme=null)
    {
        $parts = self::_parse($this->_uri);

        if (is_null($scheme))
        {
            return $parts->scheme;
        }

        else
        {
            $parts->scheme = $scheme;

            $this->_uri = self::_unparse($parts);

            return $this;
        }
    }

    public function opaque($opaque=null)
    {
        $parts = self::_parse($this->_uri);

        if (is_null($opaque))
        {
            return $parts->opaque;
        }

        else
        {
            $parts->opaque = $opaque;

            $this->_uri = self::_unparse($parts);

            return $this;
        }
    }

    public function authority($authority=null)
    {
        $parts = self::_parse($this->_uri);

        if (is_null($authority))
        {
            return $parts->authority;
        }

        else
        {
            $parts->authority = $authority;

            $this->_uri = self::_unparse($parts);

            return $this;
        }
    }

    public function path($path=null)
    {
        $parts = self::_parse($this->_uri);

        if (is_null($path))
        {
            return $parts->path;
        }

        else
        {
            $parts->path = $path;

            $this->_uri = self::_unparse($parts);

            return $this;
        }
    }

    public function query($query=null)
    {
        $parts = self::_parse($this->_uri);

        if (is_null($query))
        {
            return $parts->query;
        }

        else
        {
            $parts->query = $query;

            $this->_uri = self::_unparse($parts);

            return $this;
        }
    }

    public function fragment($fragment=null)
    {
        $parts = self::_parse($this->_uri);

        if (is_null($fragment))
        {
            return $parts->fragment;
        }

        else
        {
            $parts->fragment = $fragment;

            $this->_uri = self::_unparse($parts);

            return $this;
        }
    }

    public function host($host=null)
    {
        $parts = self::_parse($this->_uri);

        if (is_null($host))
        {
            list ($host) = explode(':', $parts->authority);

            return $host;
        }

        else
        {
            list (, $port) = explode(':', $parts->authority);

            $parts->authority = implode(':', array($host, $port));

            $this->_uri = self::_unparse($parts);

            return $this;
        }
    }

    public function port($port=null)
    {
        $parts = self::_parse($this->_uri);

        if (is_null($port))
        {
            list (, $port) = explode(':', $parts->authority);

            return $port;
        }

        else
        {
            list ($host) = explode(':', $parts->authority);

            $parts->authority = implode(':', array($host, $port));

            $this->_uri = self::_unparse($parts);

            return $this;
        }
    }

    public function param($key, $value=null)
    {
        $params = $this->params();

        if (is_null($value))
        {
            return $params[$key];
        }

        else
        {
            $params[$key] = $value;

            $this->params($params);

            return $this;
        }
    }

    public function params($params=null)
    {
        if (is_null($params))
        {
            return self::_parseQuery($this->query());
        }

        else
        {
            $this->query(self::_unparseQuery($params));

            return $this;
        }
    }

    public function push_segment($segment)
    {
        $segments = $this->segments();
        $segments[] = $segment;
        $this->segments($segments);

        return $this;
    }

    public function pop_segment()
    {
        $segments = $this->segments();
        $segment = array_pop($segments);
        $this->segments($segments);

        return $segment;
    }

    public function segment($i, $value=null)
    {
        if (is_null($value))
        {
            $segments = $this->segments();
            return $segments[$r];
        }

        else
        {
            $segments = $this->segments();
            $segments[$r] = $value;
            $this->segments($segments);

            return $this;
        }
    }

    public function rsegment($r, $value=null)
    {
        if (is_null($value))
        {
            $segments = $this->segments();
            return $segments[(count($segments) -1) + $r];
        }

        else
        {
            $segments = $this->segments();
            $segments[(count($segments) -1) + $r] = $value;
            $this->segments($segments);

            return $this;
        }
    }

    public function segments($segments=null)
    {
        $parts = self::_parse($this->_uri);

        if (is_null($segments))
        {
            $path = ltrim($parts->path, '/');

            if ($path == '')
                return array();

            return array_map(function ($segment)
            {
                return urldecode($segment);
            }, explode('/', $path));
        }

        else
        {
            $parts->path = implode('/', array_map(function ($segment)
            {
                return urlencode($segment);
            }, $segments));

            $this->_uri = self::_unparse($parts);

            return $this;
        }
    }
}