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
     * @return string
     */
    public function link()
    {
        $result =  shell_exec(sprintf('ln -sf %s %s', $this->getTarget(), $this->getDestination()));
        return json_encode(['success' => $result]);
    }
}