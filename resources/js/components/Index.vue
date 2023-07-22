<template>
    <div class="container">
        <div class="row justify-content-center mt-5">
            <div class="col-12">
                <div class="card shadow text-white bg-dark">
                    <div class="card-header">Coding Challenge - Network connections</div>
                    <div class="card-body">
                        <div class="btn-group w-100 mb-3" role="group" aria-label="Basic radio toggle button group">
                            <button type="button" class="btn btn-outline-primary" :class=" isTabActive == 'suggested' ? 'active' : ''" @click="getSuggestedConnection()">Suggestions ({{ count.suggestedUsersCount }})</button>
                            <button type="button" class="btn btn-outline-primary" :class=" isTabActive == 'sent' ? 'active' : ''" @click="getSendConnections()">Sent Requests ({{ count.sentConnectionsCount }})</button>
                            <button type="button" class="btn btn-outline-primary" :class=" isTabActive == 'received' ? 'active' : ''" @click="getReceivedConnections()">Received  Requests({{ count.receivedConnectionsCount }})</button>
                            <button type="button" class="btn btn-outline-primary" :class=" isTabActive == 'connections' ? 'active' : ''" @click="getConnections()">Connections ({{ count.connectedUsersCount }})</button>                          
                        </div>
                        <hr>
                        
                        <div class="my-2 shadow  text-white bg-dark p-1" v-show="isTabActive == 'suggested'" v-for="suggest in suggested" :key="suggest.id">
                            <SuggestedConnnection @reRenderCount="getCount" @reRenderData="getSuggestedConnection" :connection="suggest" :isloading="isloading"></SuggestedConnnection>
                        </div>

                        <div class="my-2 shadow  text-white bg-dark p-1" v-show="isTabActive == 'sent'" v-for="sent in sentRequests" :key="sent.id">
                            <SentConnnection @reRenderCount="getCount" @reRenderData="getSendConnections" :connection="sent" :isloading="isloading"></SentConnnection>
                        </div>

                        <div class="my-2 shadow  text-white bg-dark p-1" v-show="isTabActive == 'received'" v-for="received in receivedRequests" :key="received.id">
                            <ReceivedConnnection @reRenderCount="getCount" @reRenderData="getReceivedConnections" :connection="received" :isloading="isloading"></ReceivedConnnection>
                        </div>

                        <div class="my-2 shadow  text-white bg-dark p-1" v-show="isTabActive == 'connections'" v-for="friend in friends" :key="friend.id">
                            <ConnectedConnections @reRenderCount="getCount" @reRenderData="getConnections" :connection="friend" :isloading="isloading"></ConnectedConnections>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import SuggestedConnnection from './SuggestedConnnection.vue';
import SentConnnection      from './SentConnnection.vue';
import ReceivedConnnection  from './ReceivedConnnection.vue';
import ConnectedConnections from './ConnectedConnections.vue';

const token  = localStorage.getItem("token");
const config = {
    headers: { Authorization: `Bearer ${token}` }
};
export default {
    
    name: "Index",
    components: { 
		SuggestedConnnection,
        SentConnnection,
        ReceivedConnnection,
        ConnectedConnections,
	},
    data() {
        return {
            suggested: [],
            sentRequests: [],
            receivedRequests: [],
            friends: [],
            count:[],
            isTabActive: "",
            isloading: true
        };
    },
    methods: {
        getSuggestedConnection() {
            axios.get(`/api/users`, config)
                .then(response => {
                    this.isTabActive   = "suggested";
                    this.suggested     = response.data;    
                    this.isloading     = false;
                })
                .catch(function (response) {
                    console.log(response);
                });
        },
        getSendConnections() {
            axios.get(`/api/sent-connections`, config)
                .then(response => {
                    this.isTabActive      = "sent";
                    this.sentRequests     = response.data;   
                    this.isloading        = false;
                })
                .catch(function (response) {
                    console.log(response);
                });
        },
        getReceivedConnections() {
            axios.get(`/api/received-connections`, config)
                .then(response => {
                    this.isTabActive          = "received";
                    this.receivedRequests     = response.data;      
                    this.isloading            = false;
                })
                .catch(function (response) {
                    console.log(response);
                });
        },
        getConnections() {
            axios.get(`/api/connected-connections`, config)
                .then(response => {
                    this.isTabActive = "connections";
                    this.friends     = response.data; 
                    this.isloading   = false;
                })
                .catch(function (response) {
                    console.log(response);
                });
        },
        getCount(){
            axios.get("/api/get-count", config)
                .then(response => {
                    this.count = response.data;
                })
                .catch(function (response) {
                    console.log(response);
                });
        }
    },
    mounted() {
        this.getSuggestedConnection();
        this.getCount();
    },
}
</script>