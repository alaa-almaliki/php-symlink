<?php
namespace Symlink;

/**
 * Interface SymlinkInterface
 * @package Symlink
 * @author Alaa Al-Maliki <alaa.almaliki@gmail.com>
 */
interface SymlinkInterface
{
    const ACTION_LINK = 'symlink';
    const ACTION_VALIDATE = 'validate';

    /**
     * @param  bool $asJason
     * @return mixed
     */
    public function validate($asJason = true);

    /**
     * @return bool
     */
    public function isValid();
}