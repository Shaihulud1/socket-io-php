<template>
    <div id="app">
        <div>
            <h2>You entered as {{ nickname }}</h2>
        </div>
        <div class="messages-form-wrap">
            <div class="messages-wrap">
                <div class="message-item">
                    <p v-for="mess in messages" :key="mess.key">
                        <span class="name">{{ mess.name }}:</span>
                        <span>{{ mess.message }}</span>
                    </p>
                </div>
            </div>
            <form action="send-mess-form" @submit="sendMessage">
                <textarea
                    name=""
                    id=""
                    cols="100"
                    rows="2"
                    v-model="typedMessage"
                ></textarea>
                <input type="submit" value="enter" class="submit-form-btn" />
            </form>
        </div>
    </div>
</template>
<script>
export default {
    name: "Messages",
    created: function() {
        let userID = localStorage.getItem("userID")
        if (userID) {
            this.$socket.emit("getUserDataByID", userID)
            this.$socket.emit("getMessages")
        } else {
            this.$router.push("/")
        }
    },
    sockets: {
        userData: function(data) {
            if (data == "notExist") {
                localStorage.setItem("userID", "")
                this.$router.push("Home")
            } else {
                this.nickname = data.name
            }
        },
        addMessage: function(data) {
            this.messages.push(data)
        }
    },
    methods: {
        sendMessage: function(e) {
            e.preventDefault()
            let id = localStorage.getItem("userID")
            let mess = this.typedMessage
            this.typedMessage = ""
            this.$socket.emit("sendMessage", {
                id: id,
                message: mess
            })
        }
    },
    components: {},
    data: () => {
        return {
            messages: [],
            typedMessage: "",
            nickname: ""
        }
    }
}
</script>
<style lang="scss" scoped>
.messages-wrap {
    display: grid;
    justify-content: center;
    height: 70vh;
    overflow-y: scroll;
    // grid-auto-columns: 20px 80px;
    .name {
        margin-right: 20px;
        font-weight: bold;
    }
}
.send-mess-form {
    display: grid;
    grid-auto-columns: 2px 5px;
}
</style>
