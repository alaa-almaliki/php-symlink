<?php
use Symlink\Symlink;
use Symlink\Validator;

if (php_sapi_name() !== 'cli') {
    die('The Script can not be run from the browser.');
}

require_once dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'autoload.php';

/**
 * Class ShellSymlink
 * @author Alaa Al-Maliki <alaa.almaliki@gmail.com>
 */
class ShellSymlink
{
    public function run()
    {
        $params = [];
        $params['target']       = rtrim($this->_getInput("Target Directory:"), ' |/') . '/';
        $params['destination']  = rtrim($this->_getInput("Destination Directory:"), ' |/') . '/';
        $params['clean']        = $this->_isClean(trim($this->_getInput("Clean Link? y/n: ")));
        $params['action']       = \Symlink\SymlinkInterface::ACTION_LINK;

        $symlink = new Symlink(new Validator($params));
        $results = $symlink->link(false);
        foreach ($results as $result) {
            $this->_output($result['message']);
        }
    }

    /**
     * @param  string $input
     * @return string
     */
    protected function _isClean($input)
    {
        $values = [
            'y',
            'Y',
            'yes',
            'Yes'
        ];

        return in_array($input, $values)? 'true' : 'false';
    }

    /**
     * @param  string $input
     * @return string
     */
    protected function _getInput($input)
    {
        return readline($input);
    }

    /**
     * @param string $message
     */
    protected function _output($message)
    {
        echo $message;
        echo PHP_EOL;
    }

}

(new ShellSymlink())->run();