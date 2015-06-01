import tornado.web
import tornado.websocket
import tornado.httpserver
import tornado.ioloop
import json
import SocketServer
import socket
import sys
import threading
import signal
import time
from collections import namedtuple

# Define some global variables.
global pMsg
global t1
global allowSend
allowSend = True

# Initialize server app...
def main():
    ws_app = Application()
    server = tornado.httpserver.HTTPServer(ws_app)
    server.bind(9989)
    server.start()
    print "[*] WebSockets server [*] UP"
    
    server1 = SocketServer.TCPServer(("", 9990), HandleUnityTCP)
    t1 = threading.Thread(target=server1.serve_forever)
    t1.start()
    print "[*] Unity server      [*] UP"
    tornado.ioloop.IOLoop.instance().start()


# Initializes the WebSockets server.
class Application(tornado.web.Application):
    def __init__(self):
        handlers = [
                    (r'/curvegame', WebSocketHandler)
        ]
        tornado.web.Application.__init__(self, handlers)


# Handles incoming WebSocket requests (event-driven).
class WebSocketHandler(tornado.websocket.WebSocketHandler):
    def open(self):
        pass
 
    def on_message(self, json):
        global pMsg
        global allowSend
        
    	msgDecoded = self.jsonDecode(json)
    	
    	userID = msgDecoded.userId
        direction = msgDecoded.direction
        
        print "Received ID: " + str(userID)
    	print "Movement digit: " + str(direction)
        
        pMsg = str(userID) + ":" + str(direction)
        self.write_message(u"OK")
 
    def on_close(self):
        pass

    # Disable check-origin header...
    def check_origin(self, origin):
        return True 
    
    def jsonDecode(self, message):
    	return json.loads(message, object_hook=lambda d: namedtuple('X', d.keys())(*d.values()))


# Unity TCP Server
class HandleUnityTCP(SocketServer.BaseRequestHandler):
    def handle(self):
        global allowSend
        global pMsg
        
        while True:
            if (allowSend):
                try:
                    self.request.sendall(pMsg + "\r\n")
                    time.sleep(0.003)
                except:
                    print "HandleUnityTCP: not sent"
                    time.sleep(0.005)

# Handle main function on startup...
if __name__ == '__main__':
    main()