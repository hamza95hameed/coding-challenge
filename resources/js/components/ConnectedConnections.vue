<template>
    <div>
        <Loading v-if="isloading == true"></Loading>
        <div class="d-flex justify-content-between" v-else>
            <table class="ms-1">
                <td class="align-middle">{{ connection.name }}</td>
                <td class="align-middle"> - </td>
                <td class="align-middle">{{ connection.email }}</td>
                <td class="align-middle"></td>
            </table>
            <div>
                <button id="create_request_btn_" class="btn btn-danger me-1" v-on:click="remove(connection.id)">Remove Connection</button>
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
        methods: {
            remove(user) {
                let data = new FormData();
                data.append('connectionId', user);

                const token  = localStorage.getItem("token");
                const config = {
                    headers: { Authorization: `Bearer ${token}` }
                };

                axios.post("/api/remove-connection", data, config)
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