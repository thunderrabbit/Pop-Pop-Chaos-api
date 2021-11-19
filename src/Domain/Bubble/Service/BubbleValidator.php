<?php

namespace App\Domain\Bubble\Service;

use App\Domain\Bubble\Repository\BubbleRepository;
use App\Factory\ValidationFactory;
use Cake\Validation\Validator;
use Selective\Validation\Exception\ValidationException;

/**
 * Service.
 */
final class BubbleValidator
{
    private BubbleRepository $repository;

    private ValidationFactory $validationFactory;

    /**
     * The constructor.
     *
     * @param BubbleRepository $repository The repository
     * @param ValidationFactory $validationFactory The validation
     */
    public function __construct(BubbleRepository $repository, ValidationFactory $validationFactory)
    {
        $this->repository = $repository;
        $this->validationFactory = $validationFactory;
    }

    /**
     * Validate update.
     *
     * @param int $bubbleId The bubble id
     * @param array $data The data
     *
     * @return void
     */
    public function validateBubbleUpdate(int $bubbleId, array $data): void
    {
        if (!$this->repository->existsBubbleId($bubbleId)) {
            throw new ValidationException(sprintf('Bubble not found: %s', $bubbleId));
        }

        $this->validateBubble($data);
    }

    /**
     * Validate new bubble.
     *
     * @param array $data The data
     *
     * @throws ValidationException
     *
     * @return void
     */
    public function validateBubble(array $data): void
    {
        $validator = $this->createValidator();

        $validationResult = $this->validationFactory->createValidationResult(
            $validator->validate($data)
        );

        if ($validationResult->fails()) {
            throw new ValidationException('Please check your input', $validationResult);
        }
    }

    /**
     * Create validator.
     *
     * @return Validator The validator
     */
    private function createValidator(): Validator
    {
        $validator = $this->validationFactory->createValidator();

        return $validator
            ->maxLength('bubble_id', 8, 'Too long');
    }
}
