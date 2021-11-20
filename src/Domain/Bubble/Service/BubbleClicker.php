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

    private BubbleValidator $bubbleValidator;

    private LoggerInterface $logger;

    /**
     * The constructor.
     *
     * @param BubbleRepository $repository The repository
     * @param BubbleValidator $bubbleValidator The validator
     * @param LoggerFactory $loggerFactory The logger factory
     */
    public function __construct(
        BubbleRepository $repository,
        BubbleValidator $bubbleValidator,
        LoggerFactory $loggerFactory,
        BubbleReader $bubbleReader
    ) {
        $this->repository = $repository;
        $this->bubbleValidator = $bubbleValidator;
        $this->bubbleReader = $bubbleReader;
        $this->logger = $loggerFactory
            ->addFileHandler('bubble_clicker.log')
            ->createLogger();
    }

    /**
     * Update bubble.
     *
     * @param int $bubbleId The bubble id
     *
     * @return BubbleData The bubble data with new radius
     */
    public function clickBubble(int $bubbleId): BubbleData
    {
        // Input validation
        // $this->bubbleValidator->validateBubbleUpdate($bubbleId, $data);

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
