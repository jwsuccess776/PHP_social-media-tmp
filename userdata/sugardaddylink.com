--- 
customlog: 
  - 
    format: combined
    target: /etc/apache2/logs/domlogs/sugardaddylink.com
  - 
    format: "\"%{%s}t %I .\\n%{%s}t %O .\""
    target: /etc/apache2/logs/domlogs/sugardaddylink.com-bytes_log
documentroot: /home/knowled5/public_html
group: knowled5
hascgi: 1
homedir: /home/knowled5
ip: 184.154.46.57
owner: root
phpopenbasedirprotect: 1
port: 80
scriptalias: 
  - 
    path: /home/knowled5/public_html/cgi-bin
    url: /cgi-bin/
serveradmin: webmaster@sugardaddylink.com
serveralias: mail.sugardaddylink.com www.sugardaddylink.com
servername: sugardaddylink.com
usecanonicalname: 'Off'
user: knowled5
userdirprotect: ''
