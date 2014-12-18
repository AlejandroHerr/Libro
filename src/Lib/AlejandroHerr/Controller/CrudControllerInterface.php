<?php
namespace AlejandroHerr\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

interface CrudControllerInterface
{
    /**
     * Creates new item
     *
     * @param Appicaltion $app     Application instance
     * @param Request     $request Request instance
     *
     * @return Response Response instance
     */
    public function createAction(Application $app, Request $request);

    /**
     * Deletes an item
     *
     * @param Application $app Applications instance
     * @param integer     $id  Item's id
     *
     * @return Response Response instance
     */
    public function deleteAction(Application $app, $id);

    /**
     * Querys a collection of items
     *
     * @param Application $app        Application instance
     * @param integer     $page       Page
     * @param integer     $maxPerPage Items per page
     * @param string      $order      Sorting order
     *
     * @return Response Response instance
     */
    public function queryAction(Application $app, $page = 0, $maxPerPage = 50);

    /**
     * Returns item by id
     *
     * @param Appicaltion $app Application instance
     * @param integer     $id  Id of the item
     *
     * @return Response Response instance
     */
    public function readAction(Application $app, $id);

    /**
     * Updates existing element
     *
     * @param Application $app     Application instance
     * @param Request     $request Request instance
     * @param integer     $id      Item's id
     *
     * @return Response Response instance
     */
    public function updateAction(Application $app, Request $request, $id);
}
