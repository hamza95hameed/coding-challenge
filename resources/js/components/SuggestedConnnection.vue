<template>
        <div class="d-flex justify-content-between">
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
</template>

<script>
    import axios from 'axios';

    export default{
        props: ["connection"],
        data(){
            return{
            }
        },
        methods:{
            connectUser(user){
                let data = new FormData();
                data.append('connectedUserId', user);

                let token = localStorage.getItem("token");

                const config = {
                    headers: { Authorization: `Bearer ${token}` }
                };

                axios.post("/api/users", data, config)
                    .then(response => {
                        this.$emit("reRenderSuggestion", true);
                    })
                    .catch(function (response) {
                        console.log(response);
                    });
            }
        }        
    }
</script>