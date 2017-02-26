<?php
namespace Symlink;

/**
 * Class Symlink
 * @package Symlink
 * @author Alaa Al-Maliki <alaa.almaliki@gmail.com>
 */
class Symlink implements SymlinkInterface
{
    const STATUS_SUCCESS = 0;

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
            return $this->validate();
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
            $linkDestination = [
                basename($this->_validator->getTarget()),
                trim(substr($filePath, strlen($this->_validator->getTarget()), strlen($filePath)), '/')
            ];

            $link = rtrim($this->_validator->getDestination(), '/') . '/' . implode('/', $linkDestination);
            $dynamicPart = [];
            $parts = explode('/', pathinfo(implode('/', $linkDestination), PATHINFO_DIRNAME));

            foreach ($parts as $part) {
                $dynamicPart [] = $part;
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
                        $results[] = [
                            'status' => false,
                            'message' => sprintf('%s with wrong permission %s and is not writable',$path, $mod)
                        ];
                        break 2;
                    }
                }
            }

            if ($this->_canUnlink($link)) {
                unlink($link);
            }

            $status = symlink($filePath, $link);
            $message = $status? '%s Linked successfully' : 'There was error linking %s';
            $results [] = [
                'status' => $status,
                'message' => sprintf($message, $link),
            ];
        }

        return $results;
    }
}