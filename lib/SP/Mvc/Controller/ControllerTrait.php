<?php
/**
 * sysPass
 *
 * @author    nuxsmin
 * @link      https://syspass.org
 * @copyright 2012-2018, Rubén Domínguez nuxsmin@$syspass.org
 *
 * This file is part of sysPass.
 *
 * sysPass is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * sysPass is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 *  along with sysPass.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace SP\Mvc\Controller;

use SP\Core\Context\ContextInterface;
use SP\Core\Exceptions\SPException;
use SP\Http\JsonResponse;
use SP\Http\Request;
use SP\Http\Uri;
use SP\Util\Json;
use SP\Util\Util;


/**
 * Trait ControllerTrait
 *
 * @package SP\Mvc\Controller
 */
trait ControllerTrait
{
    /**
     * @return string
     */
    protected function getControllerName()
    {
        $class = static::class;

        return substr($class, strrpos($class, '\\') + 1, -strlen('Controller')) ?: '';
    }

    /**
     * Comprobar si la sesión está activa
     *
     * @param ContextInterface $context
     * @param Request          $request
     * @param \Closure         $onRedirect
     */
    protected function checkLoggedInSession(ContextInterface $context, Request $request, \Closure $onRedirect)
    {
        if (!$context->isLoggedIn()) {
            if ($request->isJson()) {
                $JsonResponse = new JsonResponse();
                $JsonResponse->setDescription(__u('La sesión no se ha iniciado o ha caducado'));
                $JsonResponse->setStatus(10);

                Json::returnJson($JsonResponse);
            } elseif ($request->isAjax()) {
                Util::logout();
            } else {
                try {
                    $route = $request->analyzeString('r');
                    $hash = $request->analyzeString('h');

                    $uri = new Uri('index.php');
                    $uri->addParam('_r', 'login');

                    if ($route && $hash) {
                        $key = $context->getConfig()->getPasswordSalt();
                        $request->verifySignature($key);

                        $uri->addParam('from', $route);

                        $onRedirect->call($this, $uri->getUriSigned($key));
                    } else {
                        $onRedirect->call($this, $uri->getUri());
                    }
                } catch (SPException $e) {
                    processException($e);
                }
            }
        }
    }

    /**
     * @param ContextInterface $context
     * @param Request          $request
     */
    protected function checkSecurityToken(ContextInterface $context, Request $request)
    {
        $sk = $request->analyzeString('sk');
        $sessionKey = $context->getSecurityKey();

        if (!$sk || (null !== $sessionKey && $sessionKey !== $sk)) {
            $this->invalidAction();
        }
    }

    /**
     * Acción no disponible
     */
    protected function invalidAction()
    {
        Json::returnJson((new JsonResponse())->setDescription(__u('Acción Inválida')));
    }
}