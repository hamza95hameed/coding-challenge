<template>
    <div class="d-flex justify-content-between">
        <table class="ms-1">
            <td class="align-middle">{{ connection.connected_user.name }}</td>
            <td class="align-middle"> - </td>
            <td class="align-middle">{{ connection.connected_user.email }}</td>
            <td class="align-middle"></td>
        </table>
        <div>
            <button id="cancel_request_btn_" class="btn btn-danger me-1" v-on:click="withdraw(connection.connected_user.id)">Withdraw Request</button>
        </div>
    </div>
</template>

<script>
    import axios from 'axios';

    export default{
        props: ["connection"],
        methods:{
            withdraw(user){
                let data = new FormData();
                data.append('connected_user_id', user);

                let token = localStorage.getItem("token");

                const config = {
                    headers: { Authorization: `Bearer ${token}` }
                };

                axios.post("/api/withdraw-connection", data, config)
                    .then(response => {
                        this.$emit("reRenderSendConnection", true);
                        this.$emit("reRenderSuggestion", true);
                    })
                    .catch(function (response) {
                        console.log(response);
                    });
            }
        }        
    }
</script>