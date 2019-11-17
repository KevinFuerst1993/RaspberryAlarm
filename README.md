# RaspberryAlarm
Project instructions:
http://www.raspberry-pi-geek.com/Archive/2015/12/Using-the-Raspberry-Pi-as-a-smart-alarm-clock

Exception notes:
- Sound card: 
  http://www.knight-of-pi.org/raspberry-pi-enable-an-usb-sound-card-for-raspbian-jessie/
  in file /usr/share/alsa/alsa.conf, change this: 
  defaults.ctl.card 1
  defaults.pcm.card 1
  
-  Installing Services 
    execute this first: sudo apt update && sudo apt upgrade -y
    Install mysql, apache und php acording to this page: https://randomnerdtutorials.com/raspberry-pi-apache-mysql-php-lamp-server/
    $ sudo apt-get install apache2 php libapache2-mod-php 
    $ sudo mysql -u root -p < createDB.sql (sudo is neccessary!)
    
- cronTab
  open with sudo
      

