<?php{namespace k=EBT\ExtensionBuilder\ViewHelpers}<f:format.raw>
date_default_timezone_set('America/Santiago');

use {espacioNombre}\EID\Util;

/**
 * Servicio EID Ejmplo
 * El servicio esta definido en el archivo de configuración: ext_localconf.php
 *
 * url: mydomain.com/index.php?eID={extension.extensionKey}_<k:format.lowercaseFirst>{domainObject.name}</k:format.lowercaseFirst>
 * JSON params: {"usr":"","pass":"","action":"list"}
 */

/*
 * Carga de librerias y dependencias TYPO3
 * Código estandar para todos los servicios EID. No modificar.
 */
if (!defined ('PATH_typo3conf')) die ('Access denied.');

\TYPO3\CMS\Frontend\Utility\EidUtility::initTCA();

$id = isset($HTTP_GET_VARS['id'])?$HTTP_GET_VARS['id']:0;
header('Content-Type: application/json');

/** @var \TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController $TSFE */
$TSFE = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController', $GLOBALS['TYPO3_CONF_VARS'], $id, '0', 1);
$GLOBALS['TSFE'] = $TSFE;
$GLOBALS['TSFE']->initFEuser(); // Get FE User Information
$GLOBALS['TSFE']->fetch_the_id();
$GLOBALS['TSFE']->getPageAndRootline();
$GLOBALS['TSFE']->initTemplate();
$GLOBALS['TSFE']->tmpl->getFileName_backPath = PATH_site;
$GLOBALS['TSFE']->forceTemplateParsing = 1;
$GLOBALS['TSFE']->getConfigArray();
$GLOBALS['TSFE']->register['hello'] = 1;

/** @var \TYPO3\CMS\Extbase\Object\ObjectManager $objectManager */
$objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\CMS\Extbase\Object\ObjectManager');
$persistenceManagerInterface = $objectManager->get('TYPO3\\CMS\\Extbase\\Persistence\\PersistenceManagerInterface');

/*
 * Inicio de logica del servicio
 */
error_log('-- SERVICIO {domainObject.name} --');

/* Obtiene datos en formato JSON */
$headers = apache_request_headers();
$data = file_get_contents('php://input');
$datos = json_decode($data);

/** @var Util $eidUtil */
$eidUtil = new Util();

/* Comprueba el acceso al servicio */
$login = Util::checkUser($datos->usr,$datos->pass);
$session = Util::checkSession();
$apiKey = Util::checkHeaderApiKey($headers['X-API-KEY']);
if ($login != TRUE && $session != TRUE && $apiKey != TRUE) {
    $eidUtil->JSendResponse('fail','no se ha podido acceder al servicio');
    die;
}

/** @var {domainObject.fullyQualifiedDomainRepositoryClassName} ${lcdomainRepositoryClassName} */
${lcdomainRepositoryClassName} = $objectManager->get('{domainObject.qualifiedDomainRepositoryClassName}');

if ($datos->action == 'list')
{
    ${lcdomainName}s = ${lcdomainRepositoryClassName}->findAll()->toArray();
    $total = count(${lcdomainName}s);
    $dataResponse = array();
    for( $i = 0; $i < $total; $i++) {
        /** @var {domainObject.fullQualifiedClassName} ${lcdomainName} */
        ${lcdomainName} = ${lcdomainName}s[$i];
        $dataResponse[$i] = ${lcdomainName}->_getProperties();
    }

    $eidUtil->JSendResponse('success',$dataResponse);
    die;

}
elseif ($datos->action == 'show')
{
    if( !isset($datos->uid) ) {
        $eidUtil->JSendResponse('fail','no hay uid');
        die;
    }

    /** @var {domainObject.fullQualifiedClassName} ${lcdomainName} */
    ${lcdomainName} = ${lcdomainRepositoryClassName}->findByUid($datos->uid);
    if(${lcdomainName} == NULL){
        $eidUtil->JSendResponse('fail',$datos->uid.' no encontrado');
        die;
    }
    $eidUtil->JSendResponse('success',${lcdomainName}->_getProperties());
    die;

}
elseif ($datos->action == 'create')
{
    <k:format.trim>
    <f:for each="{domainObject.anyRelationProperties}" as="relation"><f:if condition="{relation.dataType} == 'ManyToOneRelation'">
    ${relation.name} = NULL;
    if(isset($datos->{relation.name}) ) {
        /** @var {relation.foreignModel.fullyQualifiedDomainRepositoryClassName} ${lcdomainRepositoryClassName} */
        $<k:format.lowercaseFirst>{relation.foreignModel.domainRepositoryClassName}</k:format.lowercaseFirst> = $objectManager->get('{relation.foreignModel.qualifiedDomainRepositoryClassName}');
        /** @var {relation.foreignModel.fullQualifiedClassName} ${relation.name} */
        ${relation.name} = $<k:format.lowercaseFirst>{relation.foreignModel.domainRepositoryClassName}</k:format.lowercaseFirst>->findByUid($datos->{relation.name});
    }
    </f:if>
    <f:if condition="{relation.dataType} == 'ZeroToOneRelation'">
    ${relation.name} = NULL;
    if(isset($datos->{relation.name}) ) {
        /** @var {relation.foreignModel.fullyQualifiedDomainRepositoryClassName} ${lcdomainRepositoryClassName} */
        $<k:format.lowercaseFirst>{relation.foreignModel.domainRepositoryClassName}</k:format.lowercaseFirst> = $objectManager->get('{relation.foreignModel.qualifiedDomainRepositoryClassName}');
        /** @var {relation.foreignModel.fullQualifiedClassName} ${relation.name} */
        ${relation.name} = $<k:format.lowercaseFirst>{relation.foreignModel.domainRepositoryClassName}</k:format.lowercaseFirst>->findByUid($datos->{relation.name});
    }
    </f:if></f:for>
    </k:format.trim>

    /** @var {domainObject.fullQualifiedClassName} ${lcdomainName} */
    ${lcdomainName} = $objectManager->get('{domainObject.qualifiedClassName}');
    ${lcdomainName}->setProperties(
        <k:format.removeMultipleNewlines>
        <f:for each="{domainObject.properties}" as="property" iteration="iterator">
        <k:switch expression="{property.dataType}">
            <k:case value="AnyToManyRelation">NULL/* es una relacion distinta a 1:1 o N:1 */</k:case>
            <k:case value="ManyToManyRelation">NULL/* es una relacion distinta a 1:1 o N:1 */</k:case>
            <k:case value="ManyToOneRelation">${property.name}</k:case>
            <k:case value="ZeroToManyRelation">NULL/* es una relacion distinta a 1:1 o N:1 */</k:case>
            <k:case value="ZeroToOneRelation">${property.name}</k:case>
            <k:case value="DateProperty">$eidUtil->transformDate($datos->{property.name})</k:case>
            <k:case value="DateTimeProperty">$eidUtil->transformDate($datos->{property.name})</k:case>
            <k:case  default="TRUE">$datos->{property.name}</k:case>
        </k:switch><f:if condition="{iterator.cycle} != {iterator.total}">,</f:if>
        </f:for>
        </k:format.removeMultipleNewlines>
    );

    ${lcdomainRepositoryClassName}->add(${lcdomainName});
    $persistenceManagerInterface->persistAll();
    $eidUtil->JSendResponse('success', ${lcdomainName}->getUid() );

}
elseif ($datos->action == 'update')
{
    if( !isset($datos->uid) ) {
        $eidUtil->JSendResponse('fail','no hay uid');
        die;
    }

    /** @var {domainObject.fullQualifiedClassName} ${lcdomainName} */
    ${lcdomainName} = ${lcdomainRepositoryClassName}->findByUid($datos->uid);
    if(${lcdomainName} == NULL){
        $eidUtil->JSendResponse('fail',$datos->uid.' no encontrado');
        die;
    }

    <k:format.trim>
    <f:for each="{domainObject.anyRelationProperties}" as="relation"><f:if condition="{relation.dataType} == 'ManyToOneRelation'">
    ${relation.name} = NULL;
    if(isset($datos->{relation.name}) ) {
        /** @var {relation.foreignModel.fullyQualifiedDomainRepositoryClassName} ${lcdomainRepositoryClassName} */
        $<k:format.lowercaseFirst>{relation.foreignModel.domainRepositoryClassName}</k:format.lowercaseFirst> = $objectManager->get('{relation.foreignModel.qualifiedDomainRepositoryClassName}');
        /** @var {relation.foreignModel.fullQualifiedClassName} ${relation.name} */
        ${relation.name} = $<k:format.lowercaseFirst>{relation.foreignModel.domainRepositoryClassName}</k:format.lowercaseFirst>->findByUid($datos->{relation.name});
    }
    </f:if>
    <f:if condition="{relation.dataType} == 'ZeroToOneRelation'">
    ${relation.name} = NULL;
    if(isset($datos->{relation.name}) ) {
        /** @var {relation.foreignModel.fullyQualifiedDomainRepositoryClassName} ${lcdomainRepositoryClassName} */
        $<k:format.lowercaseFirst>{relation.foreignModel.domainRepositoryClassName}</k:format.lowercaseFirst> = $objectManager->get('{relation.foreignModel.qualifiedDomainRepositoryClassName}');
        /** @var {relation.foreignModel.fullQualifiedClassName} ${relation.name} */
        ${relation.name} = $<k:format.lowercaseFirst>{relation.foreignModel.domainRepositoryClassName}</k:format.lowercaseFirst>->findByUid($datos->{relation.name});
    }
    </f:if></f:for>
    </k:format.trim>

    ${lcdomainName}->setProperties(
        <k:format.removeMultipleNewlines>
        <f:for each="{domainObject.properties}" as="property" iteration="iterator">
        <k:switch expression="{property.dataType}">
            <k:case value="AnyToManyRelation">NULL/* es una relacion distinta a 1:1 o N:1 */</k:case>
            <k:case value="ManyToManyRelation">NULL/* es una relacion distinta a 1:1 o N:1 */</k:case>
            <k:case value="ManyToOneRelation">${property.name}</k:case>
            <k:case value="ZeroToManyRelation">NULL/* es una relacion distinta a 1:1 o N:1 */</k:case>
            <k:case value="ZeroToOneRelation">${property.name}</k:case>
            <k:case value="DateProperty">$eidUtil->transformDate($datos->{property.name})</k:case>
            <k:case value="DateTimeProperty">$eidUtil->transformDate($datos->{property.name})</k:case>
            <k:case  default="TRUE">$datos->{property.name}</k:case>
        </k:switch><f:if condition="{iterator.cycle} != {iterator.total}">,</f:if>
        </f:for>
        </k:format.removeMultipleNewlines>
    );

    ${lcdomainRepositoryClassName}->update(${lcdomainName});
    $persistenceManagerInterface->persistAll();
    $eidUtil->JSendResponse('success', ${lcdomainName}->getUid() );

}
elseif ($datos->action == 'remove')
{
    if( !isset($datos->uid) ) {
        $eidUtil->JSendResponse('fail','no hay uid');
        die;
    }

    /** @var {domainObject.fullQualifiedClassName} ${lcdomainName} */
    ${lcdomainName} = ${lcdomainRepositoryClassName}->findByUid($datos->uid);
    if(${lcdomainName} == NULL){
        $eidUtil->JSendResponse('fail',$datos->uid.' no encontrado');
        die;
    }

    ${lcdomainRepositoryClassName}->remove(${lcdomainName});
    $persistenceManagerInterface->persistAll();
    $eidUtil->JSendResponse('success');

}
else
{
    $eidUtil->JSendResponse('fail','no se ha seleccionado opcion');
    die;
}
</f:format.raw>