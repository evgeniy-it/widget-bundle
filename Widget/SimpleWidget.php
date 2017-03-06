<?php

namespace Evgit\Bundle\WidgetBundle\Widget;

use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * class SimpleWidget
 */
class SimpleWidget extends AbstractWidget
{
    /**
     * __construct
     */
    public function __construct()
    {
        $this->resolver = new OptionsResolver();
    }

    /**
     * @param array $options
     *
     * @return string
     */
    public function process(array $options)
    {
        if (!empty($this->resolver->getDefinedOptions()) || !empty($this->resolver->getRequiredOptions())) {
            $options = $this->resolver->resolve($options);
        }

        if (!empty($options['template'])) {
            $this->setTemplate($options['template']);
        }
dump($options);
        return $this->render($options);
    }

    /**
     * @param array $array
     *
     * @return $this
     */
    public function setRequiredOptions(array $array)
    {dump($array);
        $this->resolver->setRequired($array);

        return $this;
    }

    /**
     * @param mixed $defaultOptions
     *
     * @return $this
     */
    public function setDefaultOptions($defaultOptions)
    {
        $this->resolver->setDefaults($defaultOptions);

        return $this;
    }
}
