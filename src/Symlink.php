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

    /** @var  string */
    protected $_target;
    /** @var  string */
    protected $_destination;
    /** @var  array  */
    protected $_paths = [];
    /** @var  Validator */
    protected $_validator;

    /**
     * Symlink constructor.
     * @param array $folders
     */
    public function __construct(array $folders = [])
    {
        if (!empty($folders)) {
            if (isset($folders['target'])) {
                $this->_target = $folders['target'];
            }

            if (isset($folders['destination'])) {
                $this->_destination = $folders['destination'];
            }

            $this->setPaths(
                [
                    'target'        => $this->getTarget(),
                    'destination'   => $this->getDestination(),
                ]
            );
        }

        $this->_validator = new Validator();
    }

    /**
     * @param bool $asJson
     * @return string
     */
    public function validate($asJson = true)
    {
        return $this->_validator->validate($this->_paths, $asJson);
    }

    /**
     * @return bool
     */
    public function isValid()
    {
        return $this->_validator->isValid($this->_paths);
    }

    /**
     * @param $_target
     * @return $this
     */
    public function setTarget($_target)
    {
        $this->_target = $_target;
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
     * @param $_destination
     * @return $this
     */
    public function setDestination($_destination)
    {
        $this->_destination = $_destination;
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
     * @param bool $asJson
     * @return string
     */
    public function link($asJson = true)
    {
        if (!$this->isValid()) {
            return $this->validate();
        }

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
        if ($status === self::STATUS_SUCCESS) {
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