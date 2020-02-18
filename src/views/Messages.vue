<template>
    <div class="messages-form-wrap">
        <div class="messages-wrap">
            <div class="message-item">
                <p><span class="name">Name:</span><span>Message</span></p>
            </div>
        </div>
        <form action="send-mess-form">
            <textarea name="" id="" cols="100" rows="2"></textarea>
            <input type="submit" value="enter" class="submit-form-btn" />
        </form>
    </div>
</template>
<script>
export default {
    name: "Home",
    sockets: {
        newMessage: function(data) {}
    },
    methods: {},
    components: {},
    data: () => {
        return {}
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
