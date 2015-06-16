<?php

namespace EsnUab\Libro\Controller;

use EsnUab\Libro\Form\SocioType;
use EsnUab\Libro\Model\Socio;
use EsnUab\Libro\Model\SocioVersion;
use Silex\Application;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use EsnUab\Libro\EventListener\SocioEvents;
use EsnUab\Libro\EventListener\Event\SocioEvent;

class SocioController
{
    public function createAction(Application $app, Request $request)
    {
        $socio = new Socio();
        $form = $this->getForm($app, $socio);

        if ('POST' !== $request->getMethod()) {
            return ['form' => $form->createView()];
        }

        if (!$this->processForm($request, $form, $socio)) {
            $app->addFlashBag('danger', 'Hay problemas con los datos del socio.');

            return ['form' => $form->createView()];
        }

        $app['dispatcher']->dispatch(SocioEvents::SOCIO_CREATED, new SocioEvent($socio));

        return ['form' => $this->getForm($app)->createView()];
    }
    public function deleteAction(Application $app, Socio $socio)
    {
        $socio->setRemoved(true)
            ->save();

        return $app->redirect($app->path('socio.read', ['socio' => $socio->getId()], 'GET'));
    }

    public function readAction(Application $app, Socio $socio)
    {
        $response = $socio->toArray();
        $response['versions'] = $socio->getAllVersionsInfo();

        return ['socio' => $response];
    }

    public function readVersionAction(Application $app, Socio $socio, SocioVersion $version)
    {
        $response = $version->toArray();
        $response['versions'] = $socio->getAllVersionsInfo();

        return ['socio' => $response];
    }

    public function updateAction(Application $app, Request $request, Socio $socio)
    {
        $form = $app['form.factory']->createBuilder(new SocioType(), $socio, ['action_type' => 'editar'])->getForm();

        if ('POST' !== $request->getMethod() || 'PUT' !== $request->getMethod()) {
            return ['form' => $form->createView()];
        }

        if (!$this->processForm($request, $form, $socio)) {
            $app->addFlashBag('danger', 'Hay problemas con los datos del socio.');

            return ['form' => $form->createView()];
        }

        $app->addFlashBag('success', 'Datos actualizados correctamente.');

        return $app->redirect($app->path('socio.read', ['socio' => $socio->getId()], 'GET'));
    }

    /**
     * Process the form of a create or update request.
     *
     * @param Request $request
     * @param Form    $form
     * @param Socio   $socio
     *
     * @return bool Returns true if the form was processed
     */
    protected function processForm(Request $request, Form $form, Socio $socio)
    {
        $form->handleRequest($request);

        if ($socio->save()) {
            return true;
        }

        foreach ($socio->getValidationFailures() as $failure) {
            $form->get($failure->getPropertyPath())->addError(new FormError($failure->getMessage()));
        }

        return false;
    }

    public function queryAction(Application $app)
    {
        $pager = $app['request']->attributes->get('pager');

        $socios = [];
        foreach ($pager as $socio) {
            $socios[] = $socio->toArray();
        }
        $pagination = [
            'page' => $pager->getPage(),
            'first_page' => 1,
            'last_page' => $pager->getLastPage(),
            'limit' => $pager->getMaxPerPage(),
            'sort_field' => substr($app['request']->attributes->get('sort'), 1),
            'sort_sign' => substr($app['request']->attributes->get('sort'), 0, 1),
        ];

        $response = [
            'socios' => $socios,
            'pagination' => $pagination,
        ];

        return $response;
    }

    protected function getForm(Application $app, $socio = null, $action = 'crear')
    {
        $socio = $socio === null ? new Socio() : $socio;

        return $app['form.factory']->createBuilder(new SocioType(), $socio, ['action_type' => $action])->getForm();
    }
}
