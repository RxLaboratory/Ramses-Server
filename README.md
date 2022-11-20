[![Services Health](https://rxlab.montastic.io/badge)](https://rxlab.montastic.io)

# Ramses-Server

The server part of Ramses - Rx Asset Management System

This server is used to sync data across *Ramses Clients*.

- The [Ramses documentation](http://ramses.rxlab.guide) along with installation instructions for the server are available on [ramses.rxlab.guide](http://ramses.rxlab.guide).
- The main [Git repository](https://github.com/RxLaboratory/Ramses) for all *Ramses* components is [here](https://github.com/RxLaboratory/Ramses).
- The [developer documentation and all references](http://ramses.rxlab.io) are available on [ramses.rxlab.io](http://ramses.rxlab.io).

This server is a simple REST API implemantation in *PHP* to store the data in a MySQL Database. See the [developer documentation](http://ramses.rxlab.io) for [the reference of the API](http://ramses.rxlab.guide/dev/server-reference/).

## Quick reference

This is a quick overview as a reminder, the [developer documentation and all references](http://ramses.rxlab.io) being available on [ramses.rxlab.io](http://ramses.rxlab.io)

### Sync session

1. `https://server.tld/ramses/?sync` starts the sync session.
2. `https://server.tld/ramses/?push` to push modified rows (or an empty list to download all table data)
3. `https://server.tld/ramses/?fetch` to get some information, including the number of tables and rows available to pull
4. `https://server.tld/ramses/?pull` to pull the updated data from the server
