<?php
/**
 * Calculate and display result html
 * Created by PhpStorm.

 * Date: 10/19/2018
 * Time: 10:35 AM
 * Neotis framework
 */

namespace Neotis\Core\Template\Html;

use Neotis\Core\Exception\Exception;
use Neotis\Core\Http\Header;
use Neotis\Core\Mvc\Component;
use Neotis\Core\Mvc\View;
use Neotis\Core\Router\Router;
use \Wa72\HtmlPageDom\HtmlPage;

trait Manipulator
{
    /**
     * Store parameters of components
     * @var array
     */
    private static $parameters = [];

    /**
     * Store parameters of components method
     * @var array
     */
    private static $methodParameters = [];

    /**
     * Store Created and manipulated the contents of the main file
     * @var string
     */
    protected static $result = '';

    /**
     * Main path of selected package assets
     * @var string
     */
    protected static $mainPath = '';

    /**
     * Store attribute of tags element
     * @var array
     */
    protected static $attributes = [];

    /**
     * Component variables
     * @var array
     */
    protected static $componentVars = [];

    /**
     * Store of replace history
     * @var array
     */
    protected static $replaceHistory = [];

    /**
     * Create and manipulate main file
     * @return false|string
     */
    private function main()
    {
        $mainIndex = View::getMainIndex();
        $main = View::getMain() . $mainIndex . DS . 'index.phtml';

        if (file_exists($main)) {
            $main = file_get_contents($main);
        } else {
            $main = '';
        }
        $device = strtolower(View::getDevice());
        $package = strtolower(Router::getPackage());
        $bodyReplace = '<body type="' . $package . '-' . $mainIndex . '"';

        $search = ['neo_assets/', '<body'];
        $replace = ['/' . $package . '/' . $device . '/main/' . $mainIndex . '/assets/', $bodyReplace];
        self::$mainPath = '/' . $package . '/' . $device . '/main/' . $mainIndex . '/assets/';
        $main = str_ireplace($search, $replace, $main);

        self::$result = $main;
    }

    /**
     * Calculate and manipulate the contents of the "Controller"
     * In this section, the tags are replaced by the contents with the html inside the controller.
     */
    private function content()
    {
        $main = self::$result;
        $controller = strtolower(Router::getController());
        $action = strtolower(Router::getAction());
        $device = strtolower(View::getDevice());
        $package = strtolower(Router::getPackage());

        $content = View::getContent();

        if (file_exists($content . 'index.phtml')) {
            $content = file_get_contents($content . 'index.phtml');
        } else {
            $content = '';
        }

        $search = 'neo_assets/';
        $replace = $package . '/' . $device . '/controllers/' . $controller . '/' . $action . '/assets/';
        $content = str_ireplace($search, $replace, $content);

        if (Header::$partial) {
            $main = '<neo-content></neo-content>';
        } elseif (Header::$component) {
            $main = '<neo-' . self::$componentTag . '></neo-' . self::$componentTag . '>';
        }

        if (!Header::$pure) {
            self::$result = str_ireplace('<neo-content></neo-content>', '<app-' . $controller . '-' . $action .
                ' section="' . $controller . $action . '">' . $content .
                '</app-' . $controller . '-' . $action . '>', $main);
        } else {
            self::$result = $content;
        }

        if (Header::$pure and Header::$component) {
            self::$result = str_ireplace('<neo-content></neo-content>', '<app-' . $controller . '-' . $action .
                ' section="' . $controller . $action . '">' . $content .
                '</app-' . $controller . '-' . $action . '>', $main);
        }
    }

    /**
     * Generate and prepare result file for include
     * @throws \Neotis\Core\Exception\Exception
     */
    private function prepareResult()
    {
        if (!Header::$partial) {
            $this->main();//Prepare html of main
        }

        $this->content();//Prepare content of selected controller

        $controller = strtolower(Router::getController());
        $action = strtolower(Router::getAction());

        self::$result = $this->attachCss(self::$result, $controller, $action, 'controllers');
        self::$result = $this->attachJs(self::$result, $controller, $action, 'controllers');

        self::$result = $this->tagsFinder(self::$result);

        self::$result = str_ireplace('(style)', $this->getCssString(), self::$result);
        self::$result = str_ireplace('(meta-tag)', $this->getMetaTagsString(), self::$result);
        self::$result = str_ireplace('(top-script)', $this->getTopScriptString(), self::$result);
        self::$result = str_ireplace('(bottom-script)', $this->getBottomScriptString(), self::$result);
        self::$result = str_ireplace('(title)', '<?php echo Neotis\Core\Template\Factory::getTitle(); ?>', self::$result);

        self::$result = str_ireplace('<app-', '<neo-', self::$result);
        self::$result = str_ireplace('</app-', '</neo-', self::$result);

        self::$result = str_ireplace('neo_main/', self::$mainPath, self::$result);
        if (!Header::$partial and !Header::$component) {
            $directory = pathinfo(View::getResultFile());
            if (!is_dir($directory['dirname'])) {
                mkdir($directory['dirname'], 0755, true);
            }
            file_put_contents(View::getResultFile(), self::$result);
        } elseif (Header::$component) {
            if (Header::$pure) {
                $path = str_replace('manufactured' . DS . 'result', 'manufactured' . DS . 'pure-component', View::getResultFile());
            } else {
                $path = str_replace('manufactured' . DS . 'result', 'manufactured' . DS . 'component', View::getResultFile());
            }
            $directory = pathinfo($path);
            if (!is_dir($directory['dirname'])) {
                mkdir($directory['dirname'], 0755, true);
            }

            file_put_contents($path, self::$result);
        } elseif (Header::$partial and Header::$pure) {
            $path = str_replace('manufactured' . DS . 'result', 'manufactured' . DS . 'pure-partial', View::getResultFile());
            $directory = pathinfo($path);
            if (!is_dir($directory['dirname'])) {
                mkdir($directory['dirname'], 0755, true);
            }

            file_put_contents($path, self::$result);
        } else {
            $path = str_replace('manufactured' . DS . 'result', 'manufactured' . DS . 'partial', View::getResultFile());
            $directory = pathinfo($path);
            if (!is_dir($directory['dirname'])) {
                mkdir($directory['dirname'], 0755, true);
            }

            file_put_contents($path, self::$result);
        }

    }

    /**
     * Generate components by tag attributes
     * @param $tag
     * @param $variables
     * @return string
     */
    private function componentVariables($tag, $variables)
    {
        $id = $tag;
        if (isset($variables['id'])) {
            $id = $variables['id'];
        }
        self::$componentVars[$id] = [];
        foreach ($variables as $key => $value) {
            if ($key == 'id') {
                $value = 'neo-' . $value;
            }
            self::$componentVars[$id][$key] = $value;
        }

        return $id;
    }

    public function extract_tpl($string, $tag = "")
    {
        $dom = new \DOMDocument();
        libxml_use_internal_errors(true);
        $dom->loadHTML($string);

        $result = array();

        $ul = $dom->getElementsByTagName($tag)->item(0);
        if ($ul->hasAttributes()) {
            foreach ($ul->attributes as $attr) {
                $name = $attr->nodeName;
                $value = $attr->nodeValue;
                $result[$name] = $value;
            }
        }

        return $result;
    }

    /**
     * Explore components attributes
     * @param $contents
     * @param $value
     * @return array
     */
    private function attributesGenerator($contents, $value)
    {
        $attributes = explode(' ', $value);
        $tag = $attributes[0];
        unset($attributes[0]);

        $stringTag = '<' . $value . '></' . $tag . '>';

        $stringTag = str_ireplace('{{', '<?php echo $', $stringTag);
        $stringTag = str_ireplace('}}', '; ?>', $stringTag);

        $result = $this->extract_tpl('<?xml encoding="utf-8" ?>' . $stringTag, $tag);
        $attributes = [];
        foreach ($result as $iKey => $iValue) {
            $attributes[] = $iKey . '="' . $iValue . '"';
        }

        self::$attributes[$tag] = $attributes;

        $value = str_ireplace($tag, '', $value);

        $contents = str_ireplace($tag . $value, $tag, $contents);

        self::$methodParameters[$tag] = $result;


        $id = $this->componentVariables($tag, self::$methodParameters[$tag]);
        return [$tag, $contents, $id];
    }

    /**
     * Find and replace components with final string syntax
     * @param $content
     * @return array
     * @throws \Neotis\Core\Exception\Exception
     */
    private function tagsFinder($content)
    {
        $mainTag = $this->getStringBetween($content, '<neo-', '>');

        foreach ($mainTag as $key => $value) {
            $contents = $this->attributesGenerator($content, $value);
            $search = '<neo-' . $contents[0] . '></neo-' . $contents[0] . '>';
            $replace = $this->attachToTag($contents[0], $contents[2]);
            $content = str_ireplace($search, $replace, $contents[1]);
            $content = $this->tagsFinder($content);
        }

        return $content;
    }

    /**
     * Detect neo tags in string
     * @param $str
     * @param $startDelimiter
     * @param $endDelimiter
     * @return array
     */
    private function getStringBetween($str, $startDelimiter, $endDelimiter)
    {
        $contents = array();
        $startDelimiterLength = strlen($startDelimiter);
        $endDelimiterLength = strlen($endDelimiter);
        $startFrom = $contentStart = $contentEnd = 0;
        while (false !== ($contentStart = strpos($str, $startDelimiter, $startFrom))) {
            $contentStart += $startDelimiterLength;
            $contentEnd = strpos($str, $endDelimiter, $contentStart);
            if (false === $contentEnd) {
                break;
            }
            $contents[] = substr($str, $contentStart, $contentEnd - $contentStart);
            $startFrom = $contentEnd + $endDelimiterLength;
        }

        return $contents;
    }

    /**
     * Detect javascript tags to append in html file
     * @param $content
     * @param $controller
     * @param $component
     * @param string $type
     * @param bool $base
     * @return mixed
     */
    private function attachJs($content, $controller, $component, $type = 'component', $base = false)
    {
        $scripts = $this->getStringBetween($content, '<neoscript', '</neoscript>');
        $scriptsBottom = $this->getStringBetween($content, '<neobottomscript', '</neobottomscript>');
        $device = strtolower(View::getDevice());
        $package = strtolower(Router::getPackage());
        if ($base === false) {
            $mainPath = '/' . $package . '/' . $device . '/';
        } else {
            $mainPath = '/base/' . $device . '/';
        }
        if ($type === 'component') {
            if ($controller === 'ControllerBase') {
                foreach ($scripts as $key => $value) {
                    $file = $mainPath . 'components/' . $component . '/assets/';
                    $final = str_ireplace(['src="', ' ', '"', '>', 'neo_assets/'], ['', '', '', '', $file], $value);
                    self::addTopJs($final);
                    $content = str_ireplace('<neoscript' . $value . '</neoscript>', "", $content);
                }
                foreach ($scriptsBottom as $key => $value) {
                    $file = $mainPath . 'components/' . $component . '/assets/';
                    $final = str_ireplace(['src="', ' ', '"', '>', 'neo_assets/'], ['', '', '', '', $file], $value);
                    self::addBottomJs($final);
                    $content = str_ireplace('<neobottomscript' . $value . '</neobottomscript>', "", $content);
                }
            } else {
                foreach ($scripts as $key => $value) {
                    $file = $mainPath . 'components/' . $controller . '/' . $component . '/assets/';
                    $final = str_ireplace(['src="', ' ', '"', '>', 'neo_assets/'], ['', '', '', '', $file], $value);
                    self::addTopJs($final);
                    $content = str_ireplace('<neoscript' . $value . '</neoscript>', "", $content);
                }
                foreach ($scriptsBottom as $key => $value) {
                    $file = $mainPath . 'components/' . $controller . '/' . $component . '/assets/';
                    $final = str_ireplace(['src="', ' ', '"', '>', 'neo_assets/'], ['', '', '', '', $file], $value);
                    self::addBottomJs($final);
                    $content = str_ireplace('<neobottomscript' . $value . '</neobottomscript>', "", $content);
                }
            }
        } else {
            foreach ($scripts as $key => $value) {
                $file = $mainPath . 'controllers/' . $controller . '/' . $component . '/assets/';
                $final = str_ireplace(['src="', ' ', '"', '>', 'neo_assets/'], ['', '', '', '', $file], $value);
                self::addTopJs($final);
                $content = str_ireplace('<neoscript' . $value . '</neoscript>', "", $content);
            }
            foreach ($scriptsBottom as $key => $value) {
                $file = $mainPath . 'controllers/' . $controller . '/' . $component . '/assets/';
                $final = str_ireplace(['src="', ' ', '"', '>', 'neo_assets/'], ['', '', '', '', $file], $value);
                self::addBottomJs($final);
                $content = str_ireplace('<neobottomscript' . $value . '</neobottomscript>', "", $content);
            }
        }
        return $content;
    }


    /**
     * Detect Css tags to append in html file
     * @param $content
     * @param $controller
     * @param $component
     * @param string $type
     * @param bool $base
     * @return mixed
     */
    private function attachCss($content, $controller, $component, $type = 'component', $base = false)
    {
        $css = $this->getStringBetween($content, '<neocss', '</neocss>');
        $device = strtolower(View::getDevice());
        $package = strtolower(Router::getPackage());
        if ($base === false) {
            $mainPath = '/' . $package . '/' . $device . '/';
        } else {
            $mainPath = '/base/' . $device . '/';
        }
        if ($type === 'component') {
            if ($controller === 'ControllerBase') {
                foreach ($css as $key => $value) {
                    $file = $mainPath . 'components/' . $component . '/assets/';
                    $final = str_ireplace(['src="', ' ', '"', '>', 'neo_assets/'], ['', '', '', '', $file], $value);

                    self::addCss($final);
                    $content = str_ireplace('<neocss' . $value . '</neocss>', "", $content);
                }
            } else {
                foreach ($css as $key => $value) {
                    $file = $mainPath . 'components/' . $controller . '/' . $component . '/assets/';
                    $final = str_ireplace(['src="', ' ', '"', '>', 'neo_assets/'], ['', '', '', '', $file], $value);
                    self::addCss($final);
                    $content = str_ireplace('<neocss' . $value . '</neocss>', "", $content);
                }
            }
        } else {
            foreach ($css as $key => $value) {
                $file = $mainPath . 'controllers/' . $controller . '/' . $component . '/assets/';
                $final = str_ireplace(['src="', ' ', '"', '>', 'neo_assets/'], ['', '', '', '', $file], $value);
                self::addCss($final);
                $content = str_ireplace('<neocss' . $value . '</neocss>', "", $content);
            }
        }
        return $content;
    }

    /**
     * Placement php code in neo tags html for call component method from class
     * @param $tag
     * @param $package
     * @param $component
     * @param $method
     * @return string
     */
    private function phpCaller($tag, $package, $component, $method)
    {
        $text = '<?php if(!isset($neo_params)){$neo_params = [];} $neo_data = Components\\' . $package . '\\' . ucfirst($component) . 'Component::' . $method . '($neo_params); ?>';
        $result = $text . $tag . '<?php $neo_params = []; ?>';
        return $result;
    }

    /**
     * Find and attach single component by tag name
     * @param $tag
     * @param $id
     * @return string
     * @throws Exception
     */
    private function attachToTag($tag, $id)
    {
        self::$replaceHistory[$id] = true;

        $directories = explode('-', $tag);
        $device = strtolower(View::getDevice());
        $package = strtolower(Router::getPackage());
        $attributesString = '';

        foreach (self::$attributes[$tag] as $key => $value) {
            if (strpos($value, 'id=') === false) {
                $attributesString .= ' ' . $value;
            }
        }

        if (isset($directories[1])) {
            $controller = $directories[0];
            $component = $directories[1];
            $content = View::getBase() . 'components' . DS . $controller . DS . $component . DS;
            $parentContents = View::getBase() . 'components' . DS . $controller . DS . 'index.phtml';
            $contentBase = View::getBasePackageDirectory() . 'components' . DS . $controller . DS . $component . DS;
            $parentContentBase = View::getBasePackageDirectory() . 'components' . DS . $controller . DS . 'index.phtml';


            $content .= 'index.phtml';
            $contentBase .= 'index.phtml';
            if (file_exists($content)) {
                $content = file_get_contents($content);
                if (file_exists($parentContents)) {
                    $pContent = file_get_contents($parentContents);
                    $this->attachJs($pContent, 'ControllerBase', $controller);
                    $this->attachCss($pContent, 'ControllerBase', $controller);
                }
                $content = $this->attachJs($content, $controller, $component);
                $content = $this->attachCss($content, $controller, $component);
            } elseif (file_exists($contentBase)) {
                $package = 'base';
                $content = file_get_contents($contentBase);
                if (file_exists($parentContentBase)) {
                    $pContent = file_get_contents($parentContentBase);
                    $this->attachJs($pContent, 'ControllerBase', $controller);
                    $this->attachCss($pContent, 'ControllerBase', $controller);
                }
                $content = $this->attachJs($content, $controller, $component, "component", true);
                $content = $this->attachCss($content, $controller, $component, "component", true);
            } else {
                throw new Exception('The selected component file or directories: ' . $component . ' is not exist on ' . $controller . ' from ' . $package . ' package');
            }
            $search = 'neo_assets/';
            $replace = $package . '/' . $device . '/components/' . $controller . '/' . $component . '/assets/';

            $content = str_ireplace($search, $replace, $content);

            $variables = self::$componentVars[$id];

            $id = str_replace('-', '_', $id);

            $pure = false;
            foreach ($variables as $key => $value) {
                if ($key == 'display' and $value == 'pure') {
                    $pure = true;
                }
                Component::$tempVars[$id][$key] = $value;
            }

            $dataVariables = '
                <?php if(isset($data)){$dataCache = $data;}else{$dataCache = [];} $data = []; if(isset(${\'' . $id . '\'})){$data = ${\'' . $id . '\'};} ?>
            ';
            $content = $dataVariables . $content;
            if ($pure) {
                $openTag = $content;
            } else {
                $openTag = '<app-component-' . $controller . '-' . $component . ' ' . $attributesString . ' type="component" section="' . $controller . $component . '">' . $content . '</app-component-' . $controller . '-' . $component . '>';
            }
            $openTag .= '<?php $data = $dataCache; ?>';
            return $openTag;
        } else {
            $component = $directories[0];
            $content = View::getBase() . 'components' . DS . $component . DS;
            $contentBase = View::getBasePackageDirectory() . 'components' . DS . $component . DS;
            $content .= 'index.phtml';
            $contentBase .= 'index.phtml';
            if (file_exists($content)) {
                $content = file_get_contents($content);
                $content = $this->attachJs($content, 'ControllerBase', $component);
                $content = $this->attachCss($content, 'ControllerBase', $component);
            } elseif (file_exists($contentBase)) {
                $content = file_get_contents($contentBase);
                $content = $this->attachJs($content, 'ControllerBase', $component);
                $content = $this->attachCss($content, 'ControllerBase', $component);
            } else {
                throw new Exception('The selected component file or directories: ' . $component . ' is not exist on Global from ' . $package . ' package');
            }
            $search = 'neo_assets/';
            $replace = $package . '/' . $device . '/components/' . $component . '/assets/';
            $content = str_ireplace($search, $replace, $content);

            $variables = self::$componentVars[$id];

            $id = str_replace('-', '_', $id);

            foreach ($variables as $key => $value) {
                Component::$tempVars[$id][$key] = $value;
            }

            $dataVariables = '
                <?php if(isset($data)){$dataCache = $data;}else{$dataCache = [];} $data = []; if(isset(${\'' . $id . '\'})){$data = ${\'' . $id . '\'};} ?>
            ';

            $content = $dataVariables . $content;

            $openTag = '<app-component-' . $component . ' ' . $attributesString . '  type="component" section="' . $component . '">' . $content . '</app-component-' . $component . '>';
            $openTag .= '<?php $data = $dataCache; ?>';

            return $openTag;
        }
    }
}
