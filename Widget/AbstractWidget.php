<?php

namespace Evgit\Bundle\WidgetBundle\Widget;

/**
 * abstract class AbstractWidget
 */
abstract class AbstractWidget implements WidgetInterface
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $template;

    /**
     * @var \Twig_Environment
     */
    protected $twig;

    /**
     * @param mixed $template
     *
     * @return $this
     */
    public function setTemplate($template)
    {
        $this->template = $template;

        return $this;
    }

    /**
     * @param \Twig_Environment $twig
     *
     * @return $this
     */
    public function setTwig(\Twig_Environment $twig)
    {
        $this->twig = $twig;

        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @param array $options
     *
     * @return string
     */
    public function render(array $options = [])
    {
        if (strpos($this->template, ":") === false
            || strpos($this->template, " ") !== false
        ) {
            $templateName = 'template'.md5($this->template);
            $loader = new \Twig_Loader_Array([
                $templateName => $this->template,
            ]);

            $twig = new \Twig_Environment($loader);

            return $twig->render($templateName, $options);
        }

        return $this->twig->render($this->template, $options);
    }
}
