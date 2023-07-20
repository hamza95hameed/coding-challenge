<template>
    <div class="container">
        <div class="row justify-content-center mt-5">
            <div class="col-12">
                <div class="card shadow text-white bg-dark">
                    <div class="card-header">Coding Challenge - Network connections</div>
                    <div class="card-body">
                        <div class="btn-group w-100 mb-3" role="group" aria-label="Basic radio toggle button group">
                            <button type="button" class="btn btn-outline-primary" :class=" isTabActive == 'suggested' ? 'active' : ''" @click="getSuggestedConnection()">Suggestions ({{ suggested.length }})</button>
                            <button type="button" class="btn btn-outline-primary" :class=" isTabActive == 'sent' ? 'active' : ''" @click="getSendConnections()">Sent Requests ({{ sentRequests.length }})</button>
                            <button type="button" class="btn btn-outline-primary" :class=" isTabActive == 'received' ? 'active' : ''" @click="getSuggestedConnection()">Received  Requests()</button>
                            <button type="button" class="btn btn-outline-primary" :class=" isTabActive == 'connections' ? 'active' : ''" @click="getSuggestedConnection()">Connections ()</button>                          
                        </div>
                        <hr>
                        <div class="my-2 shadow  text-white bg-dark p-1" v-show="isTabActive == 'suggested'" v-for="suggest in suggested" :key="suggest.id">
                            <SuggestedConnnection @reRenderSuggestion="getSuggestedConnection" :connection="suggest"></SuggestedConnnection>
                        </div>
                        <div class="my-2 shadow  text-white bg-dark p-1" v-show="isTabActive == 'sent'" v-for="sent in sentRequests" :key="sent.id">
                            <SentConnnection @reRenderSuggestion="getSuggestedConnection" @reRenderSendConnection="getSendConnections" :connection="sent"></SentConnnection>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import SuggestedConnnection from './SuggestedConnnection.vue';
import SentConnnection from './SentConnnection.vue';

export default {
    name: "Index",
    components: { 
		SuggestedConnnection,
        SentConnnection
	},
    data() {
        return {
            suggested: [],
            sentRequests: [],
            isTabActive: "",
        };
    },
    methods: {
        getSuggestedConnection() {
            let token = localStorage.getItem("token");
            const config = {
                headers: { Authorization: `Bearer ${token}` }
            };
            axios.get("/api/users", config)
                .then(response => {
                    this.isTabActive = "suggested";
                    this.suggested = response.data;
                })
                .catch(function (response) {
                    console.log(response);
                });
        },
        getSendConnections() {
            let token = localStorage.getItem("token");
            const config = {
                headers: { Authorization: `Bearer ${token}` }
            };
            axios.get("/api/sent-connections", config)
                .then(response => {
                    this.isTabActive = "sent";
                    this.sentRequests = response.data;
                })
                .catch(function (response) {
                    console.log(response);
                });
        }
    },
    mounted() {
        this.getSuggestedConnection();
        this.getSendConnections();
    },
}
</script>