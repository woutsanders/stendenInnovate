import SocketServer
import threading
global message;
global schakel
schakel = True
#message = "abcde";		
class MyTCPHandler(SocketServer.BaseRequestHandler):
    def handle(self):
		global message;
		global schakel
		try:
			schakel = True;
			self.data = self.request.recv(1024).strip();
			message = str(self.data)
		finally:
			print (self.client_address[0]) + " stuurt: " + str(self.data);		
class MyTCPHandler_unity(SocketServer.BaseRequestHandler):
	def handle(self):
		global schakel
		global message
		while True:
			try:
				if (schakel == True):
					self.request.sendall(message + "\r\n");
					schakel = False;
					self.request.sendall(message + "\r\n");				
			except(SocketServer):
				print ""

HOST1, PORT1 = "", 9990;
HOST2, PORT2 = "", 9991;
HOST3, PORT3 = "", 9992;

server1 = SocketServer.TCPServer((HOST1, PORT1), MyTCPHandler_unity);
print "[*] Unity server     [*] UP"
server2 = SocketServer.TCPServer((HOST2, PORT2), MyTCPHandler);
print "[*] Socket, speler 1 [*] UP"
server3 = SocketServer.TCPServer((HOST3, PORT3), MyTCPHandler);
print "[*] Socket, speler 2 [*] UP"


t1 = threading.Thread(target=server1.serve_forever)
t1.start()
t2 = threading.Thread(target=server2.serve_forever)
t2.start()
t3 = threading.Thread(target=server3.serve_forever)
t3.start()
