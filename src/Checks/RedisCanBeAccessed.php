<?php

namespace BeyondCode\SelfDiagnosis\Checks;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Redis;

class RedisCanBeAccessed implements Check
{
    private $message;

    /**
     * The name of the check.
     *
     * @param array $config
     * @return string
     */
    public function name(array $config): string
    {
        return trans('self-diagnosis::checks.redis_can_be_accessed.name');
    }

    /**
     * Perform the actual verification of this check.
     *
     * @param array $config
     * @return bool
     */
    public function check(array $config): bool
    {
        try {
            if (Arr::get($config, 'default_connection', true)) {
                if (!$this->testConnection()) {
                    $this->message = trans('self-diagnosis::checks.redis_can_be_accessed.message.default_cache');
                    return false;
                }
            }

            foreach (Arr::get($config, 'connections', []) as $connection) {
                if (!$this->testConnection($connection)) {
                    $this->message = trans('self-diagnosis::checks.redis_can_be_accessed.message.named_cache', [
                        'name' => $connection,
                    ]);
                    return false;
                }
            }
        } catch (\Exception $e) {
            $this->message = $e->getMessage();
            return false;
        }

        return true;
    }

    /**
     * The error message to display in case the check does not pass.
     *
     * @param array $config
     * @return string
     */
    public function message(array $config): string
    {
        return trans('self-diagnosis::checks.redis_can_be_accessed.message.not_accessible', [
            'error' => $this->message,
        ]);
    }

    /**
     * Tests a redis connection and returns whether the connection is opened or not.
     *
     * @param string|null $name
     * @return bool
     */
    private function testConnection(string $name = null): bool
    {
        $connection = Redis::connection($name);
        $connection->connect();
        return $connection->isConnected();
    }
}
