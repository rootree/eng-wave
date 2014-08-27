<?php
/**
 * @author Ivan Chura
 * Created at: 11.08.14 13:28
 */

namespace Application\Service;

class ErrorHandling
{
    protected $logger;

    function __construct($logger)
    {
        $this->logger = $logger;
    }

    function logException(\Exception $e)
    {
        $trace = $e->getTraceAsString();
        $i = 1;
        do {
            $messages[] = $i++ . ": " . $e->getMessage();
        } while ($e = $e->getPrevious());

        $log = "Exception:n" . implode("n", $messages);
        $log .= "nTrace:n" . $trace;

        $this->logger->err($log);
    }
}