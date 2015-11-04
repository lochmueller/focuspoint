<?php

/**
 * Abstract wizard handler
 */

namespace HDNET\Focuspoint\Service\WizardHandler;

/**
 * Abstract wizard handler
 */
abstract class AbstractWizardHandler
{

    /**
     * Check if the handler can handle the current request
     *
     * @return true
     */
    abstract public function canHandle();

    /**
     * get the arguments for same request call
     *
     * @return array
     */
    abstract public function getArguments();

    /**
     * Return the current point (between -100 and 100)
     *
     * @return array
     */
    abstract public function getCurrentPoint();

    /**
     * Set the point (between -100 and 100)
     *
     * @param int $x
     * @param int $y
     * @return void
     */
    abstract public function setCurrentPoint($x, $y);

    /**
     * Get the public URL for the current handler
     *
     * @return string
     */
    abstract public function getPublicUrl();

}