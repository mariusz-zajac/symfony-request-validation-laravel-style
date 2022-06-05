<?php

declare(strict_types=1);

namespace App\Request;

use App\Exception\ValidationException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validator\ValidatorInterface;

abstract class AbstractRequestPayload
{
    protected Request $request;

    protected ValidatorInterface $validator;

    protected array $payload;

    /**
     * Constructor
     *
     * @throws ValidationException
     */
    public function __construct(RequestStack $requestStack, ValidatorInterface $validator)
    {
        if (null === $request = $requestStack->getMainRequest()) {
            throw new \RuntimeException('There is no main request to process');
        }

        $this->request = $request;
        $this->validator = $validator;
        $this->payload = $this->resolvePayload($request);
        $this->validate($this->payload);
    }

    /**
     * Resolve payload from the given request
     */
    protected function resolvePayload(Request $request): array
    {
        if (in_array($request->getMethod(), [Request::METHOD_POST, Request::METHOD_PUT, Request::METHOD_PATCH])) {
            return $request->toArray();
        }

        return $request->query->all();
    }

    /**
     * Validate payload
     *
     * @throws ValidationException
     */
    protected function validate(array $payload): void
    {
        $violations = $this->validator->validate($payload, $this->constraints());

        if ($violations->count() > 0) {
            throw new ValidationException('The given data was invalid', $violations);
        }
    }

    /**
     * Returns true if the parameter is defined, false otherwise
     *
     * @param string $key Parameter name
     */
    public function has(string $key): bool
    {
        return array_key_exists($key, $this->payload);
    }

    /**
     * Returns a parameter by name
     *
     * @param string $key     Parameter name
     * @param mixed  $default The default value if the parameter key does not exist
     */
    public function get(string $key, mixed $default = null): mixed
    {
        return array_key_exists($key, $this->payload) ? $this->payload[$key] : $default;
    }

    /**
     * Returns all parameters
     */
    public function all(): array
    {
        return $this->payload;
    }

    /**
     * Returns main request
     */
    public function getMainRequest(): Request
    {
        return $this->request;
    }

    /**
     * Get the validation constraints
     *
     * @see https://symfony.com/doc/current/validation/raw_values.html
     */
    abstract protected function constraints(): Assert\Collection;
}
