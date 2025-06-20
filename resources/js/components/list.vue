<template>
    <!-- <Navbar/> -->
    <div>

        <div class=" py-3 px-5 container-fluid">
            <span> <h1>List view 
                <span class="text-muted fs-6 fa fs-italic">
                    (List of all uploaded xml)
                </span>
                </h1>
            </span>
            <div class="row mb-3">
                <div class="col-12 d-inline-flex">
                    <button :class="filterMenu? 'btn btn-dark mx-2':'btn btn-outline-dark mx-2'" @click="toggleFilter">
                    <i class="fa fa-search" aria-hidden="true"></i>
                        Search
                    </button>
                    <button class="btn btn-outline-dark mx-2" @click="router.push('/')">Go to upload</button>
                </div>
            </div>
            <!-- team filter -->
             <div class="row bg-body-secondary row p-4" v-if="filterMenu">
                <div class="col-12 col-md-4 p-2">
                    <label for="country" class="fs-italic p-2">Country<span class="text-danger p-1">*</span></label>
                    <select id="country" class="form-control" v-model="selectedCountry" :disabled="countries.length == 0">
                        <option value="">Select Country</option>
                        <option v-for="country in countries" :value="country">{{ country }}</option>
                    </select>
                </div>
                <div class="col-6 col-md-4 p-2">
                    <label class="fs-italic p-2">Age From 
                        <span class="text-muted d-none d-sm-inline-block">(min : {{ minAge }})</span>
                    </label>
                    <input type="number" class="form-control" v-model="fromAge">
                </div>
                <div class="col-6 col-md-4 p-2">
                    <label class="fs-italic p-2">Age To 
                        <span class="text-muted d-none d-sm-inline-block">(max : {{ maxAge }})</span></label>
                    <input type="number" class="form-control" v-model="toAge">
                </div>
                <div class="col-6 col-md-4 p-2">
                    <label class="fs-italic p-2">From Date</label>
                        <span class="text-muted d-none d-sm-inline-block">(Report generated from date)</span>
                    <input type="text" class="form-control" ref="selectedFromDate">
                </div>
                <div class="col-6 col-md-4 p-2">
                    <label class="fs-italic p-2">To Date</label>
                    <span class="text-muted d-none d-sm-inline-block">(Report generated to date)</span>
                    <input type="text" class="form-control" ref="selectedToDate">
                </div>
                <div class="col-12 col-md p-2">
                    <label class="fs-italic p-2">Player Name</label>
                        <input class="form-control" v-model="selectedPlayer" list="datalistOptions" id="playerList" placeholder="Type player name here ..">
                        <datalist id="datalistOptions">
                            <option v-for="player in fullPlayersList" :value="player.playerName"></option>
                        </datalist>
                </div>
                <div class="col-12 my-2 text-center mb-3">
                    <button class="mx-2 btn btn-outline-primary" @click="searchPlayers()">
                        Seach
                    </button>
                    <button class="mx-2 btn btn-outline-primary" @click="resetForm">
                        Reset
                    </button>
                    <button class="btn btn-outline-primary d-none d-sm-inline-block" @click="toggleFilter">
                        Close
                    </button>
                </div>
            </div>
            
            <div class="my-4 overflow-x-scroll row" v-if="selectedCountry !== ''">
                <div class="col-12">
                    <nav style="width: max-content;">
                    <ul class="pagination justify-content-end">
                        <li  :class=" page.url == null ? 'page-item disabled' : 'page-item'" v-for="page,i in pagination.links">
                            <a :class="page.active ? 'page-link active' : 'page-link'" href="#" @click="paginate(page.url)">{{ htmlEntityDecode(page.label) }}</a>
                        </li>
                    </ul>
                    </nav>
                </div>
            </div>
            <div class="my-4 row" v-else>
                <h5>Recently added </h5>
            </div>
            <div class="row mt-3" >
                <div class="col-12 col-md-4 p-3"  v-for="player in fullPlayersList" :key="player.id">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-6 d-none d-md-block" >
                                    <div class="card card-height shadow-sm">
                                        <div class="card-title p-2 bg-gray-100">
                                            #IFIF : {{ player.playerId }}
                                        </div>
                                        <div class="card-body m-0" v-if="JSON.parse(player.eventDetails)._attributes.Gender == 'M'">
                                            <img class="d-none d-md-block profile-img" src="../../../public/male.jpg" alt="test" >
                                        </div>
                                        <div class="card-body m-0" v-else>
                                            <img class="d-none d-md-block profile-img" src="../../../public/female.jpg" alt="test" >
                                        </div>
                                          <div class="card-footer text-muted">{{ player.playerTeam }}</div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-12" style="font-size: small;">
                                     <span>{{ player.playerName }}</span>
                                     <span class="text-muted d-inline-block d-sm-none">({{ player.playerTeam }})</span>
                                     <br>
                                    <span>Age : {{ calculateAge(player.playerDob) }}</span>
                                    <br>
                                    <span>DOB : {{ player.playerDob}}</span>
                                    <br>
                                    <span>Game : {{ player.eventGame}}</span>
                                    <br>
                                    <span>{{ player.eventName }}</span>
                                    <br>
                                    <span>{{ player.venueLocation}} {{ player.venueName }}</span>
                                    <br>
                                    <div class="border-1 p-1">
                                        <span>COURSE PERFORMANCE</span>
                                        <select>
                                            <option value="" v-for="course,index in JSON.parse(player.stats).course">
                                                COURSE {{index+1}}({{ course.totalGained }} Gain)
                                            </option>
                                        </select>
                                    </div>
                                    <div class="border-1 p-1">
                                        <span>HOLE PERFORMANCE</span>
                                        <select>
                                            <option value="" v-for="hole,index in JSON.parse(player.stats).hole">
                                                HOLE {{index+1}}({{ hole.driveDistance[0] ? hole.driveDistance[0] : 0 }}m)
                                            </option>
                                        </select>
                                    </div>
                                    <!-- {{ JSON.parse(player.stats).course }} -->
                                    <!-- <button class="btn btn-secondary" @click="showModal(player.stats)">
                                        <i class="fa fa-pie-chart" aria-hidden="true"></i>
                                        COURSE PERFORMANCE</button> -->
                                    <!-- {{ player.stats }} -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
</template>

<script setup>
import { ref,onMounted,watch,computed } from "vue"
import axios from 'axios'
import Swal from 'sweetalert2'

import { useRoute,useRouter } from "vue-router"

import flatpickr from 'flatpickr'
import 'flatpickr/dist/flatpickr.min.css'

import BarChart from './chart.vue'




const router = useRouter()
const route = useRoute()


const countries = ref([])
const selectedCountry = ref("")
const minAge = ref(0)
const maxAge = ref(0)

const minDob = ref('')
const maxDob = ref('')


const fromAge = ref('')
const toAge = ref('')



const selectedFromDate = ref('')
const selectedToDate = ref('')


const players = ref([])
const allPlayers = ref({})
const selectedPlayer = ref("")
const pagination = ref({})

const filterMenu = ref(true)

const playerName = ref("")

let fromDatePicker = null;
let toDatePicker = null;


watch(selectedCountry,(newval)=>{

    selectedPlayer.value =""
    fromAge.value = ''
    toAge.value = ''
    if (fromDatePicker) {
        fromDatePicker.clear(); // This clears the date field
    }
    if (toDatePicker) {
        toDatePicker.clear(); // This clears the date field
    }
    allPlayers.value = {}
    getAllPlayers()
})

onMounted(() => {

    fromDatePicker = flatpickr(selectedFromDate.value, { dateFormat: 'd-m-Y' })
    toDatePicker = flatpickr(selectedToDate.value, { dateFormat: 'd-m-Y' })


    getAllPlayers()
    getAllTeams()
    getMaxMin()
    
})

const showToast = (msg,icon,time = 2000) => {
  Swal.fire({
    toast: true,
    position: 'top-end',
    icon: icon,
    title: msg,
    showConfirmButton: false,
    timer: time,
    timerProgressBar: true,
  })
}


const toggleFilter = () =>{
    filterMenu.value = !filterMenu.value

    if(!filterMenu.value)
    { 
        getAllPlayers()
        resetForm()
    }
    
}


const htmlEntityDecode =(str) =>{
  const textarea = document.createElement('textarea');
  textarea.innerHTML = str;
  return textarea.value;
}

const getAllTeams = async() => {
    const response = await axios.get('/api/allteams').then((response)=>{
        countries.value = response.data.data
        if(countries.value.length == 0)
        {
            showToast('No countries found', 'error')
        }
    }).catch((error)=>{
                showToast('Something went wrong while fetching countries', 'error')
    })
}


const paginate = (url) => {
    searchPlayers(url)
}

const getAllPlayers = async(url = '/api/getPlayers?page=1')=>{
    
    const response = await axios.get(url).then((response)=>{
        const data = response.data.data        
        pagination.value = JSON.parse(response.data.data)
        allPlayers.value = JSON.parse(data).data
        if(allPlayers.value.length == 0)
        {
            showToast('No players found', 'error')
        }
        
      
        // allPlayers.value = JSON.parse(response.data.data)
    }).catch((error)=>{
        showToast('Something went wrong while fetching players', 'error')
    })
}




const resetForm = () => {
    selectedPlayer.value =""
    selectedCountry.value = ""
    fromAge.value = ''
    toAge.value = ''
    if (fromDatePicker) {
        fromDatePicker.clear(); // This clears the date field
    }
    if (toDatePicker) {
        toDatePicker.clear(); // This clears the date field
    }
    allPlayers.value = {}
    getAllPlayers()
}




const fullPlayersList = computed(()=>{
    return allPlayers.value
})


const getMaxMin = async(country = '') =>{
    let params = country ? '?country='+country : ''
    const response = axios.get('/api/getMaxMinDob'+params).then((response)=>{
        const data = response.data.data
        minDob.value = data.min
        maxDob.value = data.max
        maxAge.value = calculateAge(data.min)
        minAge.value = calculateAge(data.max)
    }).catch((error)=>{
        console.log(error);
        showToast('Something went wrong while max min', 'error')
    })
}



const  calculateAge = (date) =>{
    const birthDate = new Date(date);
    const today = new Date();
    return today.getFullYear() - birthDate.getFullYear();
    
}

const searchPlayers = async(url = '/api/getPlayerDataByFilter') => {
    
    
    if(selectedCountry.value === '')
    {
        showToast('Please choose a country', 'error')
        return;
    }

    
    
    const fromDate = selectedFromDate.value.value.split('-').reverse().join('-');
    
    const toDate = selectedToDate.value.value.split('-').reverse().join('-');
    
    if(selectedFromDate.value.value !== '' && selectedToDate.value.value !== '')
    {
        
        let from = new Date(fromDate)
        let to = new Date(toDate)
        
        if (from > to) {
            showToast('Invalid date entry', 'error')
            return;
        }
    }

    if(fromAge.value < toAge.value)
    {
        showToast('Invalid age entry', 'error')
        return 
    }
    else{
        if(fromAge.value === 0 || toAge.value === 0)
        {
            showToast('Invalid age entry', 'error')
            return 
        }
    }

   

    const response = await axios.post(url,{
        playerTeam : selectedCountry.value,
        fromAge : fromAge.value,
        toAge : toAge.value ,
        fromdate : fromDate,
        toDate : toDate,
        playerName : selectedPlayer.value
    }).then((response)=>{
        const data = response.data.data
        pagination.value = response.data.data
        allPlayers.value = data.data
        if(allPlayers.value.length == 0)
        {
            showToast('No players found', 'error')
        }
    }).catch((error)=>{
        console.log(error);
        showToast('Something went wrong while fetching data for filter', 'error')
    })


}





</script>

<style  scoped>
.profile-img
{
    height: 8rem;
}

.card-height{
    height: 16rem;
}
</style>