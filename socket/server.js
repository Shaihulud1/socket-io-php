const socket = require("socket.io")
const express = require("express")
const http = require("http")
const https = require("https")
const port = 3002
const winston = require("winston")
const { createLogger, format, transports } = winston

const logger = createLogger({
    // format: format.combine(format.timestamp(), format.simple()),
    transports: [
        new transports.Console({
            format: format.combine(
                format.timestamp(),
                format.colorize(),
                format.simple()
            )
        })
    ]
})
logger.log({
    level: "info",
    message: "SocketIO > listening on port"
})
// logger.remove(new logger.transports.Console())
// logger.add(new logger.transports.Console({ colorize: true, timestamp: true }))
// logger.info("SocketIO > listening on port")

const app = express()
const httpServer = http.createServer(app).listen(port)

function emit(server) {
    let io = socket.listen(server)
    io.sockets.on("connection", function(socket) {
        logger.log({
            level: "info",
            message: "New connection"
        })
        socket.on("message", function(data) {
            io.emit("message", data)
        })
    })
}

emit(httpServer)

/*for https*/
// const httpsServer = https
//     .createServer(
//         {
//             key: false.readFileSync(""),
//             cert: false.readFileSync("")
//         },
//         app
//     )
//     .listen(3000)
