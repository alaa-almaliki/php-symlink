<?php
namespace Symlink;

/**
 * Class Symlink
 * @package Symlink
 * @author Alaa Al-Maliki <alaa.almaliki@gmail.com>
 */
class Symlink
{
    /** @var  string */
    protected $target;
    /** @var  string */
    protected $destination;

    /**
     * Symlink constructor.
     * @param array $folders
     */
    public function __construct(array $folders = [])
    {
        if (!empty($folders)) {
            if (isset($folders['target'])) {
                $this->target = $folders['target'];
            }

            if (isset($folders['destination'])) {
                $this->destination = $folders['destination'];
            }
        }
    }

    /**
     * @param $target
     * @return $this
     */
    public function setTarget($target)
    {
        $this->target = $target;
        return $this;
    }

    /**
     * @return mixed|string
     */
    public function getTarget()
    {
        return $this->target;
    }

    /**
     * @param $destination
     * @return $this
     */
    public function setDestination($destination)
    {
        $this->destination = $destination;
        return $this;
    }

    /**
     * @return mixed|string
     */
    public function getDestination()
    {
        return $this->destination;
    }

    /**
     * @param bool $asJson
     * @return string
     */
    public function link($asJson = true)
    {
        $this->_executeShell(
            sprintf('ln -sf %s %s', $this->getTarget(), $this->getDestination()),
            $output,
            $status
        );

        if ($asJson) {
            return json_encode($this->_getResults($status));
        }

        return $this->_getResults($status);
    }

    /**
     * @param  int $status
     * @return array
     */
    protected function _getResults($status)
    {
        $message = "Error, could not link target folder: {$this->getTarget()}";
        if ($status === 0) {
            $message = "Target: {$this->getTarget()} was linked successfully.";
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