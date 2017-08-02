<?php
/*
 * This file is part of XSCMS.
 *

 *

 */
namespace XSCMS;

/**
 * Interface of templates provider
 * @package XSCMS
 */
interface ProviderInterface
{
    /**
     * @param string $tpl
     * @return bool
     */
    public function templateExists($tpl);

    /**
     * @param string $tpl
     * @param int $time
     * @return string
     */
    public function getSource($tpl, &$time);

    /**
     * @param string $tpl
     * @return int
     */
    public function getLastModified($tpl);

    /**
     * Verify templates (check mtime)
     *
     * @param array $templates [template_name => modified, ...] By conversation, you may trust the template's name
     * @return bool if true - all templates are valid else some templates are invalid
     */
    public function verify(array $templates);

    /**
     * Get all names of template from provider
     * @return array|\Iterator
     */
    public function getList();
}
