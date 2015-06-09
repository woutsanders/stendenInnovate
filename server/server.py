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
global p1
global p1d
global p2
global p2d
global p3
global p3d
global p4
global p4d

# Initialize server app...
def main():
    global p1d,p2d,p3d,p4d
    p1d = 0
    p2d = 0
    p3d = 0
    p4d = 0
    
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


# Decoding helper for JSON.
def json_decode(str):
    return json.loads(str, object_hook=lambda d: namedtuple('X', d.keys())(*d.values()))


# The Unity server
class UnityServer(TCPServer):
    
    def handle_stream(self, stream, address):
        self._stream = stream
        #self._handle_read(pMsg)
        self._read_line()
    
    def _read_line(self):
        self._stream.read_until('\r\n', self._handle_read)
        
    def _handle_read(self, recvData):
        global p1,p2,p3,p4,p1d,p2d,p3d,p4d
        
        arrHashes = recvData.split(",");
        
        p1 = arrHashes[0];
        p2 = arrHashes[1];
        p3 = arrHashes[2];
        p4 = arrHashes[3];
        
        print "UnityServer recv: " + str(recvData)
        
        implodedData = ",".join([p1 + ":" + p1d, 
                                 p2 + ":" + p2d,
                                 p3 + ":" + p3d,
                                 p4 + ":" + p4d
                                ]);
                                
        print "UnityServer sent: " + str(implodedData)
        
        self._stream.write(implodedData)
        self._read_line()


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
        global p1,p1d,p2,p2d,p3,p3d,p4,p4d
        valid = True;
        msgDecoded = json_decode(json)
        
        userHash = msgDecoded.userHash
        direction = msgDecoded.moveTo
        
        if (p1 == userHash):
            p1d = direction
        elif (p2 == userHash):
            p2d = direction
        elif (p3 == userHash):
            p3d = direction
        elif (p4 == userHash):
            p4d = direction
        else:
            valid = False;
            print "User hash does not exist, perhaps Unity needs to update me?"
        
        print "WebSocketHandler: Received: " + 
        
        if (valid):
            self.write_message(u"OK")
    
    def on_close(self):
        pass
    
    # Disable check-origin header...
    def check_origin(self, origin):
        return True

# Handle main function on startup...
if __name__ == '__main__':
    main()