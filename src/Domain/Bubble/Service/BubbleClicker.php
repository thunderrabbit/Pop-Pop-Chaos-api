<?php

namespace App\Domain\Bubble\Service;

use App\Domain\Bubble\Data\BubbleData;
use App\Domain\Bubble\Repository\BubbleRepository;
use App\Factory\LoggerFactory;
use Psr\Log\LoggerInterface;

/**
 * Service.
 */
final class BubbleClicker
{
    private BubbleRepository $repository;

    private LoggerInterface $logger;

    /**
     * The constructor.
     *
     * @param BubbleReader $bubbleReader So we can load bubble from DB
     * @param BubbleRepository $repository The repository
     * @param LoggerFactory $loggerFactory The logger factory
     */
    public function __construct(
        BubbleReader $bubbleReader,
        BubbleRepository $repository,
        LoggerFactory $loggerFactory
    ) {
        $this->bubbleReader = $bubbleReader;
        $this->repository = $repository;
        $this->logger = $loggerFactory
            ->addFileHandler('bubble_clicker.log')
            ->createLogger();
    }

    /**
     * Update bubble upon click.  TODO receive and consider info about click
     *
     * @param int $bubbleId The bubble id
     *
     * @return BubbleData The bubble data with new radius
     */
    public function clickBubble(int $bubbleId): BubbleData
    {
        $bubble_from_db = $this->bubbleReader->getBubbleData($bubbleId);
        $bubble_from_db->radius += 1;       // TODO make this increase based on bubble and click details

        // Validation was successfully
        $bubble = new BubbleData((array)$bubble_from_db);
        $bubble->id = $bubbleId;

        // Update the bubble
        $this->repository->updateBubble($bubble);

        // Logging
        $this->logger->info(sprintf('Bubble clicked successfully: %s', $bubbleId));
        return $bubble_from_db;
    }
}
