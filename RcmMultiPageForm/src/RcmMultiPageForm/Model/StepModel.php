<?php
/**
 * Manages steps (pages) in a multi-page from.
 */

namespace RcmMuliPageForm\Model;


class StepModel
{
    protected $steps = array();
    protected $step;
    protected $urlName = 'step';

    public function addStep($stepName, $previousStep = null)
    {
        $this->steps[$stepName] = $previousStep;
    }

    public function getFirstStep()
    {
        foreach ($this->steps as $stepName => $previousStep) {
            if ($previousStep == null) {
                return $stepName;
            }
        }
        throw new \Exception('No first step found');
    }

    /**
     * @param mixed $step
     */
    public function setCurrentStep($step)
    {
        $this->step = $step;
    }

    /**
     * @return mixed
     */
    public function getCurrentStep()
    {
        if (empty($this->step)) {
            if (
                //Using a white list to filter $_GET
                isset($_GET[$this->urlName])
                && array_key_exists($this->urlName, $this->steps)
            ) {
                $this->step = $this->urlName;
            } else {
                $this->step = $this->getFirstStep();
            }
        }
        return $this->step;
    }

    public function getPreviousStep()
    {
        return $this->steps[$this->step];
    }

    public function getPreviousStepUrlParam()
    {
        return $this->urlName . '=' . $this->step;
    }
} 