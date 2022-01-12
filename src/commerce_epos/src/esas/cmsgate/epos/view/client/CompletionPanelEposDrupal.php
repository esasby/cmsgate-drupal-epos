<?php
/**
 * Created by IntelliJ IDEA.
 * User: nikit
 * Date: 24.06.2019
 * Time: 14:11
 */

namespace esas\cmsgate\epos\view\client;

class CompletionPanelEposDrupal extends CompletionPanelEpos
{
    public function getCssClass4MsgSuccess()
    {
        return "alert alert-info";
    }

    public function getCssClass4MsgUnsuccess()
    {
        return "alert alert-danger";
    }

    public function getCssClass4Button()
    {
        return "btn btn-primary";
    }

    public function getCssClass4TabsGroup()
    {
        return "panel-group";
    }

    public function getCssClass4Tab()
    {
        return "panel panel-default";
    }

    public function getCssClass4TabHeader()
    {
        return "panel-heading";
    }

    public function getCssClass4TabHeaderLabel()
    {
        return "panel-title";
    }

    public function getCssClass4TabBody()
    {
        return "panel-collapse";
    }

    public function getCssClass4TabBodyContent()
    {
        return "panel-body";
    }


    public function getCssClass4AlfaclickForm()
    {
        return "form-inline";
    }

    public function getCssClass4FormInput()
    {
        return "form-control";
    }

    public function getModuleCSSFilePath()
    {
        return dirname(__FILE__) . "/hiddenRadio.css";
    }


}