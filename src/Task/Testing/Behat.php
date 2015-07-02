<?php
namespace Robo\Task\Testing;

use Robo\Contract\PrintedInterface;
use Robo\Exception\TaskException;
use Robo\Task\BaseTask;
use Robo\Contract\CommandInterface;

/**
 * Executes Behat tests
 *
 * ``` php
 * <?php
 * // config
 * $this->taskBehat()
 *      ->tags()
 *      ->run();
 *
 * ?>
 * ```
 *
 */
class Behat extends BaseTask implements CommandInterface, PrintedInterface
{
    use \Robo\Common\ExecOneCommand;

    protected $command;

    public function __construct($pathToBehat = '')
    {
        if ($pathToBehat) {
            $this->command = $pathToBehat . ' run';
        } elseif (file_exists('vendor/bin/behat')) {
            $this->command = 'vendor/bin/behat run';
        } else {
            throw new TaskException(__CLASS__, 'behat not found.');
        }
    }

    /**
     * execute with --tags
     *
     * @param $tags string|array
     * @return $this
     * @throws TaskException
     */
    public function tags($tags)
    {
        if (is_string($tags)) {
            $this->option('tags', $tags);
        } elseif (is_array($tags)) {
            $this->option('tags', implode(',', $tags));
        } else {
            throw new TaskException('Tags need to be string or array.');
        }
        return $this;
    }

    public function getCommand()
    {
        return $this->command . $this->arguments;
    }

    public function run()
    {
        $command = $this->getCommand();
        $this->printTaskInfo('Executing ' . $command);
        return $this->executeCommand($command);
    }

}
