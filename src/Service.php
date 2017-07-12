<?php

namespace Quhang\LaravelEasemob;

use Closure;
use Exception;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Exception\RequestException;
use Quhang\LaravelEasemob\Exception\LaravelEasemobException;

class Service
{
    protected $httpClient;
    protected $config;

    public function __construct(HttpClient $client)
    {
        $this->httpClient = $client;
        $this->config = config('easemob');
    }

    public function setHost($host)
    {
        $this->config['host'] = $host;
        return $this;
    }

    public function setOrgName($orgName)
    {
        $this->config['org_name'] = $orgName;
        return $this;
    }

    public function setAppName($appName)
    {
        $this->config['app_name'] = $appName;
        return $this;
    }

    public function setClientId($clientId)
    {
        $this->config['client_id'] = $clientId;
        return $this;
    }

    public function setClientSecret($secret)
    {
        $this->config['client_secret'] = $secret;
        return $this;
    }

    public function setFilePath($path)
    {
        $this->config['download_file_path'] = $path;
        return $this;
    }

    public function getConfig()
    {
        return $this->config;
    }

    public function setConfig(array $config)
    {
        $this->config = $config;
        return $this;
    }

    public function getBaseUri()
    {
        if (!Arr::get($this->config, 'host')) {
            throw new LaravelEasemobException('请设置 host');
        }
        if (!Arr::get($this->config, 'org_name')) {
            throw new LaravelEasemobException('请设置 org_name');
        }
        if (!Arr::get($this->config, 'app_name')) {
            throw new LaravelEasemobException('请设置 app_name');
        }
        return trim($this->config['host'], '/')."/{$this->config['org_name']}/{$this->config['app_name']}";
    }

    public function buildUrl($resource)
    {
        return $this->getBaseUri().'/'.trim($resource, '/');
    }

    public function getHttpClient()
    {
        return $this->httpClient;
    }

    /**
     * 获取并缓存token
     * @return string
     */
    protected function getToken()
    {
        $cacheKey = md5(json_encode(Arr::only($this->config, ['client_id', 'client_secret', 'org_name', 'app_name'])));
        if ($token = Cache::get('laravel-easemob-token:'.$cacheKey)) {
            return $token;
        }

        $body = json_encode([
            "grant_type" => "client_credentials",
            "client_id" => Arr::get($this->config, 'client_id'),
            "client_secret" => Arr::get($this->config, ('client_secret'))
        ]);

        $result = $this->request('post', '/token', ['body' => $body], false);
        if ($result['exception']) {
            throw new Exception\TokenException($result['message']);
        }

        $token = Arr::get($result, 'data.access_token');
        $expireMinutes = floor(Arr::get($result, 'data.expires_in', 0) / 60);
        Cache::put('laravel-easemob-token:'.$cacheKey, $token, $expireMinutes);
        return $token;
    }

    public function request($method, $url, $options = [], $authToken = true)
    {
        try {
            if ($authToken) {
                Arr::set($options, 'headers.Authorization', 'Bearer '.$this->getToken());
            }
            $response = $this->httpClient->request($method, $this->buildUrl($url), $options);
        } catch (RequestException $e) {
            $response = $e->getResponse();
            if (!$response) {
                return $this->exceptionHandle($e);
            }
            return $this->exceptionResponseHandle($response);
        }

        return $this->responseHandle($response);
    }

    public function file($method, $url, $options = [], $authToken = true)
    {
        try {
            $savePath = Arr::get($this->config, 'download_file_path');
            if (!$savePath) {
                throw new LaravelEasemobException('请设置 download_file_path');
            }
            if (!file_exists($savePath)) {
                throw new LaravelEasemobException("{$savePath} 不存在");
            }
            if ($authToken) {
                Arr::set($options, 'headers.Authorization', 'Bearer '.$this->getToken());
            }
            $response = $this->httpClient->request($method, $this->buildUrl($url), $options);

            $contentDisposition = $response->getHeader('Content-Disposition');
            if (is_array($contentDisposition)) {
                $contentDisposition = implode(' ', $contentDisposition);
            }

            preg_match('/filename="(.+)";/', $contentDisposition, $matches);
            $filename = rtrim($savePath, '/').'/'.Carbon::now()->format('YmdHis');

            if (count($matches) > 1) {
                $filename .= $matches[1];
            }

            file_put_contents($filename, $response->getBody());
        } catch (RequestException $e) {
            $response = $e->getResponse();
            if (!$response) {
                return $this->exceptionHandle($e);
            }
            return $this->exceptionResponseHandle($response);
        }

        $result = $this->responseHandle($response);
        $result['data'] = ['filename' => $filename];
        return $result;
    }

    protected function responseHandle(ResponseInterface $response)
    {
        return [
            'code' => $response->getStatusCode(),
            'exception' => false,
            'message' => '',
            'data' => json_decode($response->getBody(), true)
        ];
    }

    protected function exceptionResponseHandle(ResponseInterface $response)
    {
        $result = json_decode($response->getBody(), true);
        return [
            'code' => $response->getStatusCode(),
            'exception' => true,
            'message' => Arr::get($result, 'error', ''),
            'data' => $result
        ];
    }

    protected function exceptionHandle(Exception $e)
    {
        return [
            'code' => -1,
            'exception' => true,
            'message' => $e->getMessage(),
            'data' => []
        ];
    }
}
