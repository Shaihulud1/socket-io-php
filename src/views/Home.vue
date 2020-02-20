<template>
    <div class="enter-form-wrap">
        <form action="" class="enter-form" @submit="enterUser">
            <input type="text" v-model="nickname" />
            <input type="submit" value="enter" class="submit-form-btn" />
            <div class="errors">
                <p v-for="(error, key) in formErrors" :key="key">{{ error }}</p>
            </div>
        </form>
    </div>
</template>

<script>
export default {
    name: "Home",
    created: function() {
        let userID = localStorage.getItem("userID")
        if (userID) {
            this.$socket.emit("isExistID", userID)
        }
    },
    sockets: {
        checkUserByID: function(data) {
            if (data == "exist") {
                this.$router.push("messages")
            }
        },
        joinStatus: function(data) {
            if (data == "exist") {
                this.formErrors.push("user already exist")
            } else {
                localStorage.setItem("userID", data)
                this.$router.push("messages")
            }
        }
    },
    methods: {
        enterUser: function(e) {
            e.preventDefault()
            this.formErrors = []
            if (this.nickname.length < 1) {
                this.formErrors.push("Nickname cannot be empty")
            }
            if (this.formErrors.length > 0) {
                return
            }
            this.$socket.emit("joinUser", this.nickname)
        }
    },
    components: {},
    data: () => {
        return {
            nickname: "",
            formErrors: []
        }
    }
}
</script>

<style lang="scss" scoped>
// .enter-form-wrap{

// }
.errors {
    color: red;
}
.enter-form {
    display: grid;
    justify-content: center;
    align-content: center;
    height: 100vh;
    .submit-form-btn {
        margin-top: 5px;
        cursor: pointer;
        // grid-column: controls;
        // grid-row: auto;
    }
}
</style>
