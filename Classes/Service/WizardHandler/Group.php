<?php


namespace HDNET\Focuspoint\Service\WizardHandler;


class Group extends AbstractWizardHandler
{

    /**
     * Check if the handler can handle the current request
     *
     * @return true
     */
    public function canHandle()
    {
        return false;
    }

    /**
     * get the arguments for same request call
     *
     * @return array
     */
    public function getArguments()
    {
        return [];
    }

    /**
     * Return the current point
     *
     * @return array
     */
    public function getCurrentPoint()
    {
        return [0, 0];
    }

    /**
     * Get the public URL for the current handler
     *
     * @return string
     */
    public function getPublicUrl()
    {
        return null;
    }

    /**
     * Set the point (between -100 and 100)
     *
     * @param int $x
     * @param int $y
     * @return void
     */
    public function setCurrentPoint($x, $y)
    {
        // TODO: Implement setCurrentPoint() method.
    }
}