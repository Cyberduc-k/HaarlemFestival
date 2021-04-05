<?php
//Model for API keys
class ApiKey
{
    private const KEY_LENGTH = 32;

    private string $email;
    private string $apiKey;

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getApiKey(): string
    {
        return $this->apiKey;
    }

    /**
     * @param string $apiKey
     */
    public function setApiKey(string $apiKey): void
    {
        $this->apiKey = $apiKey;
    }

    /**
     * @return bool
     */
    public function generate(): bool
    {
        try{
            $this->setApiKey(bin2hex(random_bytes(self::KEY_LENGTH)));
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

}