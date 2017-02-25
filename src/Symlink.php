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
     * @param bool $asJson
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
     * @param bool $asJson
     * @return string
     */
    public function link($asJson = true)
    {
        if (!$this->isValid()) {
            return $this->validate();
        }

        $files = File::listFiles($this->_validator->getTarget());

        foreach ($files as $file) {
            symlink($file, $this->_validator->getDestination());
        }
    }

    /**
     * @param  int $status
     * @return array
     */
    protected function _getResults($status)
    {
        $message = "Error, could not link target folder: {$this->_validator->getTarget()}";
        if ($status === self::STATUS_SUCCESS) {
            $message = "Target: {$this->_validator->getTarget()} was linked successfully.";
        }

        return ['message' => $message, 'status' => $status];
    }

    /**
     * @param $cmd
     * @param $output
     * @param $status
     * @return $this
     */
    protected function _executeShell($cmd, &$output, &$status)
    {
        exec($cmd, $output, $status);
        return $this;
    }
}