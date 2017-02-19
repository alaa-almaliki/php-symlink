<?php
namespace Symlink;

/**
 * Class Validator
 * @package Symlink
 * @author Alaa Al-Maliki <alaa.almaliki@gmail.com>
 */
class Validator
{
    const FILE_EXIST                = 1;
    const ERROR_FILE_IS_EMPTY       = -1;
    const ERROR_FILE_IS_ROOT        = -2;
    const ERROR_FILE_NOT_EXIST      = -3;
    const ERROR_FILE_NOT_WRITABLE   = -4;
    const ERROR_FILE_ALREADY_LINK   = -5;

    const PATH_TARGET               = 'target';
    const PATH_DESTINATION          = 'destination';

    /** @var  array  */
    protected $_paths = [];
    /** @var  string */
    protected $_target;
    /** @var  string */
    protected $_destination;

    /**
     * Validator constructor.
     * @param array $paths
     */
    public function __construct(array $paths = [])
    {
        if (!empty($paths)) {
            if (isset($paths['target'])) {
                $this->_target = $paths['target'];
            }

            if (isset($paths['destination'])) {
                $this->_destination = $paths['destination'];
            }

            $this->setPaths(
                [
                    'target'        => $this->getTarget(),
                    'destination'   => $this->getDestination(),
                ]
            );
        }
    }

    /**
     * @param array $paths
     * @return $this
     */
    public function setPaths(array $paths)
    {
        $this->_paths = $paths;
        return $this;
    }

    /**
     * @return array
     */
    public function getPaths()
    {
        return $this->_paths;
    }

    /**
     * @param $target
     * @return $this
     */
    public function setTarget($target)
    {
        $this->_target = $target;
        return $this;
    }

    /**
     * @return mixed|string
     */
    public function getTarget()
    {
        return $this->_target;
    }

    /**
     * @param $destination
     * @return $this
     */
    public function setDestination($destination)
    {
        $this->_destination = $destination;
        return $this;
    }

    /**
     * @return mixed|string
     */
    public function getDestination()
    {
        return $this->_destination;
    }

    /**
     * @param bool $isJson
     * @return array|string
     */
    public function validate($isJson = true)
    {
        $results = [];
        foreach ($this->getPaths() as $key => $path) {
            $results[] = $this->_validateFilePath($key, $path);
        }

        if ($isJson) {
            return json_encode($results);
        }

        return $results;
    }

    /**
     * @return bool
     */
    public function isValid()
    {
        $results = $this->validate(false);

        if (!is_array($results)) {
            return false;
        }

        $success = true;
        foreach ($results as $result) {
            $success &= $result['found'];
        }

        return $success;
    }

    /**
     * @param  string $key
     * @param  string $path
     * @return array
     */
    protected function _validateFilePath($key, $path)
    {
        switch ($path) {
            case '':
                $code = self::ERROR_FILE_IS_EMPTY;
                break;
            case '/':
                $code = self::ERROR_FILE_IS_ROOT;
                break;
            case !$this->_fileExists(realpath($path)):
                $code = self::ERROR_FILE_NOT_EXIST;
                break;
            case !$this->_isWritable(realpath($path)):
                $code = self::ERROR_FILE_NOT_WRITABLE;
                break;
            case !$this->_isValidLink($key, $path):
                $code = self::ERROR_FILE_ALREADY_LINK;
                break;
            default:
                $code = self::FILE_EXIST;
        }

        return $this->_getValidationResult($key, $code);
    }

    /**
     * @param string $key
     * @param int $resultCode
     * @return array
     */
    protected function _getValidationResult($key, $resultCode)
    {
        $result = [
            self::FILE_EXIST            => [
                'message'   => "{$key} is exist and writable.",
                'field'     => $key,
                'found'     => true
            ],
            self::ERROR_FILE_IS_EMPTY   => [
                'message'   => "{$key} cannot be empty.",
                'field'     => $key,
                'found'     => false
            ],
            self::ERROR_FILE_IS_ROOT    => [
                'message'   => "{$key} cannot be root directory.",
                'field'     => $key,
                'found'     => false
            ],
            self::ERROR_FILE_NOT_EXIST  => [
                'message'   => "{$key} is not exist.",
                'field'     => $key,
                'found'     => false
            ],
            self::ERROR_FILE_NOT_WRITABLE => [
                'message'   => "{$key} is not writable",
                'field'     => $key,
                'found'     => false,
            ],
            self::ERROR_FILE_ALREADY_LINK => [
                'message'   => "Destination is an existing link to the target directory",
                'field'     => $key,
                'found'     => false,
            ],
        ];

        $unknown = ['message' => "Unknown error.", 'field' => $key, 'found' => false];

        if (!in_array($resultCode, array_keys($result))) {
            return $unknown;
        }

        return $result[$resultCode];
    }

    /**
     * @param  string $key
     * @param  string $path
     * @return bool
     */
    protected function _isValidLink($key, $path)
    {
        $link = [
            rtrim($this->_paths['destination'], '/'),
            trim(basename($path), '/')
        ];

        return !($this->_isLink(implode('/', $link)) && $this->_isTarget($key));
    }

    /**
     * @param  string $path
     * @return bool
     */
    protected function _isLink($path)
    {
        return is_link($path);
    }

    /**
     * @param  string $path
     * @return bool
     */
    protected function _fileExists($path)
    {
        return file_exists($path);
    }

    /**
     * @param  string $path
     * @return bool
     */
    protected function _isWritable($path)
    {
        return is_writable($path);
    }

    /**
     * @param  string $key
     * @return bool
     */
    protected function _isTarget($key)
    {
        return $key === self::PATH_TARGET;
    }

    /**
     * @param  string $key
     * @return bool
     */
    protected function _isDestination($key)
    {
        return $key === self::PATH_DESTINATION;
    }
}