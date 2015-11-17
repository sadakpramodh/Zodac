import sys
import platform

def application(environ, start_response):
    start_response(b'200 OK', [(b'Content-Type', b'text/html')])
    with open ("hostingstart-python.html", "r") as hostingstart_file:
        hosting = hostingstart_file.read()
        yield hosting.encode('utf8').replace(b'PYTHON_VERSION', platform.python_version().encode('utf8'))