<?php
namespace Leadgen\Customer\SegmentParsing;

interface StepInterface
{
    /**
     * Parses the data
     *
     * @param  Dto $dto The input data.
     * @return Dto $dto after parsing.
     */
    public function parse(Dto $dto): Dto;

    /**
     * Set's the next step of the chain
     *
     * @param StepInterface $next Next step that will be executed.
     * @return void
     */
    public function setNext(StepInterface $next);
}
