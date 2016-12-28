<?php
namespace Leadgen\Customer\SegmentParsing;

/**
 * Base class for each step of the SegmentParsing chain of responsability.
 *
 * @see SegmentParser
 */
abstract class StepBase
{
    /**
     * Next step to be executed
     * @var StepBase
     */
    protected $next;

    /**
     * Process this step. This method will be called by the public 'parser'
     * method.
     *
     * @param  Dto $dto The input data.
     * @return Dto $dto after parsing.
     */
    abstract protected function process(Dto $dto): Dto;

    /**
     * Parses the given data throught the whole chain.
     *
     * @param  Dto $dto The input data.
     * @return Dto $dto after parsing.
     */
    final public function parse(Dto $dto): Dto
    {
        $dto = $this->process($dto);

        if ($this->next) {
            return $this->next->parse($dto);
        }

        return $dto;
    }

    /**
     * Set's the next step of the chain
     *
     * @param StepBase $next Next step that will be executed.
     * @return self
     */
    final public function setNext(StepBase $next): self
    {
        if (! $this->next) {
            $this->next = $next;
        } else {
            $this->next->setNext($next);
        }

        return $this;
    }
}
