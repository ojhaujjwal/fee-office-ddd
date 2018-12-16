<?php

declare(strict_types=1);

namespace App\Core\Ports\Exception\Api;

trait ApiExceptionTrait
{
    /**
     * @var string
     */
    private $title;

    /**
     * @var int
     */
    private $status;

    /**
     * @var string
     */
    private $detail;

    /**
     * @var string
     */
    private $type = '';

    /**
     * @var int
     */
    private $errorCode;

    /**
     * @var array
     */
    private $meta = [];

    public function getStatus(): int
    {
        return $this->status;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDetail(): string
    {
        return $this->detail;
    }

    public function getErrorCode(): int
    {
        return $this->errorCode;
    }

    public function getMeta(): array
    {
        return $this->meta;
    }

    public function getAdditionalData(): array
    {
        $data = [
            'code' => $this->getErrorCode(),
        ];

        if (!empty($this->meta)) {
            $data['meta'] = $this->meta;
        }

        return $data;
    }

    /**
     * Serialize the exception to an array of problem details.
     */
    public function toArray(): array
    {
        return array_merge([
            'status' => $this->status,
            'detail' => $this->detail,
            'title' => $this->title,
            'type' => $this->type,
        ], $this->getAdditionalData());
    }

    /**
     * Allow serialization via json_encode().
     *
     * @return array
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
