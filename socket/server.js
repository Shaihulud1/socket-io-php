const socket = require("socket.io")
const express = require("express")
const http = require("http")
const https = require("https")
const port = 3003
const winston = require("winston")
const { createLogger, format, transports } = winston
const axios = require("axios")
const querystring = require("querystring")

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

function send2Php(dataSend, callback) {
    dataSend = querystring.stringify(dataSend)
    let options = {
        method: "POST",
        url: "http://127.0.8.8/server/index.php",
        data: dataSend,
        headers: {
            "Content-Type": "application/x-www-form-urlencoded",
            "Content-Length": dataSend.length
        }
    }
    axios(options)
        .then(response => {
            callback(response)
        })
        .catch(error => {
            // console.log(error)
        })
}

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

        socket.on("joinUser", function(data) {
            let dataSend = { method: "userJoin", nickname: data }
            req = send2Php(dataSend, response => {
                io.emit("joinStatus", response.data.result)
            })
        })
        socket.on("isExistID", function(data) {
            let dataSend = { method: "isExistID", id: data }
            req = send2Php(dataSend, response => {
                io.emit("checkUserByID", response.data.result)
            })
        })
        socket.on("getUserDataByID", function(data) {
            let dataSend = { method: "getUserDataByID", id: data }
            req = send2Php(dataSend, response => {
                let reqResponse =
                    response.data.result == "notExist"
                        ? response.data.result
                        : {
                              id: response.data.result.id,
                              name: response.data.result.name
                          }
                io.emit("userData", reqResponse)
                req = send2Php({ method: "getMessages" }, response => {
                    io.emit("loadMessages", response.data)
                    // io.emit("userData", response.data.result)
                })
            })
        })
        socket.on("sendMessage", function(data) {
            let userID = data.id
            let message = data.message
            req = send2Php(
                { method: "sendMessage", id: userID, message: data.message },
                response => {
                    if (response.data != "failed") {
                        let r = Math.random()
                            .toString(36)
                            .substring(7)
                        let resp = {
                            keyID: Date.now() + r,
                            name: response.data.result.user.name,
                            message: response.data.result.message
                        }
                        io.sockets.emit("addMessage", resp)
                    }
                }
            )
        })
        socket.on("getMessages", function(data) {
            req = send2Php({ method: "getMessages" }, response => {
                console.log("tt")
                // io.sockets.emit("loadMessages", response.data)
            })
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
