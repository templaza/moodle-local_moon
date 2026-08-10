<?php
/**
 * @package   Moon Framework
 * @author    Moon Framework Team https://moonframe.work
 * @copyright Copyright (C) 2025 MoonFrame.work.
 * @license https://www.gnu.org/licenses/gpl-3.0.html GNU/GPLv3 or Later
 */
namespace local_moon\library\Element;
use local_moon\library\Framework;
use local_moon\library\Helper\Utilities;

defined('MOODLE_INTERNAL') || die;

class Element extends BaseElement
{
    public $section, $row, $column;
    public function __construct($data, $section, $row, $column, $role = '')
    {
        $this->section = $section;
        $this->row = $row;
        $this->column = $column;
        parent::__construct($data, $section->devices, $section->options, $role);
    }

    public function render()
    {
        $this->_decorateSection();
        if ($this->type == 'sublayout') {
            $this->content = Layout::renderSublayout($this->params->get('source', ''), 'layouts', [], 'sublayout');
        } else {
            $this->prepareContent();
            if (empty($this->state) || !$this->isAssigned) {
                return '';
            }
            $dynamic_data = $this->getDynamicContent();
            if (!empty($dynamic_data)) {
                foreach ($dynamic_data as $dynamic_data_item) {
                    foreach ($dynamic_data_item as $key => $value) {
                        $this->params->set($key, $value);
                    }
                    $this->content .= $this->_content();
                }
            } else {
                $this->content = $this->_content();
            }
        }
        return $this->wrap();
    }

    public function _content(): false|string
    {
        $layout = Framework::getTheme()->getElementLayout($this->type);
        if (empty($layout) || !file_exists($layout)) {
            return '';
        }
        ob_start();
        include $layout;
        return ob_get_clean();
    }

    public function prepareContent() {
//        $app            = Factory::getApplication();
//        $option         = $app->input->get('option', '', 'RAW');
//        $view           = $app->input->get('view', '', 'RAW');
//        $id             = (int) $app->input->get('id', null, 'RAW');
//        if ($option === 'com_content' && $view === 'article' && !empty($id)) {
//            $template_name = Framework::getTemplate()->template;
//            $layout_path = Path::clean(JPATH_SITE . "/media/templates/site/$template_name/params/article_widget_data/". $id . '_' . $this->unqid . '.json');
//            if (!file_exists($layout_path)) {
//                $layout_path = Path::clean(JPATH_SITE . "/media/templates/site/$template_name/moon/article_widget_data/". $id . '_' . $this->unqid . '.json');
//            }
//            if (file_exists($layout_path)) {
//                $article_json = file_get_contents($layout_path);
//                $article_data = json_decode($article_json, true);
//                $article_params = Helper::loadParams($article_data['params']);
//                $this->state = $article_data['state'];
//                $this->params->merge($article_params);
//            }
//        }
    }

    public function getDynamicContent() {
        $dynamic_data = [];
//        if (Helper::isPro()) {
            $dynamic_params = $this->params->get('dynamic_content_settings');
            if (!empty($dynamic_params)) {
                $dynamic_content = new DynamicContent(
                    $dynamic_params->source,
                    $dynamic_params->start,
                    $dynamic_params->quantity,
                    $dynamic_params->conditions,
                    $dynamic_params->order,
                    $dynamic_params->order_dir,
                    $dynamic_params->dynamic_content,
                    $dynamic_params->options
                );
                $dynamic_data = $dynamic_content->getContent();
            }
//        }
        return $dynamic_data;
    }

    public function _decorateSection()
    {
        $params = Framework::getTheme()->getParams();
        if ($this->type == 'header') {
            $this->section->hasHeader = true;
            $this->section->addClass('moon-header-section');
        }
        if ($this->type == "module_position") {
            if ($params->get('header_module_position', '') === $this->params->get('position', '')) {
                $this->section->hasHeader = true;
                $this->section->addClass('moon-header-section');
            }
            if ($params->get('footer_module_position', '') === $this->params->get('position', '')) {
                $this->section->hasFooter = true;
                $this->section->addClass('moon-footer-section');
            }
        }

        if ($this->type == "component") {
            $this->section->hasComponent = true;
            $this->column->component = true;
            $this->section->addClass('moon-component-section');
        }
    }
}
