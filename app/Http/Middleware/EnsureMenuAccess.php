<?php

namespace App\Http\Middleware;

use App\Models\UserPermission;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureMenuAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        if (!$user) {
            return $next($request);
        }

        $routeName = $request->route() ? $request->route()->getName() : null;
        $permissionFromRouteName = $this->resolvePermissionKeyFromRouteName($routeName);
        if ($permissionFromRouteName) {
            $allowed = $user->getPermissionsArray();
            if (!in_array($permissionFromRouteName, $allowed, true)) {
                abort(403);
            }

            return $next($request);
        }

        $path = '/' . ltrim($request->path(), '/');
        $path = preg_replace('#/+$#', '', $path);
        if ($path === '') {
            $path = '/';
        }

        if (str_starts_with(ltrim($path, '/'), 'api/')) {
            return $next($request);
        }

        $permissionKey = $this->resolvePermissionKeyForPath($path);
        if (!$permissionKey) {
            return $next($request);
        }

        $allowed = $user->getPermissionsArray();
        if (!in_array($permissionKey, $allowed, true)) {
            abort(403);
        }

        return $next($request);
    }

    private function resolvePermissionKeyFromRouteName(?string $routeName): ?string
    {
        if (!$routeName) {
            return null;
        }

        if ($routeName === 'dashboard') {
            return 'dashboard';
        }

        if (!str_starts_with($routeName, 'vue.')) {
            return null;
        }

        $raw = substr($routeName, 4);
        if ($raw === false || $raw === '') {
            return null;
        }

        $key = strtolower($raw);
        $key = str_replace(['.', '-'], '_', $key);
        $key = preg_replace('/[^a-z0-9_]+/', '', $key);
        $key = preg_replace('/_+/', '_', $key);
        $key = trim($key, '_');

        if ($key === '') {
            return null;
        }

        $knownKeys = [];
        foreach (UserPermission::getAllMenuItems() as $item) {
            if (!empty($item['key'])) {
                $knownKeys[$item['key']] = true;
            }
        }

        return isset($knownKeys[$key]) ? $key : null;
    }

    private function resolvePermissionKeyForPath(string $path): ?string
    {
        if (str_starts_with($path, '/user-permissions/')) {
            return 'define_user_access_permission';
        }

        if ($path === '/system-security/update-password') {
            return 'amend_user_password';
        }

        $sidebarMap = $this->getSidebarUriToPermissionKeyMap();

        $bestKey = null;
        $bestLen = -1;

        foreach ($sidebarMap as $uri => $permissionKey) {
            if ($uri === '/') {
                continue;
            }

            if ($path === $uri || str_starts_with($path, $uri . '/')) {
                $len = strlen($uri);
                if ($len > $bestLen) {
                    $bestLen = $len;
                    $bestKey = $permissionKey;
                }
            }
        }

        if ($path === '/dashboard') {
            return 'dashboard';
        }

        return $bestKey;
    }

    private function getSidebarUriToPermissionKeyMap(): array
    {
        static $cached = null;
        if (is_array($cached)) {
            return $cached;
        }

        $cached = [];

        $sidebarPath = resource_path('js/Layouts/Partials/Sidebar.vue');
        if (!is_readable($sidebarPath)) {
            return $cached;
        }

        $normalizeUri = function (string $uri): string {
            $uri = trim($uri);
            $uri = preg_replace('/[?#].*$/', '', $uri);
            $uri = '/' . ltrim((string) $uri, '/');
            $uri = preg_replace('#/+$#', '', $uri);
            return $uri === '' ? '/' : $uri;
        };

        $titleToFallbackKey = function (string $title): string {
            $key = strtolower(trim($title));
            $key = preg_replace('/[\s\/&\\-]+/', '_', $key);
            $key = preg_replace('/[^a-z0-9_]+/', '', $key);
            $key = preg_replace('/_+/', '_', $key);
            return trim($key, '_');
        };

        $menuNameToKey = [];
        foreach (UserPermission::getAllMenuItems() as $item) {
            if (!empty($item['name']) && !empty($item['key']) && !isset($menuNameToKey[$item['name']])) {
                $menuNameToKey[$item['name']] = $item['key'];
            }
        }

        $sidebar = file_get_contents($sidebarPath);
        if (!is_string($sidebar) || $sidebar === '') {
            return $cached;
        }

        preg_match_all(
            "/\\{[^\\{\\}]*?title:\\s*['\"]([^'\"]+)['\"][^\\{\\}]*?route:\\s*['\"]([^'\"]+)['\"][^\\{\\}]*?\\}/s",
            $sidebar,
            $matches,
            PREG_SET_ORDER
        );

        foreach ($matches as $m) {
            $title = trim($m[1] ?? '');
            $uri = $normalizeUri($m[2] ?? '');
            if ($title === '' || $uri === '/') {
                continue;
            }

            $permissionKey = $menuNameToKey[$title] ?? $titleToFallbackKey($title);
            if ($permissionKey !== '') {
                $cached[$uri] = $permissionKey;
            }
        }

        $cached['/dashboard'] = $cached['/dashboard'] ?? 'dashboard';

        return $cached;
    }
}
