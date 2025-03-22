<?php

namespace App\Infrastructure\Shared\ApiPlatform\EventListener;

use ApiPlatform\Serializer\SerializerContextBuilderInterface;
use ApiPlatform\Symfony\EventListener\DeserializeListener as DecoratedListener;
use ApiPlatform\Util\RequestAttributesExtractor;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

final class DeserializeListener
{
    private $decorated;
    private $denormalizer;
    private $serializerContextBuilder;

    public function __construct(
        DenormalizerInterface $denormalizer,
        SerializerContextBuilderInterface $serializerContextBuilder,
        DecoratedListener $decorated
    ) {
        $this->denormalizer = $denormalizer;
        $this->serializerContextBuilder = $serializerContextBuilder;
        $this->decorated = $decorated;
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();
        if ($request->isMethodCacheable(false) || $request->isMethod(Request::METHOD_DELETE)) {
            return;
        }

        if ('form' === $request->getContentType()) {
            $this->denormalizeFormRequest($request);
        } else {
            $this->decorated->onKernelRequest($event);
        }
    }

    private function denormalizeFormRequest(Request $request): void
    {
        if (!$attributes = RequestAttributesExtractor::extractAttributes($request)) {
            return;
        }

        $context = $this->serializerContextBuilder->createFromRequest($request, false, $attributes);
        $populated = $request->attributes->get('data');
        if (null !== $populated) {
            $context['object_to_populate'] = $populated;
        }

        $data = $request->request->all();
        $files = $request->files->all();

        foreach ($data as $k => $datum) {
            if ($this->isJson($datum)) {
                $data[$k] = json_decode($datum, true);
            }
        }

        if (!empty($files)) {
            $data = array_merge($data, $files);
        }

        $object = $this->denormalizer->denormalize($data, $attributes['resource_class'], null, $context);

        $request->attributes->set('data', $object);
    }

    private function isJson($string): bool
    {
        json_decode($string);

        return JSON_ERROR_NONE === json_last_error();
    }
}
