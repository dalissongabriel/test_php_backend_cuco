<?php


namespace App\EventListener;

use App\Helpers\Exception\DatabaseException;
use App\Helpers\Exception\EntityFactoryException;
use App\Service\ResponseFactory;
use Doctrine\ORM\EntityNotFoundException;
use DomainException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\KernelEvents;

class ExceptionHandler implements EventSubscriberInterface
{

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::EXCEPTION => [
                ['handle404Exception',5],
                ['handle400Exception',4],
                ['handleEntityException',3],
                ['handleEntityNotFoundException',2],
                ['handleDatabaseException',1],
                ['handleDomainException',0],
            ]
        ];
    }

    public function handle404Exception(ExceptionEvent $event)
    {
        if ($event->getThrowable() instanceof NotFoundHttpException) {

            $responseFactory = ResponseFactory::fromError(
                $event->getThrowable(),
                Response::HTTP_NOT_FOUND);

            $event->setResponse($responseFactory->getResponse());
        }
    }

    public function handleEntityException(ExceptionEvent $event) {

        $exception = $event->getThrowable();

        if ($exception instanceof  EntityFactoryException) {
            $responseFactory = ResponseFactory::fromError(
                $exception,
                Response::HTTP_BAD_REQUEST
            );

            $event->setResponse($responseFactory->getResponse());
        }
    }

    public function handleEntityNotFoundException(ExceptionEvent $event)
    {
        $exception = $event->getThrowable();

        if ($exception instanceof  EntityNotFoundException) {
            $responseFactory = ResponseFactory::fromError(
                $exception,
                $exception->getCode()
            );

            $event->setResponse($responseFactory->getResponse());
        }
    }

    public function handle400Exception(ExceptionEvent $event)
    {
        $exception = $event->getThrowable();

        if ($exception instanceof  BadRequestHttpException) {
            $responseFactory = ResponseFactory::fromError(
                $exception,
                Response::HTTP_BAD_REQUEST
            );

            $event->setResponse($responseFactory->getResponse());
        }
    }

    public function handleDatabaseException(ExceptionEvent $event)
    {
        $exception = $event->getThrowable();

        if ($exception instanceof  DatabaseException) {
            $responseFactory = ResponseFactory::fromError(
                $exception,
                $exception->getCode()
            );

            $event->setResponse($responseFactory->getResponse());
        }
    }

    public function handleDomainException(ExceptionEvent $event)
    {
        $exception = $event->getThrowable();

        if ($exception instanceof  DomainException) {
            $responseFactory = ResponseFactory::fromError(
                $exception,
                412
            );

            $event->setResponse($responseFactory->getResponse());
        }
    }

}