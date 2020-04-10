# Module Nas Tax generator

    ``nas/module-taxgen``

 - [Main Functionalities](#markdown-header-main-functionalities)
 - [Installation](#markdown-header-installation)
 - [Configuration](#markdown-header-configuration)
 - [Specifications](#markdown-header-specifications)
 - [Attributes](#markdown-header-attributes)


## Main Functionalities
generate tax percent for specific product

## Installation

 - Unzip the zip file in `app/code/Nas`
 - Enable the module by running `php bin/magento module:enable Nas_Taxgen`
 - Apply database updates by running `php bin/magento setup:upgrade`\*
 - Flush the cache by running `php bin/magento cache:flush`


## Configuration

 - Nas > Tax Generator


## Specifications

 - Helper
	- Nas\Taxgen\Helper\Data


## Notes
 - Prefix for generated taxe classes is `NAS-TAX-` can be edited in Helper, in future we can add it in configuration page
 - In this version i'm not checked validation for type of input data
 - after each generation please reindex and clear cache
## Author
 - Taoufiq Ait Ali