<?php

class SugarServiceApi {

    public $db;

    /**
     * Handles validation of required arguments for a request
     *
     * @param array $args
     * @param array $requiredFields
     * @throws SugarApiExceptionMissingParameter
     */
    public function requireArgs(&$args,$requiredFields = array()) {
        foreach ( $requiredFields as $fieldName ) {
            if ( !array_key_exists($fieldName, $args) ) {
                throw new SugarApiExceptionMissingParameter('Missing parameter: '.$fieldName);
            }
        }
    }

    public function registerApiRest() {
        throw new SugarApiExceptionError('missing required registerApiRest method');
    }
}
