<template>
     <div>
        <Loading v-if="isloading == true"></Loading>
        <div class="d-flex justify-content-between" v-else>
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
    </div>
</template>

<script>
    import axios from 'axios';
    import Loading from './Loading.vue';
    export default{
        props: ["connection", "isloading"],
        components: { 
            Loading,
        },
        methods:{
            withdraw(user){
                let data = new FormData();
                data.append('connectionId', user);

                let token = localStorage.getItem("token");

                const config = {
                    headers: { Authorization: `Bearer ${token}` }
                };

                axios.post("/api/withdraw-connection", data, config)
                    .then(response => {
                        this.$emit("reRenderCount", true);
                        this.$emit("reRenderData", true);
                    })
                    .catch(function (response) {
                        console.log(response);
                    });
            }
        }        
    }
</script>