GAS Framework
=============
This is the base of the PHP5 framework we use in-house at BakedCode to build our products. Feel free to fork away, make any changes as you'd like. Please just share as much as you can back with the community.

If you upload the framework and don't choose to rewrite the URL you can access controllers like this:

    http://example.com/Framework/Web/index.php/controller/action/query/string?key=value

> (For example: http://19sites.com/Framework/Web/index.php/index/index).

If you choose to rewrite your URL (like we do on our products), simply set the 'application.rewrited' value to true in your index.php configuration and alter the BASEDIR constant appropriately (it's usually set to '/' on our apps).

> Some more information can be found in /Application/Controllers/IndexController.php.

> A full documentation/wiki will also be available if we decide to actively maintain the open-source release of the framework