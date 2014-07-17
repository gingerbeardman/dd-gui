dd-gui
======

##Background
dd is a command line tool to do byte-exact copy.

##Purpose
dd-gui is a simple GUI wrapper to enable you to easily launch dd without resorting to the command line. It will also show progress information throughout the copy.

##Usage
Both source and destination can be either a system device from /dev or a file with the extension .img, for example: /dev/disk4, /files/dd.img

The app has only been tested on Mac OS 10.6.x

##**Warning**
**If you choose the wrong device as the destination, you can erase important data! Proceed with caution.**

##Changelog
2014-07-16: Added sudo to fix permissions issue on Mac OS X 10.9.X Mavericks (0.23)  
2013-11-04: Added sudo to fix permissions issue on Mac OS 10.9 Mavericks (0.22)  
2009-09-17: Added option to list devices (0.21)  
2009-09-16: Added automatic unmounting and mounting of devices, plus more robust determination of source size (0.20)  
2009-09-07: Initial release (0.10)  

##Future versions
I may add support for all dd features, just like [Air Imager](http://air-imager.sourceforge.net/) on Linux.

##Links
[man dd](http://www.freebsd.org/cgi/man.cgi?query=dd&sektion=1) information on the dd command

## License

dd-gui is made available under a [Creative Commons Attribution-Share Alike 3.0 Unported License](http://creativecommons.org/licenses/by-sa/3.0).
