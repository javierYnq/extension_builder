{namespace k=EBT\ExtensionBuilder\ViewHelpers}<f:for each="{extension.plugins}" as="plugin">
plugin.{extension.shortExtensionKey}_{plugin.key} {
    view {
        templateRootPaths.0 = EXT:{extension.extensionKey}/Resources/Private/Templates/
        templateRootPaths.1 = <k:curlyBrackets>$plugin.{extension.shortExtensionKey}_{plugin.key}.view.templateRootPath</k:curlyBrackets>
        partialRootPaths.0 = EXT:{extension.extensionKey}/Resources/Private/Partials/
        partialRootPaths.1 = <k:curlyBrackets>$plugin.{extension.shortExtensionKey}_{plugin.key}.view.partialRootPath</k:curlyBrackets>
        layoutRootPaths.0 = EXT:{extension.extensionKey}/Resources/Private/Layouts/
        layoutRootPaths.1 = <k:curlyBrackets>$plugin.{extension.shortExtensionKey}_{plugin.key}.view.layoutRootPath</k:curlyBrackets>
    }
    persistence {
        storagePid = <k:curlyBrackets>$plugin.{extension.shortExtensionKey}_{plugin.key}.persistence.storagePid</k:curlyBrackets>
        #recursive = 1
    }
    features {
        #skipDefaultArguments = 1
        # if set to 1, the enable fields are ignored in BE context
        ignoreAllEnableFieldsInBe = 0
        # Should be on by default, but can be disabled if all action in the plugin are uncached
        requireCHashArgumentForActionArguments = 0
    }
    mvc {
        #callDefaultActionIfActionCantBeResolved = 1
    }
}
</f:for>

config.tx_extbase.view.widget.TYPO3\CMS\Fluid\ViewHelpers\Widget\PaginateViewHelper.templateRootPath = EXT:{extension.extensionKey}/Resources/Private/Layouts/

page.includeJSFooter{
    # cat=plugin.{extension.shortExtensionKey}/javascript; type=string; label=Javascript main
    dropzone = EXT:{extension.extensionKey}/Resources/Public/Javascript/dropzone.js
    # cat=plugin.{extension.shortExtensionKey}/javascript; type=string; label=Javascript main
    select2Min = EXT:{extension.extensionKey}/Resources/Public/Javascript/select2.min.js
    # cat=plugin.{extension.shortExtensionKey}/javascript; type=string; label=Javascript main
    select2LocaleEs = EXT:{extension.extensionKey}/Resources/Public/Javascript/select2_locale_es.js
    # cat=plugin.{extension.shortExtensionKey}/javascript; type=string; label=Javascript main
    bootstrapDatepicker = EXT:{extension.extensionKey}/Resources/Public/Javascript/bootstrap-datepicker.js
    # cat=plugin.{extension.shortExtensionKey}/javascript; type=string; label=Javascript main
    bootstrapDatepickerEs = EXT:{extension.extensionKey}/Resources/Public/Javascript/bootstrap-datepicker.es.js
    # cat=plugin.{extension.shortExtensionKey}/javascript; type=string; label=Javascript main
    bootstrapSortable = EXT:{extension.extensionKey}/Resources/Public/Javascript/bootstrap-sortable.js
    # cat=plugin.{extension.shortExtensionKey}/javascript; type=string; label=Javascript main
    moment = EXT:{extension.extensionKey}/Resources/Public/Javascript/moment.min.js
    # cat=plugin.{extension.shortExtensionKey}/javascript; type=string; label=Javascript main
    mainFile = EXT:{extension.extensionKey}/Resources/Public/Javascript/main.js
    mainFile.disableCompression = 1
    mainFile.excludeFromConcatenation = 1
}

page.includeCSS{
    # cat=plugin.{extension.shortExtensionKey}/javascript; type=string; label=Javascript main
    select2 = EXT:{extension.extensionKey}/Resources/Public/Css/select2.css
    # cat=plugin.{extension.shortExtensionKey}/javascript; type=string; label=Javascript main
    select2Bootstrap = EXT:{extension.extensionKey}/Resources/Public/Css/select2-bootstrap.css
    # cat=plugin.{extension.shortExtensionKey}/javascript; type=string; label=Javascript main
    bootstrapDatepickerCss = EXT:{extension.extensionKey}/Resources/Public/Css/datepicker3.css
    # cat=plugin.{extension.shortExtensionKey}/javascript; type=string; label=Javascript main
    bootstrapSortableCss = EXT:{extension.extensionKey}/Resources/Public/Css/bootstrap-sortable.css
    # cat=plugin.{extension.shortExtensionKey}/javascript; type=string; label=Javascript main
    #dropzone = EXT:{extension.extensionKey}/Resources/Public/Css/dropzone.css
    # cat=plugin.{extension.shortExtensionKey}/javascript; type=string; label=Javascript main
    #basic = EXT:{extension.extensionKey}/Resources/Public/Css/basic.css
    # cat=plugin.{extension.shortExtensionKey}/javascript; type=string; label=Javascript main
    dropzoneStyle = EXT:{extension.extensionKey}/Resources/Public/Css/dropzoneStyle.css
}

<f:if condition="{extension.plugins}">

# these classes are only used in auto-generated templates
plugin.{extension.shortExtensionKey}._CSS_DEFAULT_STYLE (
    textarea.f3-form-error {
        background-color:#FF9F9F;
        border: 1px #FF0000 solid;
    }

    input.f3-form-error {
        background-color:#FF9F9F;
        border: 1px #FF0000 solid;
    }

    .typo3-messages .message-error {
        color:red;
    }

    .typo3-messages .message-ok {
        color:green;
    }

    .{extension.cssClassName} th{
        text-align: center;
    }

    .{extension.cssClassName} td{
        text-align: center;
    }
)
</f:if>

<f:for each="{extension.backendModules}" as="backendModule">
# Module configuration
module.{extension.shortExtensionKey}_{backendModule.mainModule}_{extension.unprefixedShortExtensionKey}{backendModule.key} {
    persistence {
        storagePid = <k:curlyBrackets>$module.{extension.shortExtensionKey}_{backendModule.key}.persistence.storagePid</k:curlyBrackets>
    }
    view {
        templateRootPaths.0 = EXT:{extension.extensionKey}/Resources/Private/Backend/Templates/
        templateRootPaths.1 = <k:curlyBrackets>$module.{extension.shortExtensionKey}_{backendModule.key}.view.templateRootPath</k:curlyBrackets>
        partialRootPaths.0 = EXT:{extension.extensionKey}/Resources/Private/Backend/Partials/
        partialRootPaths.1 = <k:curlyBrackets>$module.{extension.shortExtensionKey}_{backendModule.key}.view.partialRootPath</k:curlyBrackets>
        layoutRootPaths.0 = EXT:{extension.extensionKey}/Resources/Private/Backend/Layouts/
        layoutRootPaths.1 = <k:curlyBrackets>$module.{extension.shortExtensionKey}_{backendModule.key}.view.layoutRootPath</k:curlyBrackets>
    }
}
</f:for>
