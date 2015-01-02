<?php
namespace Esnuab\Libro\Controller;

use AlejandroHerr\Controller\CrudControllerInterface;
use AlejandroHerr\Controller\RestControllerInterface;
use AlejandroHerr\Exception\Arr\HttpException;
use AlejandroHerr\Exception\Model\ModelExceptionInterface;
use Esnuab\Libro\Model\Socio;
use Esnuab\Libro\Model\SocioEditType;
use Esnuab\Libro\Model\SocioType;
use Esnuab\Libro\Model\Exception\SocioNotFoundException;
use Propel\Runtime\Map\TableMap;
use Silex\Application;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SocioController extends Core\SocioController implements CrudControllerInterface, RestControllerInterface
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
        $socio = new Socio();
        $form = $app['form.factory']->createBuilder(new SocioType(), $socio)
            ->getForm();

        $form->handleRequest($request);
        if ($form->isValid()) {
            try {
                $socio = $this->create($socio);

                $message = sprintf(
                    'Socio %d creado satisfactoriamente. <a href="%s">Click aquí</a> para verlo o editarlo.',
                    $socio->getId(),
                    $app->url('read_socio', ['id' => $socio->getId()])
                );

                $app['session']->getFlashBag()->add('success', [
                    'title'   => '¡Socio creado!',
                    'message' => $message,
                ]);

                return $app->redirect($app->url('new_socio'));
            } catch (ModelExceptionInterface $e) {
                $errors = $e->getArrayMessage();
                foreach ($errors as $key => $msg) {
                    $form->get($key)->addError(new FormError($msg));
                }
            }
        }

        return $app['twig']->render('create.twig', ['form' => $form->createView()]);
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
            $this->softDelete($id);
        } catch (SocioNotFoundException $e) {
            throw new HttpException(404, 'Resource not Found', $e);
        }

        $message = sprintf(
            'Socio <a href="%s">%d</a> eliminado satisfactoriamente.',
            $app->url('read_socio', ['id' => $id]),
            $id
        );
        $app['session']->getFlashBag()->add('success', [
            'title'   => '¡Socio eliminado!',
            'message' => $message,
        ]);

        return $app->redirect($app->url('query_socio'));
    }
    public function editAction(Application $app, Request $request, $id)
    {
        $socio = $this->read($id);
        //Reset version comment.
        $socio->setVersionComment('');
        $form = $app['form.factory']->createBuilder(new SocioEditType(), $socio)
            ->getForm();

        return $app['twig']->render('update.twig', ['form' => $form->createView()]);
    }
    public function newAction(Application $app, Request $request)
    {
        $socio = new Socio();
        $form = $app['form.factory']->createBuilder(new SocioType(), $socio)
            ->getForm();

        return $app['twig']->render('create.twig', ['form' => $form->createView()]);
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
        $socios = $sociosPager->toArray(null, null, TableMap::TYPE_CAMELNAME);
        $pagination = [
            'page' => $sociosPager->getPage(),
            'lastPage' => $sociosPager->getLastPage(),
            'maxPerPage' => $sociosPager->getMaxPerPage(),
            'firstIndex' => $sociosPager->getFirstIndex(),
            'lastIndex' => $sociosPager->getLastIndex(),
        ];

        return $app['twig']->render('query.twig', ['socios' => $socios, 'pagination' => $pagination]);
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

        return $app['twig']->render('read.twig', ['socio' => $socio]);
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
        $socio = $this->read($id);
        $form = $app['form.factory']->createBuilder(new SocioEditType(), $socio)
            ->getForm();

        $form->handleRequest($request);
        if ($form->isValid()) {
            try {
                $socio = $this->update($socio);

                $message = sprintf(
                    'Socio %d editado satisfactoriamente.',
                    $socio->getId()
                );

                $app['session']->getFlashBag()->add('success', [
                    'title'   => '¡Socio actualizado!',
                    'message' => $message,
                ]);

                return $app->redirect($app->url('read_socio', ['id' => $socio->getId()]));
            } catch (ModelExceptionInterface $e) {
                $errors = $e->getArrayMessage();
                foreach ($errors as $key => $msg) {
                    $form->get($key)->addError(new FormError($msg));
                }
            }
        }

        return $app['twig']->render('update.twig', ['form' => $form->createView()]);
    }

    public function parsePayload(Request $request)
    {
        if (0 === strpos($request->headers->get('Content-Type'), 'application/json')) {
            $data = json_decode($request->getContent(), true);
            $request->request->replace(is_array($data) ? $data : array());
        }
    }
}
