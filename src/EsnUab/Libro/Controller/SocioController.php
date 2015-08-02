<?php

namespace EsnUab\Libro\Controller;

use EsnUab\Libro\EventListener\SocioEvents;
use EsnUab\Libro\EventListener\Event\SocioEvent;
use EsnUab\Libro\Form\SocioType;
use EsnUab\Libro\Model\Socio;
use EsnUab\Libro\Model\SocioVersion;
use Silex\Application;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\HttpKernelInterface;

class SocioController
{
    public function newAction(Application $app, Request $request)
    {
        $form = $this->createForm($app);

        return ['form' => $form->createView()];
    }
    public function createAction(Application $app, Request $request)
    {
        $form = $this->processForm($app);

        if (!$form->isValid()) {
            $app->addFlashBag('danger', 'Hay problemas con los datos del socio.');

            return ['form' => $form->createView()];
        }

        $socio = $form->getData();
        $app['dispatcher']->dispatch(SocioEvents::SOCIO_CREATED, new SocioEvent($socio));

        $app->addFlashBag(
            'success',
            '¡Socio creado correctamente!'.
            sprintf('Puedes verlo <a href="%s">aqu&iacute;</a>.', $app->url('socio.read', ['socio' => $socio->getId()]))
        );

        $forwardRequest = Request::create($app->url('socio.create'), 'GET');
        $forwardRequest->headers->set('X-Requested-With', $request->headers->get('X-Requested-With'));

        return $app->handle($forwardRequest, HttpKernelInterface::SUB_REQUEST);
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

    public function queryAction(Application $app)
    {
        $pager = $app['request']->attributes->get('pager');

        $socios = [];
        foreach ($pager as $socio) {
            $socios[] = $socio->toArray();
        }
        $pagination = [
            'current' => count($socios),
            'total' => $pager->getNbResults(),
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

    public function modifyAction(Application $app, Socio $socio)
    {
        if ($socio->isModified()) {
            /*
             * @todo Add author of the version
             */
            $socio->save();
            $app->addFlashBag('success', 'Datos actualizados correctamente.');
        } else {
            $app->addFlashBag('warning', 'Operación no realizada: La acción ya fue realizada con anterioridad.');
        }

        return $app->redirect($app->path('socio.read', ['socio' => $socio->getId()], 'GET'));
    }

    /**
     * Process the form.
     *
     * @param Application $app
     * @param Socio|null  $socio  The Socio instance (for update actions)
     * @param string      $action Type of action ('crear' or 'editar')
     *
     * @return Form Processed form
     */
    protected function processForm(Application $app, Socio $socio = null, $action = SocioType::SUBMIT_ACTION_CREAR)
    {
        $form = $this->createForm($app, $socio, $action);
        $form->handleRequest($app['request']);
        $socio = $form->getData();
        if (!$socio->save()) {
            foreach ($socio->getValidationFailures() as $failure) {
                $form->get($failure->getPropertyPath())
                    ->addError(new FormError($failure->getMessage()));
            }
        }

        return $form;
    }
    /**
     * Creates a SocioType form.
     *
     * @param Application $app
     * @param Socio|null  $socio  The Socio instance (for update actions)
     * @param string      $action Type of action ('crear' or 'editar')
     *
     * @return Form The form
     */
    protected function createForm(Application $app, Socio $socio = null, $action = SocioType::SUBMIT_ACTION_CREAR)
    {
        $socio = $socio === null ? new Socio() : $socio;

        return $app['form.factory']->createBuilder(new SocioType(), $socio, ['action_type' => $action])
            ->getForm();
    }
}
