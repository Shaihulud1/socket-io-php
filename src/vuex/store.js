import Vue from "vue"
import Vuex from "vuex"
import socket from "./modules/socket.js"

Vue.use(Vuex)

export default new Vuex.Store({ modules: { socket } })
