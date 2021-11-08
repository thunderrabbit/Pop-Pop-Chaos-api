<?php

namespace App\Domain\Bubble\Service;

use App\Domain\Bubble\Repository\BubblesGetterRepository;
use App\Factory\LoggerFactory;

/**
 * Service.
 */
final class BubblesGetter
{
    /**
     * @var BubblesGetterRepository
     */
    private $repository;

    /**
     * The constructor.
     *
     * @param BubblesGetterRepository $repository
     *         We have declared the BubblesGetterRepository as a dependency,
     *         because the service can only interact with the database through the repository.
     */
    public function __construct(
      BubblesGetterRepository $repository,
      LoggerFactory $loggerFactory
    )
    {
        $this->repository = $repository;
        $this->logger = $loggerFactory
            ->addFileHandler('bubble_creator.log')
            ->createLogger();
    }

    /**
     * Get bubbles for initial screen display
     *
     * Possible future param int Which level of bubbles to get, but maybe each level should be a separate function
     *
     * @return array List of bubbles
     */
    public function getBubbles(): array
    {
        // Get bubbles
        $array_of_bubbles = $this->repository->getBubbles();

        // Logging here: Bubble created successfully
        $this->logger->info(json_encode($array_of_bubbles));

        return $array_of_bubbles;
    }

}
