<?php

class Widget extends Doctrine_Record
{

    function setTableDefinition()
    {
        $this->hasColumn('id');
        $this->hasColumn('nombre');
        $this->hasColumn('tipo');
        $this->hasColumn('config');
        $this->hasColumn('posicion');
        $this->hasColumn('cuenta_id');
        $this->hasColumn('anomin');
        $this->hasColumn('anomax');
        $this->hasColumn('comuna');

        $this->setSubclasses(array(
                'WidgetTramiteEtapas' => array('tipo' => 'tramite_etapas'),
                'WidgetTramitesCantidad' => array('tipo' => 'tramites_cantidad'),
                'WidgetEtapaUsuarios' => array('tipo' => 'etapa_usuarios'),
                'WidgetEtapaGrupoUsuarios' => array('tipo' => 'etapa_grupo_usuarios'),
                'WidgetEstadoTramites' => array('tipo' => 'estado_tramites'),
                'WidgetEstadoTramitesComuna' => array('tipo' => 'estado_tramites_comuna')
            )
        );
    }

    function setUp()
    {
        parent::setUp();

        $this->hasOne('Cuenta', array(
            'local' => 'cuenta_id',
            'foreign' => 'id'
        ));
    }

    public function display()
    {
        return null;
    }

    public function displayForm()
    {
        return null;
    }

    public function validateForm()
    {
        return;
    }

    public function setConfig($datos_array)
    {
        if ($datos_array)
            $this->_set('config', json_encode($datos_array));
        else
            $this->_set('config', NULL);
    }

    public function setAnomin($anomin)
    {
        if ($anomin)
            $this->_set('anomin', $anomin);
        else
            $this->_set('anomin', NULL);
    }

    public function setAnomax($anomax)
    {
        if ($anomax)
            $this->_set('anomax', $anomax);
        else
            $this->_set('anomax', NULL);
    }

    public function setComuna($comuna)
    {
        if ($comuna)
            $this->_set('comuna', $comuna);
        else
            $this->_set('comuna', NULL);
    }

    public function getConfig()
    {
        return json_decode($this->_get('config'));
    }

    public function getAnomin()
    {
        return $this->_get('anomin');
    }

    public function getAnomax()
    {
        return $this->_get('anomax');
    }

    public function getComuna()
    {
        return $this->_get('comuna');
    }
}

