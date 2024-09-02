[![Services Health](https://rxlab.montastic.io/badge)](https://rxlab.montastic.io)

# Ramses-Server

The server part of Ramses - Rx Asset Management System

This server is used to sync data across *Ramses Clients*.

- The [Ramses documentation](http://ramses.rxlab.guide) along with installation instructions for the server are available on [ramses.rxlab.guide](http://ramses.rxlab.guide).
- The main [Git repository](https://github.com/RxLaboratory/Ramses) for all *Ramses* components is [here](https://github.com/RxLaboratory/Ramses).
- The [developer documentation and all references](http://ramses.rxlab.io) are available on [ramses.rxlab.io](http://ramses.rxlab.io).

This server is a simple REST API implemantation in *PHP* to store the data in a MySQL Database. See the [developer documentation](http://ramses.rxlab.io) for [the reference of the API](http://ramses.rxlab.guide/dev/server-reference/).

## Python client

We provide a very simple example implementation of a client in Python, available in [py_client](py_client).  
You can use this client to test the server from a terminal.

## Quick reference

This is a quick overview as a reminder, the [developer documentation and all references](http://ramses.rxlab.io) being available on [ramses.rxlab.io](http://ramses.rxlab.io)

### Sync session

1. `https://server.tld/ramses/?ping` says "hi" to the server. This is mandatory to start a session.
1. `https://server.tld/ramses/?login` tells who you are. This is mandatory to be able to sync data, the server will refuse if you're not logged in.  
    You can optionnaly get the list of projects you're assigned to with ``https://server.tld/ramses/?getProjects`,  
    then set the current project with ``https://server.tld/ramses/?setCurrentProject`
3. `https://server.tld/ramses/?sync` starts the sync session.
4. `https://server.tld/ramses/?push` to push modified rows (or an empty list to download all table data)
5. `https://server.tld/ramses/?fetch` to get some information, including the number of tables and rows available to pull
6. `https://server.tld/ramses/?pull` to pull the updated data from the server
