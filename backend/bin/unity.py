import sys
import socket
import sys
import time
import errno


hostaddress="127.0.0.1"
poort=9991

def callargs():
	total = len(sys.argv)
	if (total == 3):
			argument_1_ID=sys.argv[1]
			argument_2_COMMAND=sys.argv[2]
			return (argument_1_ID, argument_2_COMMAND)
	elif(total < 3):
			print "minimaal 2 argumenten nodig"
			print "format: ID COMMAND"
			exit()
	elif(total > 3):
			print "Je mag maar 2 argumenten gebruiken"
			print "format: ID COMMAND"
			exit()

command = callargs()[0] + callargs()[1]

def senddata(bericht):
	HOST, PORT = (hostaddress, poort);
	data = bericht
	sock = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
	try:
		sock.connect((HOST, PORT));
		sock.sendall(data + "\r\n");
		print "Verzonden: " + data;
	finally:
		sock.close();

senddata(str(command));