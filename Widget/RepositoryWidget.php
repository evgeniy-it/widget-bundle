<?php

namespace Evgit\Bundle\WidgetBundle\Widget;

use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class ModelsWidget
 * @package Evgit\Bundle\WidgetBundle\Widget
 */
class RepositoryWidget extends AbstractWidget
{
    /**
     * @var OptionsResolver
     */
    private $resolver;

    /**
     * @var RegistryInterface
     */
    private $doctrine;

    /**
     * __construct()
     */
    public function __construct()
    {
        $this->resolver = new OptionsResolver();
        $this->setDefaultOptions();
    }

    /**
     * @param array $options
     *
     * @return string
     *
     * @throws \Exception
     */
    public function process(array $options)
    {
        if (!empty($options['tpl'])) {
            $this->setTemplate($options['tpl']);
        } else {
            $options['tpl'] = $this->template;
        }
        $options = $this->resolver->resolve($options);

        $repository = $this->doctrine->getRepository($options['model']);

        if (!is_callable([$repository, $options['function']])) {
            throw new \Exception(sprintf("Function \"%s\" of model \"%s\" repository didn't find", $options['function'], $options['model']));
        }

        $data = call_user_func_array([$repository, $options['function']], $this->argsProcess($options['args']));

        if (empty($data) || !count($data)) {
            return "";
        }

        if (!is_array($data)) {
            $data = [$data];
        }
        $return = "";

        if ($options['beforeTpl']) {
            $return .= $this->render($options, $options['beforeTpl']);
        }
        foreach ($data as $n => $item) {
            $rowOptions = array_merge($options, [
                'item' => $item,
                'idx' => $n,
                'isFirst' => $n === 0,
                'isLast' => count($item) - 1 === $n,
            ]);

            $return .= $this->render($rowOptions, $options['tpl']);

        }
        if ($options['beforeTpl']) {
            $return .= $this->render($options, $options['afterTpl']);
        }

        return $return;
    }

    /**
     * setDefaultOptions
     */
    public function setDefaultOptions()
    {
        $this->resolver->setRequired(['model', 'function']);
        $this->resolver->setDefaults([
            'beforeTpl' => '',
            'afterTpl' => '',
            'args' => "",
            'tpl' => "",
        ]);
    }

    /**
     * @param RegistryInterface $doctrine
     */
    public function setDoctrine(RegistryInterface $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    /**
     * @param $args
     *
     * @return array|mixed
     */
    protected function argsProcess($args)
    {
        $return = json_decode($args);

        if (json_last_error() !== JSON_ERROR_NONE) {
            $return = [$args];
        }

        return $return;
    }
}
