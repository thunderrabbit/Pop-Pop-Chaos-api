<?php

namespace App\Domain\Bubble\Service;

use App\Domain\Bubble\Data\BubbleData;
use App\Domain\Bubble\Repository\BubbleRepository;

/**
 * Service.
 */
final class BubbleReader
{
    private BubbleRepository $repository;

    /**
     * The constructor.
     *
     * @param BubbleRepository $repository The repository
     */
    public function __construct(BubbleRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Read a bubble.
     *
     * @param int $bubbleId The bubble id
     *
     * @return BubbleData The bubble data
     */
    public function getBubbleData(int $bubbleId): BubbleData
    {
        // Input validation
        // ...

        // Fetch data from the database
        $bubble = $this->repository->getBubbleById($bubbleId);

        // Optional: Add or invoke your complex business logic here
        // ...

        // Optional: Map result
        // ...

        return $bubble;
    }
}
