<?php

namespace TGHP\WPGatsbyTGHP\Actions;

interface ActionInterface
{

    public function getActionCode(): string;

    public function executePriv(): string;
    public function executeNoPriv(): string;

}