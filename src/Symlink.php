<?php
namespace Symlink;

/**
 * Class Symlink
 * @package Symlink
 * @author Alaa Al-Maliki <alaa.almaliki@gmail.com>
 */
class Symlink implements SymlinkInterface
{
    /** @var  Validator */
    protected $_validator;

    /**
     * Symlink constructor.
     * @param Validator $validator
     */
    public function __construct(Validator $validator)
    {
        $this->_validator = $validator;
    }

    /**
     * @param  bool $asJson
     * @return string
     */
    public function validate($asJson = true)
    {
        return $this->_validator->validate($asJson);
    }

    /**
     * @return bool
     */
    public function isValid()
    {
        return $this->_validator->isValid();
    }

    /**
     * @param  bool $asJson
     * @return array|string
     */
    public function link($asJson = true)
    {
        if (!$this->isValid()) {
            return $this->validate($asJson);
        }

        $results = $this->_link(File::listFiles($this->_validator->getTarget()));

        if ($asJson) {
            return json_encode($results);
        }

        return $results;
    }

    /**
     * @param  string $link
     * @return bool
     */
    protected function _canUnlink($link)
    {
        return $this->_validator->isClean() && file_exists($link);
    }

    /**
     * @param  array $files
     * @return array
     */
    protected function _link(array $files)
    {
        $results = [];
        foreach ($files as $filename => $filePath) {
            $linkSinceTarget = trim(substr($filePath, strlen($this->_validator->getTarget()), strlen($filePath)), '/');
            $realLink = rtrim($this->_validator->getDestination(), '/') . '/' . $linkSinceTarget;
            $parts = explode('/', pathinfo($linkSinceTarget, PATHINFO_DIRNAME));

            try {
                $this->_resolveDirectories($parts);
            } catch (\Exception $e) {
                $results[] = [
                    'status' => false,
                    'message' => $e->getMessage(),
                ];
                break;
            }

            if ($this->_canUnlink($realLink)) {
                unlink($realLink);
            }

            $status = @symlink($filePath, $realLink);
            $message = $status? '%s Linked successfully' : 'There was error linking %s';
            $results [] = [
                'status' => $status,
                'message' => sprintf($message, $realLink),
            ];
        }

        return $results;
    }

    /**
     * @param  array $parts
     * @throws \Exception
     */
    protected function _resolveDirectories(array $parts)
    {
        $dynamicPart = [];
        foreach ($parts as $part) {
            $dynamicPart[] = $part;
            $path = $this->_validator->getDestination() . '/' . implode('/', $dynamicPart);

            if (is_file($path)) {
                continue;
            }

            if (!is_dir($path)) {
                mkdir($path, 0777, true);
                chmod($path, 0777);
            } else {
                $mod = decoct(fileperms($path) & 0777);
                if ($mod != 777) {
                    throw new \Exception(sprintf('%s with wrong permission %s and is not writable', $path, $mod));
                }
            }
        }
    }
}