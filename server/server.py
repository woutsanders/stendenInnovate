import json
import sys
import signal
import time
import tornado.web
import tornado.websocket
import tornado.httpserver
from collections import namedtuple
from tornado.tcpserver import TCPServer
from tornado.ioloop import IOLoop


# Define some global variables.
global pMsg


# Initialize server app...
def main():
    global pMsg;
    pMsg = ""
    
    signal.signal(signal.SIGINT, exitApplication)
    signal.signal(signal.SIGTERM, exitApplication)
    
    ws_app = Application()
    ws_srv = tornado.httpserver.HTTPServer(ws_app)
    ws_srv.bind(9989)
    ws_srv.start()
    print "[*] WebSockets server [*] UP"
    
    u_srv = UnityServer()
    u_srv.listen(9990)
    print "[*] Unity server      [*] UP"
    
    IOLoop.instance().start()
    IOLoop.instance().close()


# Helper to properly exit the servers.
def exitApplication(sig, frame):
    IOLoop.instance().add_callback(IOLoop.instance().stop)


# The Unity server
class UnityServer(TCPServer):
    
    def handle_stream(self, stream, address):
        global pMsg
        self._stream = stream
        self._handle_read(pMsg)
        #self._read_line()
    
    #def _read_line(self):
        #self._stream.read_until('\n', self._handle_read)
    
    def _handle_read(self, data):
        print "UnityServer: Writing back: " + str(data)
        self._stream.write(data)
        #self._read_line()


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
        direction = msgDecoded.moveTo
        pMsg = str(userID) + ":" + str(direction)
        print "WebSocketHandler: Received: " + pMsg
        self.write_message(u"OK")
    
    def on_close(self):
        pass
    
    # Disable check-origin header...
    def check_origin(self, origin):
        return True
    
    def jsonDecode(self, message):
        return json.loads(message, object_hook=lambda d: namedtuple('X', d.keys())(*d.values()))

# Handle main function on startup...
if __name__ == '__main__':
    main()