<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Leadgen\Authorization\Repository;

/**
 * Responsible for authenticating a consumer against our API.
 * To authenticate, the consumer must have a valid token
 * and the requested URL must be in the allowed routes.
 * Even though the middleware uses basic access authentication
 * to know if the request should be authorized, it does accept
 * the query parameter `_authorization` in order to be compatible
 * with legacy systems.
 */
class AuthToken
{
    /**
     * Authorization Token repository.
     *
     * @var Repository
     */
    protected $repo;

    /**
     * Injects dependencies
     * @param Repository $repo AuthToken repository.
     */
    public function __construct(Repository $repo)
    {
        $this->repo = $repo;
    }

    /**
     * Run the request middleware
     *
     * @param Request $request Incoming request.
     * @param Closure $next    Next middleware.
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (!$this->isEnabled()) {
            return $next($request);
        }

        if ($this->isWhitelistedRoute($request->route()[1]['as'] ?? '')) {
            return $next($request);
        }

        if (!$authorization = $this->extractAuthFromRequest($request)) {
            return $this->unauthorized('Authentication required');
        }

        if (!$this->authenticate($authorization)) {
            return $this->unauthorized('Invalid credentials '.$authorization);
        }

        // application does not need to know about tokens
        $request->headers->remove('authorization');

        return $next($request);
    }

    /**
     * Check if AuthToken is enabled.
     *
     * @see /config/multitue.php
     *
     * @return boolean
     */
    protected function isEnabled()
    {
        return config('multitude.auth_token');
    }

    /**
     * Check if the given route is whitelisted
     *
     * @param  string $routeName Name of route.
     *
     * @return boolean
     */
    protected function isWhitelistedRoute(string $routeName)
    {
        return in_array(
            $routeName,
            ['root', 'interaction.store', 'interaction.pulse']
        );
    }

    /**
     * Retrieves the 'authorization' header or '_authorization'
     * parameter from the $request. it does accept the query
     * parameter `_authorization` in order to be widely compatible.
     *
     * @param Request $request Request to extract authentication from.
     *
     * @return string|null E.g.: "Basic d2h5IGFyZSB5b3UgZGVjcmlwdGluZyB0aGlzPyA7UA==".
     */
    protected function extractAuthFromRequest(Request $request)
    {
        return $request->header('authorization') ?: $request->input('_authorization');
    }

    /**
     * In order to authenticate to the API, the type must be 'Basic',
     * the token and requested route must match the one set on config.
     * E.g.: `Basic d2h5IGFyZSB5b3UgZGVjcmlwdGluZyB0aGlzPyA7UA==`.
     *
     * @param string  $authorization
     *
     * @return bool
     */
    protected function authenticate(string $authorization): bool
    {
        $parts = explode(' ', $authorization);
        $type = strtolower($parts[0]);
        $token = $parts[1] ?? null;

        return 2 === count($parts)
            && 'basic' === $type
            && $this->validateAccess($token);
    }

    /**
     * Validates the token using http basic pattern: `<username>:<password>`,
     * encoded with base64 and where <username> represents the token
     * and <password> must be blank. (colon is required).
     *
     * If the token is valid, we check the request against the
     * allowed routes defined on the API config.
     *
     * @param string $token Token to be validate.
     *
     * @return boolean
     */
    protected function validateAccess(string $token): bool
    {

        return null !== $this->repo->findBySecret($this->decodeToken($token));
    }

    /**
     * Decodes the given token applying base64_decode and removing the last
     * character (:).
     *
     * @param string $token
     *
     * @return string
     */
    protected function decodeToken(string $token): string
    {
        return substr(base64_decode($token), 0, -1);
    }

    /**
     * Respond with an authorization error.
     *
     * @param string $message
     *
     * @return JsonResponse
     */
    protected function unauthorized(string $message): JsonResponse
    {
        return response()->json(compact('message'), 401);
    }
}
