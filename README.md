# LonelyPullRequests.com

## Contributing

### Set up the vagrant instance (Linux and OSX users only)

Ensure that [VirtualBox](https://www.virtualbox.org), [Vagrant](http://www.vagrantup.com), and [Ansible](http://www.ansible.com) are installed.

1. `git clone git@github.com:LonelyPullRequestsHQ/lonelypullrequests.com.git --recursive`
2. run `vagrant up`
3. (from within the machine) `composer install` to install composer dependencies

All done! Now you can access the application at [http://lonelypullrequests.192.168.33.10.xip.io/](http://lonelypullrequests.192.168.33.10.xip.io/).
