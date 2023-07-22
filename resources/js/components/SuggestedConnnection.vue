<template>
        <div>
            <Loading v-if="isloading == true"></Loading>
            <div class="d-flex justify-content-between" v-else>
                <table class="ms-1">
                    <td class="align-middle">{{ connection.name}}</td>
                    <td class="align-middle"> - </td>
                    <td class="align-middle">{{ connection.email}}</td>
                    <td class="align-middle"> </td>
                </table>
                <div>
                <button id="create_request_btn_" type="button" v-on:click="connectUser(connection.id)" class="btn btn-primary me-1">Connect</button>
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
            connectUser(user){
                let data = new FormData();
                data.append('connectionId', user);

                let token = localStorage.getItem("token");

                const config = {
                    headers: { Authorization: `Bearer ${token}` }
                };

                axios.post("/api/users", data, config)
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