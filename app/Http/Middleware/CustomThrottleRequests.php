<?php

namespace App\Http\Middleware;

use Illuminate\Routing\Middleware\ThrottleRequests;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Support\Facades\Log;

class CustomThrottleRequests extends ThrottleRequests
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  int|string  $maxAttempts
     * @param  float|int  $decayMinutes
     * @param  string  $prefix
     * @return mixed
     *
     * @throws \Illuminate\Http\Exceptions\ThrottleRequestsException
     */
    public function handle($request, $next, $maxAttempts = 60, $decayMinutes = 1, $prefix = '')
    {
        Log::info('CustomThrottleRequests middleware called', [
            'maxAttempts' => $maxAttempts,
            'decayMinutes' => $decayMinutes,
            'prefix' => $prefix,
            'path' => $request->path(),
            'method' => $request->method(),
        ]);

        $key = $this->resolveRequestSignature($request);
        
        Log::info('Rate limit key generated', [
            'key' => $key,
            'user_id' => $request->user() ? $request->user()->id : null,
            'ip' => $request->ip(),
        ]);

        return parent::handle($request, $next, $maxAttempts, $decayMinutes, $prefix);
    }

    /**
     * カスタムレート制限メッセージを返す
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $key
     * @param  int  $maxAttempts
     * @param  \Throwable|null  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Illuminate\Http\Exceptions\ThrottleRequestsException
     */
    protected function buildException($request, $key, $maxAttempts, $responseCallback = null, $exception = null)
    {
        Log::warning('Rate limit exceeded', [
            'key' => $key,
            'maxAttempts' => $maxAttempts,
            'user_id' => $request->user() ? $request->user()->id : null,
            'ip' => $request->ip(),
        ]);

        return new ThrottleRequestsException('リクエスト回数制限を超えました。しばらくしてから再度お試しください。', null, $this->getHeaders(
            $maxAttempts,
            $this->calculateRemainingAttempts($key, $maxAttempts)
        ));
    }
}
