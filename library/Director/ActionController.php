<?php

namespace Icinga\Module\Director;

use Icinga\Application\Icinga;
use Icinga\Module\Director\Db;
use Icinga\Module\Director\Web\Form\FormLoader;
use Icinga\Module\Director\Web\Table\TableLoader;
use Icinga\Web\Controller;
use Icinga\Web\Widget;

abstract class ActionController extends Controller
{
    protected $db;

    protected $forcedMonitoring = false;

    public function init()
    {
        $m = Icinga::app()->getModuleManager();
        if (! $m->hasLoaded('monitoring') && $m->hasInstalled('monitoring')) {
            $m->loadModule('monitoring');
        }
    }

    public function loadForm($name)
    {
        return FormLoader::load($name, $this->Module());
    }

    public function loadTable($name)
    {
        return TableLoader::load($name, $this->Module());
    }

    protected function setIcingaTabs()
    {
        $this->view->tabs = Widget::create('tabs')->add('services', array(
            'label' => $this->translate('Services'),
            'url'   => 'director/list/services')
        )->add('hosts', array(
            'label' => $this->translate('Hosts'),
            'url'   => 'director/list/hosts')
        );
        return $this->view->tabs;
    }

    protected function db()
    {
        if ($this->db === null) {
            $this->db = Db::fromResourceName($this->Config()->get('db', 'resource'));
        }

        return $this->db;
    }
}