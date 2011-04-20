This Spark seamlessly integrates Doctrine2 ORM in CI2.
Doctrine2 is a powerful ORM based on DataMapper pattern, providing database abstraction, entities-based ORM complete with an entity manager, and a powerful command line tool to automate many tasks.
This spark contains both Doctrine2 library and the CLI tool, but to make it work you'll have to copy some files by hand.

## Installation and configuration

After installing this Spark, create a directory called `proxies` inside your `application/models` folder and make it writable:

    $ cd /path/to/your/ci2/installation
    $ mkdir ./application/models/proxies
    $ chmod a+w ./application/models/proxies

You could use this Spark as any other, by loading it in your controller:

    $this->load->sparks('doctrine2');

    // entity manager is loaded
    $this->doctrine2->em(...);

To make access to entity manager more comfortable, use this:

    $this->load->sparks('doctrine2');
    $this->em = $this->doctrine2->em;

    // your Entity Manager is now quickly available
    $this->em(...);

To enable CLI tool, follow these instructions:

- Copy `tools` folder from Spark directory to your CI2 installation path (if you're using Sparks you should already have a `tools` directory in your installation path):

    $ cd /path/to/your/ci2/installation
    $ cp ./sparks/doctrine2/<CURRENT-VERSION>/tools . -R

- Use the CLI tool from your `tools` directory:

    $ tools/doctrine COMMAND PARAMS

Remember that your model entities will have to be placed in `application/models` directory, while generated proxies will be put in `application/models/proxies` directory.

## Known bugs and roadmap

There is no known bugs so far, but some feature is missing from a "standard" `application/libraries`-based installation.

* Autoloading of Doctrine library doesn't works out of the box. You should manually load the spark every time you need it. You could always do this in your controller constructor, so that you avoid too much code duplication.
* You could think about implementing MY_Controller to extend standard controller and load the Entity Manager in all of the controllers without explicitly loading the spark; I tried but this doesn't work, since MY_Loader (that is how sparks extends CI loading mechanism) is loaded *after* MY_Controller, so `$this->load->spark()` is unavailable at this stage. I also tried with a `post_controller_constructor` hook but: 1) something didn't work and 2) this is an awful workaround and the right way is to getting sparks autoloading functionality to work as intended. This will therefore be addressed in a future release.
* I'd like to have the Entity Manager automatically available in all controllers via `$this->em` if Doctrine2 spark is autoloaded, but I'll have to solve previous point, obviously.

If you could find solutions to above problems, please contact me or send a pull request on GitHub project page:
[https://github.com/stickgrinder/doctrine2-spark](https://github.com/stickgrinder/doctrine2-spark)

## Resources and further readings:

* This work is largely based on WildlyInaccurate tutorial by Joseph Wynn: [http://wildlyinaccurate.com/integrating-doctrine-2-with-codeigniter-2/](http://wildlyinaccurate.com/integrating-doctrine-2-with-codeigniter-2/)
Follow this link to get a deep understanding of what and how.
* If you need a tighter integration bitween Doctrine2 and your CI2 codebase and you're too lazy to set it up by yourself, try this: [http://www.tlswebsolutions.com/codeigniter-2-and-doctrine-2-integration-a-working-setup-doctrineignited/](http://www.tlswebsolutions.com/codeigniter-2-and-doctrine-2-integration-a-working-setup-doctrineignited/)
I didn't try this package but used the checklist on the page to get a super-awesome CI2+D2+UhOh! integration. HTML5 boons in the package are cool also.
* Obviously, if you didn't do that already, head towards wonderful and well-written Doctrine2 ORM Documentation: [http://www.doctrine-project.org/projects/orm/2.0/docs/en](http://www.doctrine-project.org/projects/orm/2.0/docs/en)
With such a doc, you won't have no excuses, so RTFM! >;)

## Contacts and footnotes

This Spark has been "sponsored" by [Agavee Team](http://www.agavee.com), written by Paolo Pustorino (hey, that's me! :) follow @stickgrinder tweets) and it's hosted on GitHub.

Thanks to Joseph Wynn for his clear tutorial, and thanks to all that will find (and fix!) bugs, send me nice twits and offer me some beer&pizza! :)