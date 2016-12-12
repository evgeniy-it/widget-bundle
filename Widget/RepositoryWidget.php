<?php

namespace Evgit\Bundle\WidgetBundle\Widget;

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

    public function __construct()
    {
        $this->resolver = new OptionsResolver();
        $this->setDefaultOptions();
    }

    /**
     * @param array $options
     *
     * @return string
     */
    public function process(array $options)
    {
        if (!empty($options['tpl'])) {
            $this->setTemplate($options['tpl']);
        } else {
            $options['tpl'] = $this->template;
        }

        $options = $this->resolver->resolve($options);

        return $this->render($options);
    }

    public function setDefaultOptions()
    {
        $this->resolver->setRequired(['model', 'tpl']);
    }
}
