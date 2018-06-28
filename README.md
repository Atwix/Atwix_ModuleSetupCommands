# Atwix_ModuleSetupCommands

There is a need to change Setup Module versions especially when
development is going on. It's not handy to do that directly and much harder
when it comes to production/stagign environments.

The feature provides a safe and fast way to perform changes under Setup Module
Versions.

## Technical Concept

The module adds the following CLI commands:

### bin/magento atwix:setup-module:set

The command configures setup versions of given module.

Example:

```bin/magento atwix:setup-module:set Temando_Shipping 1.1.0 1.1.0```

### bin/magento atwix:setup-module:show

The command shows the current state of setup versions of given modules.

Example:

```bin/magento atwix:setup-module:set Temando_Shipping Shopial_Facebook```

### bin/magento atwix:setup-module:show

The command shows the current state of setup versions of given modules.

Example:

```bin/magento atwix:setup-module:set Temando_Shipping Shopial_Facebook```

### bin/magento atwix:setup-module:fix-versions

The command mends inconsistency between version of module in module.xml and
one in setup module table.
Something this occurs when GIT branch is changing or development is being performed on modules.

Examples:

```bin/magento atwix:setup-module:fix-versions```

```bin/magento atwix:setup-module:fix-versions Temando_Shipping Shopial_Facebook```
