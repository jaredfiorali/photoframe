use ferris_says::say;
use std::io::{stdout, BufWriter};

use std::io::prelude::*;
use std::net::TcpListener;
use std::net::TcpStream;
use std::fs;

fn main() {
    // Keep this for Felix
    // hello();

    // Start our TCP Listener, on port 8080
    let listener = TcpListener::bind("0.0.0.0:8080").unwrap();

    // Loop through all incoming messages
    for stream in listener.incoming() {
        let stream = stream.unwrap();

        // Call a separate function to deal with handling incoming requests
        handle_connection(stream);
    }
}

fn handle_connection(mut stream: TcpStream) {

    // Create a new buffer to handle the incoming request stream
    let mut buffer = [0; 1024];

    // Read the incoming stream, and store it in the buffer
    stream.read(&mut buffer).unwrap();

    // Set a variable containing the expected request header
    let get = b"GET / HTTP/1.1\r\n";

    // Check the request, and route accordingly
    let (status_line, filename) = if buffer.starts_with(get) {
        ("HTTP/1.1 200 OK", "hello.html")
    } else {
        ("HTTP/1.1 404 NOT FOUND", "404.html")
    };

    // Read the desired filename in to a variable for output
    let contents = fs::read_to_string(filename).unwrap();

    // Craft our response, with the output and HTTP status
    let response = format!(
        "{}\r\nContent-Length: {}\r\n\r\n{}",
        status_line,
        contents.len(),
        contents
    );

    // Write the response to the output stream
    stream.write(response.as_bytes()).unwrap();
    stream.flush().unwrap();
}

fn hello() {
    let stdout = stdout();
    let message = String::from("Hello Felix!");
    let width = message.chars().count();

    let mut writer = BufWriter::new(stdout.lock());
    say(message.as_bytes(), width, &mut writer).unwrap();
}
