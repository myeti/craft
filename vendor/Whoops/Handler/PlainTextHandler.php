<?php
/**
* Whoops - php errors for cool kids
* @author Filipe Dobreira <http://github.com/filp>
* Plaintext handler for command line and logs.
* @author Pierre-Yves Landuré <https://howto.biapy.com/>
*/

namespace Whoops\Handler;
use Whoops\Handler\Handler;
use InvalidArgumentException;
use Whoops\Exception\Frame;
use Psr\Log\LoggerInterface;

/**
* Handler outputing plaintext error messages. Can be used
* directly, or will be instantiated automagically by Whoops\Run
* if passed to Run::pushHandler
*/
class PlainTextHandler extends Handler
{
    const VAR_DUMP_PREFIX = '   | ';

    /**
     * @var Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * @var bool
     */
    private $addDebugToOutput = true;

    /**
     * @var bool|integer
     */
    private $addDebugFunctionArgsToOutput = false;

    /**
     * @var integer
     */
    private $DebugFunctionArgsOutputLimit = 1024;

    /**
     * @var bool
     */
    private $onlyForCommandLine = false;

    /**
     * @var bool
     */
    private $outputOnlyIfCommandLine = true;

    /**
     * @var bool
     */
    private $loggerOnly = false;

    /**
     * Constructor.
     * @throws InvalidArgumentException If argument is not null or a LoggerInterface
     * @param Psr\Log\LoggerInterface|null $logger
     */
    public function __construct($logger = null)
    {
        $this->setLogger($logger);
    }

    /**
     * Set the output logger interface.
     * @throws InvalidArgumentException If argument is not null or a LoggerInterface
     * @param Psr\Log\LoggerInterface|null $logger
     */
    public function setLogger($logger = null)
    {
        if(! (is_null($logger)
            || $logger InstanceOf LoggerInterface)) {
            throw new InvalidArgumentException(
                'Argument to ' . __METHOD__ .
                " must be a valid Logger Interface (aka. Monolog), " .
                get_class($logger) . ' given.'
            );
        }

        $this->logger = $logger;
    }

    /**
     * @return Psr\Log\LoggerInterface|null
     */
    public function getLogger()
    {
        return $this->logger;
    }

    /**
     * Add error Debug to output.
     * @param bool|null $addDebugToOutput
     * @return bool|$this
     */
    public function addDebugToOutput($addDebugToOutput = null)
    {
        if(func_num_args() == 0) {
            return $this->addDebugToOutput;
        }

        $this->addDebugToOutput = (bool) $addDebugToOutput;
        return $this;
    }

    /**
     * Add error Debug function arguments to output.
     * Set to True for all frame args, or integer for the n first frame args.
     * @param bool|integer|null $addDebugFunctionArgsToOutput
     * @return null|bool|integer
     */
    public function addDebugFunctionArgsToOutput($addDebugFunctionArgsToOutput = null)
    {
        if(func_num_args() == 0) {
            return $this->addDebugFunctionArgsToOutput;
        }

        if(! is_integer($addDebugFunctionArgsToOutput)) {
            $this->addDebugFunctionArgsToOutput = (bool) $addDebugFunctionArgsToOutput;
        }
        else {
            $this->addDebugFunctionArgsToOutput = $addDebugFunctionArgsToOutput;
        }
    }

    /**
     * Set the size limit in bytes of frame arguments var_dump output.
     * If the limit is reached, the var_dump output is discarded.
     * Prevent memory limit errors.
     * @var integer
     */
    public function setDebugFunctionArgsOutputLimit($DebugFunctionArgsOutputLimit)
    {
        $this->DebugFunctionArgsOutputLimit = (integer) $DebugFunctionArgsOutputLimit;
    }

    /**
     * Get the size limit in bytes of frame arguments var_dump output.
     * If the limit is reached, the var_dump output is discarded.
     * Prevent memory limit errors.
     * @return integer
     */
    public function getDebugFunctionArgsOutputLimit()
    {
        return $this->DebugFunctionArgsOutputLimit;
    }

    /**
     * Restrict error handling to command line calls.
     * @param bool|null $onlyForCommandLine
     * @return null|bool
     */
    public function onlyForCommandLine($onlyForCommandLine = null)
    {
        if(func_num_args() == 0) {
            return $this->onlyForCommandLine;
        }
        $this->onlyForCommandLine = (bool) $onlyForCommandLine;
    }

    /**
     * Output the error message only if using command line.
     * else, output to logger if available.
     * Allow to safely add this handler to web pages.
     * @param bool|null $outputOnlyIfCommandLine
     * @return null|bool
     */
    public function outputOnlyIfCommandLine($outputOnlyIfCommandLine = null)
    {
        if(func_num_args() == 0) {
            return $this->outputOnlyIfCommandLine;
        }
        $this->outputOnlyIfCommandLine = (bool) $outputOnlyIfCommandLine;
    }

    /**
     * Only output to logger.
     * @param bool|null $loggerOnly
     * @return null|bool
     */
    public function loggerOnly($loggerOnly = null)
    {
        if(func_num_args() == 0) {
            return $this->loggerOnly;
        }

        $this->loggerOnly = (bool) $loggerOnly;
    }

    /**
     * Check, if possible, that this execution was triggered by a command line.
     * @return bool
     */
    private function isCommandLine()
    {
        return PHP_SAPI == 'cli';
    }

    /**
     * Test if handler can process the exception..
     * @return bool
     */
    private function canProcess()
    {
        return $this->isCommandLine() || !$this->onlyForCommandLine();
    }

    /**
     * Test if handler can output to stdout.
     * @return bool
     */
    private function canOutput()
    {
        return ($this->isCommandLine() || ! $this->outputOnlyIfCommandLine())
            && ! $this->loggerOnly();
    }

    /**
     * Get the frame args var_dump.
     * @param  \Whoops\Exception\Frame $frame [description]
     * @param  integer $line  [description]
     * @return string
     */
    private function getFrameArgsOutput(Frame $frame, $line)
    {
        if($this->addDebugFunctionArgsToOutput() === false
            || $this->addDebugFunctionArgsToOutput() < $line) {
            return '';
        }

        // Dump the arguments:
        ob_start();
        var_dump($frame->getArgs());
        if(ob_get_length() > $this->getDebugFunctionArgsOutputLimit()) {
            // The argument var_dump is to big.
            // Discarded to limit memory usage.
            ob_clean();
            return sprintf(
                "\n%sArguments dump length greater than %d Bytes. Discarded.",
                self::VAR_DUMP_PREFIX,
                $this->getDebugFunctionArgsOutputLimit()
            );
        }

        return sprintf("\n%s",
            preg_replace('/^/m', self::VAR_DUMP_PREFIX, ob_get_clean())
        );
    }

    /**
     * Get the exception Debug as plain text.
     * @return string
     */
    private function getDebugOutput()
    {
        if(! $this->addDebugToOutput()) {
            return '';
        }
        $inspector = $this->getInspector();
        $frames = $inspector->getFrames();

        $response = "\nStack Debug:";

        $line = 1;
        foreach($frames as $frame) {
            /** @var Frame $frame */
            $class = $frame->getClass();

            $template = "\n%3d. %s->%s() %s:%d%s";
            if(! $class) {
                // Remove method arrow (->) from output.
                $template = "\n%3d. %s%s() %s:%d%s";
            }

            $response .= sprintf(
                $template,
                $line,
                $class,
                $frame->getFunction(),
                $frame->getFile(),
                $frame->getLine(),
                $this->getFrameArgsOutput($frame, $line)
            );

            $line++;
        }

        return $response;
    }

    /**
     * @return int
     */
    public function handle()
    {
        if(! $this->canProcess()) {
            return Handler::DONE;
        }

        $exception = $this->getException();

        $response = sprintf("%s: %s in file %s on line %d%s\n",
                get_class($exception),
                $exception->getMessage(),
                $exception->getFile(),
                $exception->getLine(),
                $this->getDebugOutput()
            );

        if($this->getLogger()) {
            $this->getLogger()->error($response);
        }

        if(! $this->canOutput()) {
            return Handler::DONE;
        }

        if(class_exists('\Whoops\Util\Misc')
            && \Whoops\Util\Misc::canSendHeaders()) {
            header('Content-Type: text/plain');
        }

        echo $response;

        return Handler::QUIT;
    }
}
