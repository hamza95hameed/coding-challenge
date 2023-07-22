<template>
    <div>
        <Loading v-if="isloading == true"></Loading>
        <div class="d-flex justify-content-between" v-else>
            <table class="ms-1">
                <td class="align-middle">{{ connection.user.name }}</td>
                <td class="align-middle"> - </td>
                <td class="align-middle">{{ connection.user.email }}</td>
                <td class="align-middle"></td>
            </table>
            <div>
                <button id="accept_request_btn_" class="btn btn-primary me-1" v-on:click="accept(connection.user.id)">Accept</button>
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
            accept(user){
                let data = new FormData();
                data.append('connectionId', user);

                let token = localStorage.getItem("token");

                const config = {
                    headers: { Authorization: `Bearer ${token}` }
                };

                axios.post("/api/accept-connection", data, config)
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