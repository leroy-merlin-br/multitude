<?php
namespace Leadgen\Customer\SegmentParsing;

use Leadgen\Segment\Segment;

/**
 * A service that, by receiving a segment, will try to find the Customers that
 * match it and add a reference of the segment into then. It works by parsing
 * the input throught a chain of responsability.
 *
 * The SegmentParser is the entry point of this namespace.
 *
 * @see StepBase
 */
class SegmentParser
{
    /**
     * Chain of StepBase objects that will be executed when parsing a
     * segment
     * @var StepBase
     */
    protected $pipeline;

    /**
     * Injects dependencies
     *
     * @param StepEsQuery         $stepEsQuery         Step of the segment parsing.
     * @param StepCustomerIds     $stepCustomerIds     Step of the segment parsing.
     * @param StepUpdateCustomers $stepUpdateCustomers Step of the segment parsing.
     */
    public function __construct(
        StepEsQuery $stepEsQuery,
        StepCustomerIds $stepCustomerIds,
        StepUpdateCustomers $stepUpdateCustomers
    ) {
        $this->pipeline = $stepEsQuery
            ->setNext($stepCustomerIds)
            ->setNext($stepUpdateCustomers);
    }

    /**
     * Find out which users matchs the given segment.
     *
     * @param Segment $segment The segment that is being parsed.
     *
     * @return int Number of affected documents.
     */
    public function parse(Segment $segment): int
    {
        $dto = new Dto;
        $dto->segment = $segment;

        $dto = $this->pipeline->parse($dto);

        return $dto->affectedCount ?? 0;
    }
}
