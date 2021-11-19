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
        LoggerFactory $loggerFactory
    ) {
        $this->repository = $repository;
        $this->bubbleValidator = $bubbleValidator;
        $this->logger = $loggerFactory
            ->addFileHandler('bubble_clicker.log')
            ->createLogger();
    }

    /**
     * Update bubble.
     *
     * @param int $bubbleId The bubble id
     * @param array $data The request data
     *
     * @return void
     */
    public function clickBubble(int $bubbleId, array $data): void
    {
        // Input validation
        $this->bubbleValidator->validateBubbleUpdate($bubbleId, $data);

        // Validation was successfully
        $bubble = new BubbleData($data);
        $bubble->id = $bubbleId;

        // Update the bubble
        $this->repository->updateBubble($bubble);

        // Logging
        $this->logger->info(sprintf('Bubble clicked successfully: %s', $bubbleId));
    }
}
