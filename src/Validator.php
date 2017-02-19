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

    /**
     * @param  array $paths
     * @param  boolean $isJson
     * @return array|string
     */
    public function validate(array $paths = [], $isJson = true)
    {
        $results = [];
        foreach ($paths as $key => $path) {
            $results[] = $this->_validateFilePath($key, $path);
        }

        if ($isJson) {
            return json_encode($results);
        }

        return $results;
    }

    /**
     * @param  array $paths
     * @return bool
     */
    public function isValid(array $paths)
    {
        $results = $this->validate($paths, false);

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
            ]
        ];

        $unknown = ['message' => "Unknown error.", 'field' => $key, 'found' => false];

        if (!in_array($resultCode, array_keys($result))) {
            return $unknown;
        }

        $result['code'] = $resultCode;
        return $result[$resultCode];
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
}