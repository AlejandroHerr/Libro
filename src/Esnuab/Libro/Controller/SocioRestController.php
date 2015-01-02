<?php
namespace Esnuab\Libro\Controller;

use AlejandroHerr\Controller\CrudControllerInterface;
use AlejandroHerr\Controller\RestControllerInterface;
use AlejandroHerr\Exception\Arr\HttpException;
use AlejandroHerr\Exception\Model\DuplicateException;
use AlejandroHerr\Exception\Model\ValidationException;
use Esnuab\Libro\Model\Exception\SocioNotFoundException;
use Propel\Runtime\Map\TableMap;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class SocioRestController extends Core\SocioController implements CrudControllerInterface, RestControllerInterface
{
    /**
     * Creates new item
     *
     * @param Appicaltion $app     Application instance
     * @param Request     $request Request instance
     *
     * @return Response Response instance
     */
    public function createAction(Application $app, Request $request)
    {
        try {
            $socio = $this->create($request->request->all());
        } catch (ValidationException $e) {
            throw new HttpException(422, 'Validation Exception', $e);
        } catch (DuplicateException $e) {
            throw new HttpException(409, 'Duplication Exception', $e);
        }

        return new JsonResponse($socio->toArray(), 201);
    }

    /**
     * Deletes an item
     *
     * @param Application $app Applications instance
     * @param integer     $id  Item's id
     *
     * @return Response Response instance
     */
    public function deleteAction(Application $app, $id)
    {
        try {
            $this->delete($id);
        } catch (SocioNotFoundException $e) {
            throw new HttpException(404, 'Resource not Found', $e);
        }

        return new JsonResponse('', 204);
    }

    /**
     * Querys a collection of items
     *
     * @param Application $app   Application instance
     * @param integer     $page  Page
     * @param integer     $items Items per page
     * @param string      $order Sorting order
     *
     * @return Response Response instance
     */
    public function queryAction(Application $app, $page = 1, $maxPerPage = 50)
    {
        $sociosPager = $this->query($page, $maxPerPage);

        $response = [
            'socios' => $sociosPager->toArray(null, null, TableMap::TYPE_CAMELNAME),
            'pagination' => [
                'page' => $sociosPager->getPage(),
                'lastPage' => $sociosPager->getLastPage(),
                'maxPerPage' => $sociosPager->getMaxPerPage(),
                'firstIndex' => $sociosPager->getFirstIndex(),
                'lastIndex' => $sociosPager->getLastIndex(),
            ],
        ];

        return new JsonResponse($response);
    }

    /**
     * Returns item by id
     *
     * @param Appicaltion $app Application instance
     * @param integer     $id  Id of the item
     *
     * @return Response Response instance
     */
    public function readAction(Application $app, $id, $version = null)
    {
        try {
            $socio = $this->readVersion($id, $version);
        } catch (SocioNotFoundException $e) {
            throw new HttpException(404, 'Resource not Found', $e);
        }

        return new JsonResponse($socio);
    }
    /**
     * Updates existing element
     *
     * @param Application $app     Application instance
     * @param Request     $request Request instance
     * @param integer     $id      Item's id
     *
     * @return Response Response instance
     */
    public function updateAction(Application $app, Request $request, $id)
    {
        try {
            $socio = $this->read($id);
        } catch (SocioNotFoundException $e) {
            throw new HttpException(404, 'Resource not Found', $e);
        }
        try {
            $socio = $socio->fromArray($request->request->all());
            $socio = $this->update($id, $request->request->all());
        } catch (ValidationException $e) {
            throw new HttpException(422, 'Validation Exception', $e);
        } catch (DuplicateException $e) {
            throw new HttpException(409, 'Duplication Exception', $e);
        }

        return new JsonResponse($socio->toArray());
    }

    public function parsePayload(Request $request)
    {
        if (0 === strpos($request->headers->get('Content-Type'), 'application/json')) {
            $data = json_decode($request->getContent(), true);
            $request->request->replace(is_array($data) ? $data : array());
        }
    }
}
