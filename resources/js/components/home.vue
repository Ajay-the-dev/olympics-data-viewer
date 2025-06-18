<template>
    <div>
        <div class="row">
            <!-- upload area -->
            <div class="col-12 py-3 px-5">
                <div class="card">
                    <div class="card-body text-center">
                        <button class="btn btn-outline-primary" @click="uploadFile">
                            Upload file Here
                        </button>
                        <input ref="fileInput" type="file" accept=".xml" class="hidden" @change="handleFileUpload">
                    </div>
                </div>
            </div>
        </div>
        <div class="row" v-if="false">
            <div class="col-12 py-3 px-5" v-if="fileDetails">
                {{ fileDetails }}
            </div>
            <div class="col-12 py-3 px-5" v-if="eventDetails">
                {{ eventDetails }}
            </div>
            <div class="col-12 py-3 px-5" v-if="LocationDetails">
                {{ LocationDetails }}
            </div>
            <div class="col-12 py-3 px-5" v-if="playerDetails">
                {{ playerDetails }}
            </div>
            <div class="col-12 py-3 px-5" v-if="statisticalData">
                {{ statisticalData }}
            </div>
        </div>
        <div class="row" v-if="xmlData">
            <div class="col-12 py-3 px-5">
                <div class="card inside-height">
                    <div class="card-body row ">
                        <div class="col-12">
                                  <!-- first block -->
                                   <div class="row inside-first-height">
                                        <div class="col-md-3 col-12 card mx-2 tile">
                                            <p class="card-title mt-2">
                                                  <i class="fa fa-clock-o"></i>
                                                Time & Date
                                            </p>
                                            <div class="card-body">
                                                    <p class="text-muted">Date : {{ fileDetails?.Date}}</p>
                                                    <p class="text-muted">Time : {{ parseODFTime(fileDetails?.Time)}}</p>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-12 card mx-2 tile">
                                            <p class="card-title mt-2">
                                                  <i class="fa fa-soccer-ball-o"></i>
                                                Game Details
                                            </p>
                                            <div class="card-body">
                                                    <p class="text-muted">Game : {{ eventDetails?._attributes.DisciplineName}}</p>
                                                    <p class="text-muted">Event : {{ eventDetails?._attributes.EventName}}</p>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-12 card mx-2 tile">
                                            <p class="card-title mt-2">
                                                  <i class="fa fa-map-marker"></i> 
                                                Venue Details
                                            </p>
                                            <div class="card-body">
                                                    <!-- <p class="text-muted">Location : {{ LocationDetails._attributes}}</p> -->
                                                    <p class="text-muted">Location : {{ LocationDetails?._attributes.Location}}</p>
                                                    <p class="text-muted">Venue  : {{ LocationDetails?._attributes.LocationName}}</p>
                                            </div>
                                        </div>
                                        <div class="col-md-2 col-12 card mx-2">
                                            <div class="card-body">
                                               <button class="btn btn-primary w-100 mt-2">Save</button>
                                               <button class="btn btn-primary w-100 mt-2">Cancel</button>
                                            </div>
                                        </div>
                                   </div>
                                   <!-- secondrow -->
                                    <div class="row mt-3 inside-second-height overflow-y-scroll">
                                        <div class="col-md-4 col-12 card mx-2 tile">
                                            <p class="card-title mt-2">
                                                  <i class="fa fa-user"></i>
                                                profile
                                            </p>
                                            <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-6" v-if="playerDetails._attributes.Gender === 'M'">
                                                            <img class="profile-img" src="../../../public/male.jpg" alt="test" >
                                                        </div>
                                                        <div class="col-6" v-else>
                                                            <img class="profile-img" src="../../../public/female.jpg" alt="test" >
                                                        </div>
                                                        <div class="col-6">
                                                            <p class="text-muted">
                                                                IFIF : {{ playerDetails._attributes.IFId }}
                                                            </p>
                                                            <p class="text-muted">
                                                                Name : {{ playerDetails._attributes.GivenName+' '+playerDetails._attributes.FamilyName }}
                                                            </p>
                                                            <p class="text-muted">
                                                                DOB : {{ playerDetails._attributes.BirthDate }}
                                                            </p>
                                                            <p class="text-muted">
                                                                Gender : {{ playerDetails._attributes.Gender === 'M' ? 'MALE' : 'FEMALE' }}
                                                            </p>
                                                            <p class="text-muted">
                                                                Team : {{ playerDetails._attributes.Organisation }}
                                                            </p>
                                                        </div>
                                                    </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-12 card mx-2 tile">
                                            <p class="card-title mt-2">
                                                  <i class="fa fa-history"></i>
                                                Course Statistics
                                            </p>
                                            <div class="card-body" v-if="MainCareerStats">
                                                    <div class="card mt-1" v-for="(st,index) in MainCareerStats">
                                                        <div class="card-body">
                                                            <span  class="badge bg-dark m-1" >
                                                               COURSE {{index+1}}
                                                            </span>
                                                            <span  :class=" parseFloat(st[0]?.totalGained) > 0 ? 'badge bg-dark m-1' : 'badge bg-danger m-1'" >
                                                               GAIN {{st[0].totalGained}}
                                                            </span>
                                                            <span  class="badge bg-dark m-1" >
                                                               PAR {{st[0].par}}
                                                            </span>
                                                            <span  class="badge bg-dark m-1" >
                                                               BOGEY {{st[0].bogey}}
                                                            </span>
                                                            <span  class="badge bg-dark m-1" >
                                                               DOUBLE BOGEY {{st[0].double_bogey}}
                                                            </span>
                                                            <span  class="badge bg-dark m-1" >
                                                               PUTTS {{st[0].putts}}
                                                            </span>
                                                        </div>
                                                    </div>
                                            </div>
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
import { ref,onMounted,computed,watch } from 'vue'
import { xml2json } from 'xml-js'


  const fileInput = ref(null)
  const xmlData = ref('')
  const test = ref(true)
  const MainCareerStats = ref([])

   const uploadFile = ()=>{
        fileInput.value?.click();
  }

  const handleFileUpload  = (event) =>{
    const file = event.target.files[0];

    if(!file)
    {
      return
    }
    let isValid = checkForValidExtension(file.name);

    if(isValid)
    {
        const reader = new FileReader();
        reader.onload = () => {            
            const rawXml = reader.result;
            xmlData.value = jsonConverter(rawXml);
        }
        reader.readAsText(file);              
    }

  } 


 const checkForValidExtension = (fileName) =>{
      
      const isValid = /\.xml$/i.test(fileName);
      return isValid;
  }

  const jsonConverter = (string) => 
  {
    const json = JSON.parse(xml2json(string, { compact: true, spaces: 2 }))
    return json
  }
  
  onMounted(() => {
  console.log('test mounted')
  if(test.value)
  {
        xmlData.value = 'loading';
        // const xml = ''
        const xml = '<?xml version=\"1.0\" encoding=\"utf-8\"?><OdfBody CompetitionCode=\"OG2024\" DocumentCode=\"GLFWSTROKE------------------------\" DocumentSubcode=\"1536522\" DocumentType=\"DT_STATS\" DocumentSubtype=\"CUM\" ResultStatus=\"OFFICIAL\" Version=\"80\" FeedFlag=\"P\" Date=\"2024-08-10\" Time=\"175756695\" LogicalDate=\"2024-08-10\" Source=\"LGNGLF1\"><Competition Gen=\"SOG-2024-GEN-V3.7\" Sport=\"SOG-2024-GLF-3.5\" Codes=\"SOG-2024-CC-V4.10\"><ExtendedInfos><ExtendedInfo Type=\"UI\" Code=\"AFTER_ROUND\" Value=\"4\" /><SportDescription DisciplineName=\"Golf\" EventName=\"Women\'s Individual Stroke Play\" Gender=\"W\" /><VenueDescription Venue=\"LGN\" VenueName=\"Le Golf National\" Location=\"LGN\" LocationName=\"Le Golf National\" /></ExtendedInfos><Stats Code=\"CUM\"><Competitor Code=\"1536522\" Type=\"A\" Organisation=\"IND\" Order=\"29\"><Composition><Athlete Code=\"1536522\" Order=\"29\"><Description GivenName=\"Aditi\" FamilyName=\"Ashok\" Gender=\"F\" Organisation=\"IND\" BirthDate=\"1998-03-29\" IFId=\"4405\" /><StatsItems><StatsItem Type=\"ST\" Code=\"COURSE\" Pos=\"1\"><ExtendedStat Code=\"DRIVE_DIST\" Pos=\"0\" Value=\"208\" /><ExtendedStat Code=\"DRIVE_DIST\" Pos=\"1\" Value=\"227\" /><ExtendedStat Code=\"DTP\" Pos=\"0\" Value=\"10.43\" /><ExtendedStat Code=\"DTP\" Pos=\"1\" Value=\"34\'3 &quot;\" /><ExtendedStat Code=\"FAIRWAY_HITS\" Value=\"20\" /><ExtendedStat Code=\"FAIRWAY_OPP\" Value=\"28\" /><ExtendedStat Code=\"GREENS_NUM\" Value=\"36\" /><ExtendedStat Code=\"GREENS_REG\" Value=\"21\" /><ExtendedStat Code=\"PUTT_DIST\" Pos=\"0\" Value=\"1.67\" /><ExtendedStat Code=\"PUTT_DIST\" Pos=\"1\" Value=\"5\'6 &quot;\" /><ExtendedStat Code=\"SAND_ATT\" Value=\"1\" /><ExtendedStat Code=\"SAND_MADE\" Value=\"0\" /><ExtendedStat Code=\"PUTTS_GAINED\" Value=\"3.037\" /><ExtendedStat Code=\"TEE_GAINED\" Value=\"-1.128\" /><ExtendedStat Code=\"APPROACH_GAINED\" Value=\"0.679\" /><ExtendedStat Code=\"AROUND_GAINED\" Value=\"0.705\" /><ExtendedStat Code=\"TOTAL_GAINED\" Value=\"3.293\" /><ExtendedStat Code=\"SCRAMBLE\" Value=\"40.00\" /><ExtendedStat Code=\"PAR\" Value=\"15\" /><ExtendedStat Code=\"BIRDIE\" Value=\"11\" /><ExtendedStat Code=\"BOGEY\" Value=\"9\" /><ExtendedStat Code=\"EAGLES\" Value=\"0\" /><ExtendedStat Code=\"DBL_BOGEY\" Value=\"1\" /><ExtendedStat Code=\"FAIRWAY_ACC\" Value=\"71.43\" /><ExtendedStat Code=\"PUTTS\" Value=\"53\" /></StatsItem><StatsItem Type=\"ST\" Code=\"COURSE\" Pos=\"2\"><ExtendedStat Code=\"DRIVE_DIST\" Pos=\"0\" Value=\"213\" /><ExtendedStat Code=\"DRIVE_DIST\" Pos=\"1\" Value=\"233\" /><ExtendedStat Code=\"DTP\" Pos=\"0\" Value=\"10.60\" /><ExtendedStat Code=\"DTP\" Pos=\"1\" Value=\"34\'9 &quot;\" /><ExtendedStat Code=\"FAIRWAY_HITS\" Value=\"22\" /><ExtendedStat Code=\"FAIRWAY_OPP\" Value=\"28\" /><ExtendedStat Code=\"GREENS_NUM\" Value=\"36\" /><ExtendedStat Code=\"GREENS_REG\" Value=\"28\" /><ExtendedStat Code=\"PUTT_DIST\" Pos=\"0\" Value=\"1.23\" /><ExtendedStat Code=\"PUTT_DIST\" Pos=\"1\" Value=\"4\'0 &quot;\" /><ExtendedStat Code=\"SAND_ATT\" Value=\"1\" /><ExtendedStat Code=\"SAND_MADE\" Value=\"0\" /><ExtendedStat Code=\"PUTTS_GAINED\" Value=\"-0.816\" /><ExtendedStat Code=\"TEE_GAINED\" Value=\"-2.050\" /><ExtendedStat Code=\"APPROACH_GAINED\" Value=\"1.885\" /><ExtendedStat Code=\"AROUND_GAINED\" Value=\"-0.269\" /><ExtendedStat Code=\"TOTAL_GAINED\" Value=\"-1.250\" /><ExtendedStat Code=\"SCRAMBLE\" Value=\"37.50\" /><ExtendedStat Code=\"PAR\" Value=\"25\" /><ExtendedStat Code=\"BIRDIE\" Value=\"5\" /><ExtendedStat Code=\"BOGEY\" Value=\"5\" /><ExtendedStat Code=\"EAGLES\" Value=\"0\" /><ExtendedStat Code=\"DBL_BOGEY\" Value=\"1\" /><ExtendedStat Code=\"FAIRWAY_ACC\" Value=\"78.57\" /><ExtendedStat Code=\"PUTTS\" Value=\"65\" /></StatsItem><StatsItem Type=\"ST\" Code=\"COURSE\" Pos=\"3\"><ExtendedStat Code=\"DRIVE_DIST\" Pos=\"0\" Value=\"210\" /><ExtendedStat Code=\"DRIVE_DIST\" Pos=\"1\" Value=\"230\" /><ExtendedStat Code=\"DTP\" Pos=\"0\" Value=\"10.51\" /><ExtendedStat Code=\"DTP\" Pos=\"1\" Value=\"34\'6 &quot;\" /><ExtendedStat Code=\"FAIRWAY_HITS\" Value=\"42\" /><ExtendedStat Code=\"FAIRWAY_OPP\" Value=\"56\" /><ExtendedStat Code=\"GREENS_NUM\" Value=\"72\" /><ExtendedStat Code=\"GREENS_REG\" Value=\"49\" /><ExtendedStat Code=\"PUTT_DIST\" Pos=\"0\" Value=\"1.45\" /><ExtendedStat Code=\"PUTT_DIST\" Pos=\"1\" Value=\"4\'9 &quot;\" /><ExtendedStat Code=\"SAND_ATT\" Value=\"2\" /><ExtendedStat Code=\"SAND_MADE\" Value=\"0\" /><ExtendedStat Code=\"PUTTS_GAINED\" Value=\"2.221\" /><ExtendedStat Code=\"TEE_GAINED\" Value=\"-3.178\" /><ExtendedStat Code=\"APPROACH_GAINED\" Value=\"2.564\" /><ExtendedStat Code=\"AROUND_GAINED\" Value=\"0.436\" /><ExtendedStat Code=\"TOTAL_GAINED\" Value=\"2.043\" /><ExtendedStat Code=\"SCRAMBLE\" Value=\"39.13\" /><ExtendedStat Code=\"PAR\" Value=\"40\" /><ExtendedStat Code=\"BIRDIE\" Value=\"16\" /><ExtendedStat Code=\"BOGEY\" Value=\"14\" /><ExtendedStat Code=\"EAGLES\" Value=\"0\" /><ExtendedStat Code=\"DBL_BOGEY\" Value=\"2\" /><ExtendedStat Code=\"FAIRWAY_ACC\" Value=\"75.00\" /><ExtendedStat Code=\"PUTTS\" Value=\"118\" /></StatsItem><StatsItem Type=\"ST\" Code=\"HOLE\" Pos=\"1\"><ExtendedStat Code=\"DRIVE_DIST\" Pos=\"0\" Value=\"861\" /><ExtendedStat Code=\"DRIVE_DIST\" Pos=\"1\" Value=\"942\" /><ExtendedStat Code=\"DTP\" Pos=\"0\" Value=\"47.90\" /><ExtendedStat Code=\"DTP\" Pos=\"1\" Value=\"52yds\" /><ExtendedStat Code=\"PUTT_DIST\" Pos=\"0\" Value=\"3.51\" /><ExtendedStat Code=\"PUTT_DIST\" Pos=\"1\" Value=\"11\'6 &quot;\" /><ExtendedStat Code=\"PUTTS\" Value=\"6\" /><ExtendedStat Code=\"FAIRWAY_HITS\" Value=\"3\" /><ExtendedStat Code=\"FAIRWAY_OPP\" Value=\"4\" /><ExtendedStat Code=\"GREENS\" Value=\"4\" /><ExtendedStat Code=\"GREENS_REG\" Value=\"1\" /></StatsItem><StatsItem Type=\"ST\" Code=\"HOLE\" Pos=\"2\"><ExtendedStat Code=\"DTP\" Pos=\"0\" Value=\"21.36\" /><ExtendedStat Code=\"DTP\" Pos=\"1\" Value=\"70\'1 &quot;\" /><ExtendedStat Code=\"PUTT_DIST\" Pos=\"0\" Value=\"5.77\" /><ExtendedStat Code=\"PUTT_DIST\" Pos=\"1\" Value=\"18\'11 &quot;\" /><ExtendedStat Code=\"PUTTS\" Value=\"6\" /><ExtendedStat Code=\"GREENS\" Value=\"4\" /><ExtendedStat Code=\"GREENS_REG\" Value=\"3\" /></StatsItem><StatsItem Type=\"ST\" Code=\"HOLE\" Pos=\"3\"><ExtendedStat Code=\"DRIVE_DIST\" Pos=\"0\" Value=\"848\" /><ExtendedStat Code=\"DRIVE_DIST\" Pos=\"1\" Value=\"927\" /><ExtendedStat Code=\"DTP\" Pos=\"0\" Value=\"45.29\" /><ExtendedStat Code=\"DTP\" Pos=\"1\" Value=\"50yds\" /><ExtendedStat Code=\"PUTT_DIST\" Pos=\"0\" Value=\"4.72\" /><ExtendedStat Code=\"PUTT_DIST\" Pos=\"1\" Value=\"15\'6 &quot;\" /><ExtendedStat Code=\"PUTTS\" Value=\"6\" /><ExtendedStat Code=\"FAIRWAY_HITS\" Value=\"3\" /><ExtendedStat Code=\"FAIRWAY_OPP\" Value=\"4\" /><ExtendedStat Code=\"GREENS\" Value=\"4\" /><ExtendedStat Code=\"GREENS_REG\" Value=\"2\" /><ExtendedStat Code=\"SAND_ATT\" Value=\"1\" /><ExtendedStat Code=\"SAND_MADE\" Value=\"0\" /></StatsItem><StatsItem Type=\"ST\" Code=\"HOLE\" Pos=\"4\"><ExtendedStat Code=\"DRIVE_DIST\" Pos=\"0\" Value=\"835\" /><ExtendedStat Code=\"DRIVE_DIST\" Pos=\"1\" Value=\"913\" /><ExtendedStat Code=\"DTP\" Pos=\"0\" Value=\"55.63\" /><ExtendedStat Code=\"DTP\" Pos=\"1\" Value=\"61yds\" /><ExtendedStat Code=\"PUTT_DIST\" Pos=\"0\" Value=\"1.04\" /><ExtendedStat Code=\"PUTT_DIST\" Pos=\"1\" Value=\"3\'5 &quot;\" /><ExtendedStat Code=\"PUTTS\" Value=\"8\" /><ExtendedStat Code=\"FAIRWAY_HITS\" Value=\"2\" /><ExtendedStat Code=\"FAIRWAY_OPP\" Value=\"4\" /><ExtendedStat Code=\"GREENS\" Value=\"4\" /><ExtendedStat Code=\"GREENS_REG\" Value=\"3\" /></StatsItem><StatsItem Type=\"ST\" Code=\"HOLE\" Pos=\"5\"><ExtendedStat Code=\"DRIVE_DIST\" Pos=\"0\" Value=\"806\" /><ExtendedStat Code=\"DRIVE_DIST\" Pos=\"1\" Value=\"881\" /><ExtendedStat Code=\"DTP\" Pos=\"0\" Value=\"36.22\" /><ExtendedStat Code=\"DTP\" Pos=\"1\" Value=\"40yds\" /><ExtendedStat Code=\"PUTT_DIST\" Pos=\"0\" Value=\"9.19\" /><ExtendedStat Code=\"PUTT_DIST\" Pos=\"1\" Value=\"30\'2 &quot;\" /><ExtendedStat Code=\"PUTTS\" Value=\"6\" /><ExtendedStat Code=\"FAIRWAY_HITS\" Value=\"3\" /><ExtendedStat Code=\"FAIRWAY_OPP\" Value=\"4\" /><ExtendedStat Code=\"GREENS\" Value=\"4\" /><ExtendedStat Code=\"GREENS_REG\" Value=\"3\" /></StatsItem><StatsItem Type=\"ST\" Code=\"HOLE\" Pos=\"6\"><ExtendedStat Code=\"DRIVE_DIST\" Pos=\"0\" Value=\"847\" /><ExtendedStat Code=\"DRIVE_DIST\" Pos=\"1\" Value=\"926\" /><ExtendedStat Code=\"DTP\" Pos=\"0\" Value=\"33.86\" /><ExtendedStat Code=\"DTP\" Pos=\"1\" Value=\"37yds\" /><ExtendedStat Code=\"PUTT_DIST\" Pos=\"0\" Value=\"6.07\" /><ExtendedStat Code=\"PUTT_DIST\" Pos=\"1\" Value=\"19\'11 &quot;\" /><ExtendedStat Code=\"PUTTS\" Value=\"6\" /><ExtendedStat Code=\"FAIRWAY_HITS\" Value=\"2\" /><ExtendedStat Code=\"FAIRWAY_OPP\" Value=\"4\" /><ExtendedStat Code=\"GREENS\" Value=\"4\" /><ExtendedStat Code=\"GREENS_REG\" Value=\"3\" /></StatsItem><StatsItem Type=\"ST\" Code=\"HOLE\" Pos=\"7\"><ExtendedStat Code=\"DRIVE_DIST\" Pos=\"0\" Value=\"848\" /><ExtendedStat Code=\"DRIVE_DIST\" Pos=\"1\" Value=\"927\" /><ExtendedStat Code=\"DTP\" Pos=\"0\" Value=\"69.22\" /><ExtendedStat Code=\"DTP\" Pos=\"1\" Value=\"76yds\" /><ExtendedStat Code=\"PUTT_DIST\" Pos=\"0\" Value=\"15.65\" /><ExtendedStat Code=\"PUTT_DIST\" Pos=\"1\" Value=\"51\'4 &quot;\" /><ExtendedStat Code=\"PUTTS\" Value=\"3\" /><ExtendedStat Code=\"FAIRWAY_HITS\" Value=\"3\" /><ExtendedStat Code=\"FAIRWAY_OPP\" Value=\"4\" /><ExtendedStat Code=\"GREENS\" Value=\"4\" /><ExtendedStat Code=\"GREENS_REG\" Value=\"1\" /></StatsItem><StatsItem Type=\"ST\" Code=\"HOLE\" Pos=\"8\"><ExtendedStat Code=\"DTP\" Pos=\"0\" Value=\"56.64\" /><ExtendedStat Code=\"DTP\" Pos=\"1\" Value=\"62yds\" /><ExtendedStat Code=\"PUTT_DIST\" Pos=\"0\" Value=\"6.76\" /><ExtendedStat Code=\"PUTT_DIST\" Pos=\"1\" Value=\"22\'2 &quot;\" /><ExtendedStat Code=\"PUTTS\" Value=\"6\" /><ExtendedStat Code=\"GREENS\" Value=\"4\" /><ExtendedStat Code=\"GREENS_REG\" Value=\"1\" /></StatsItem><StatsItem Type=\"ST\" Code=\"HOLE\" Pos=\"9\"><ExtendedStat Code=\"DRIVE_DIST\" Pos=\"0\" Value=\"775\" /><ExtendedStat Code=\"DRIVE_DIST\" Pos=\"1\" Value=\"848\" /><ExtendedStat Code=\"DTP\" Pos=\"0\" Value=\"9.47\" /><ExtendedStat Code=\"DTP\" Pos=\"1\" Value=\"31\'1 &quot;\" /><ExtendedStat Code=\"PUTT_DIST\" Pos=\"0\" Value=\"7.57\" /><ExtendedStat Code=\"PUTT_DIST\" Pos=\"1\" Value=\"24\'10 &quot;\" /><ExtendedStat Code=\"PUTTS\" Value=\"6\" /><ExtendedStat Code=\"FAIRWAY_HITS\" Value=\"4\" /><ExtendedStat Code=\"FAIRWAY_OPP\" Value=\"4\" /><ExtendedStat Code=\"GREENS\" Value=\"4\" /><ExtendedStat Code=\"GREENS_REG\" Value=\"4\" /></StatsItem><StatsItem Type=\"ST\" Code=\"HOLE\" Pos=\"10\"><ExtendedStat Code=\"DRIVE_DIST\" Pos=\"0\" Value=\"802\" /><ExtendedStat Code=\"DRIVE_DIST\" Pos=\"1\" Value=\"877\" /><ExtendedStat Code=\"DTP\" Pos=\"0\" Value=\"35.76\" /><ExtendedStat Code=\"DTP\" Pos=\"1\" Value=\"39yds\" /><ExtendedStat Code=\"PUTT_DIST\" Pos=\"0\" Value=\"1.50\" /><ExtendedStat Code=\"PUTT_DIST\" Pos=\"1\" Value=\"4\'11 &quot;\" /><ExtendedStat Code=\"PUTTS\" Value=\"8\" /><ExtendedStat Code=\"FAIRWAY_HITS\" Value=\"4\" /><ExtendedStat Code=\"FAIRWAY_OPP\" Value=\"4\" /><ExtendedStat Code=\"GREENS\" Value=\"4\" /><ExtendedStat Code=\"GREENS_REG\" Value=\"4\" /></StatsItem><StatsItem Type=\"ST\" Code=\"HOLE\" Pos=\"11\"><ExtendedStat Code=\"DTP\" Pos=\"0\" Value=\"34.77\" /><ExtendedStat Code=\"DTP\" Pos=\"1\" Value=\"38yds\" /><ExtendedStat Code=\"PUTT_DIST\" Pos=\"0\" Value=\"2.06\" /><ExtendedStat Code=\"PUTT_DIST\" Pos=\"1\" Value=\"6\'9 &quot;\" /><ExtendedStat Code=\"PUTTS\" Value=\"8\" /><ExtendedStat Code=\"GREENS\" Value=\"4\" /><ExtendedStat Code=\"GREENS_REG\" Value=\"4\" /></StatsItem><StatsItem Type=\"ST\" Code=\"HOLE\" Pos=\"12\"><ExtendedStat Code=\"DRIVE_DIST\" Pos=\"0\" Value=\"842\" /><ExtendedStat Code=\"DRIVE_DIST\" Pos=\"1\" Value=\"921\" /><ExtendedStat Code=\"DTP\" Pos=\"0\" Value=\"103.10\" /><ExtendedStat Code=\"DTP\" Pos=\"1\" Value=\"113yds\" /><ExtendedStat Code=\"PUTT_DIST\" Pos=\"0\" Value=\"3.25\" /><ExtendedStat Code=\"PUTT_DIST\" Pos=\"1\" Value=\"10\'8 &quot;\" /><ExtendedStat Code=\"PUTTS\" Value=\"8\" /><ExtendedStat Code=\"FAIRWAY_HITS\" Value=\"2\" /><ExtendedStat Code=\"FAIRWAY_OPP\" Value=\"4\" /><ExtendedStat Code=\"GREENS\" Value=\"4\" /><ExtendedStat Code=\"GREENS_REG\" Value=\"1\" /></StatsItem><StatsItem Type=\"ST\" Code=\"HOLE\" Pos=\"13\"><ExtendedStat Code=\"DRIVE_DIST\" Pos=\"0\" Value=\"891\" /><ExtendedStat Code=\"DRIVE_DIST\" Pos=\"1\" Value=\"974\" /><ExtendedStat Code=\"DTP\" Pos=\"0\" Value=\"22.94\" /><ExtendedStat Code=\"DTP\" Pos=\"1\" Value=\"75\'3 &quot;\" /><ExtendedStat Code=\"PUTT_DIST\" Pos=\"0\" Value=\"8.05\" /><ExtendedStat Code=\"PUTT_DIST\" Pos=\"1\" Value=\"26\'5 &quot;\" /><ExtendedStat Code=\"PUTTS\" Value=\"7\" /><ExtendedStat Code=\"FAIRWAY_HITS\" Value=\"3\" /><ExtendedStat Code=\"FAIRWAY_OPP\" Value=\"4\" /><ExtendedStat Code=\"GREENS\" Value=\"4\" /><ExtendedStat Code=\"GREENS_REG\" Value=\"4\" /></StatsItem><StatsItem Type=\"ST\" Code=\"HOLE\" Pos=\"14\"><ExtendedStat Code=\"DRIVE_DIST\" Pos=\"0\" Value=\"860\" /><ExtendedStat Code=\"DRIVE_DIST\" Pos=\"1\" Value=\"940\" /><ExtendedStat Code=\"DTP\" Pos=\"0\" Value=\"21.16\" /><ExtendedStat Code=\"DTP\" Pos=\"1\" Value=\"69\'5 &quot;\" /><ExtendedStat Code=\"PUTT_DIST\" Pos=\"0\" Value=\"4.50\" /><ExtendedStat Code=\"PUTT_DIST\" Pos=\"1\" Value=\"14\'9 &quot;\" /><ExtendedStat Code=\"PUTTS\" Value=\"7\" /><ExtendedStat Code=\"FAIRWAY_HITS\" Value=\"4\" /><ExtendedStat Code=\"FAIRWAY_OPP\" Value=\"4\" /><ExtendedStat Code=\"GREENS\" Value=\"4\" /><ExtendedStat Code=\"GREENS_REG\" Value=\"4\" /></StatsItem><StatsItem Type=\"ST\" Code=\"HOLE\" Pos=\"15\"><ExtendedStat Code=\"DRIVE_DIST\" Pos=\"0\" Value=\"838\" /><ExtendedStat Code=\"DRIVE_DIST\" Pos=\"1\" Value=\"916\" /><ExtendedStat Code=\"DTP\" Pos=\"0\" Value=\"34.24\" /><ExtendedStat Code=\"DTP\" Pos=\"1\" Value=\"37yds\" /><ExtendedStat Code=\"PUTT_DIST\" Pos=\"0\" Value=\"3.99\" /><ExtendedStat Code=\"PUTT_DIST\" Pos=\"1\" Value=\"13\'1 &quot;\" /><ExtendedStat Code=\"PUTTS\" Value=\"7\" /><ExtendedStat Code=\"FAIRWAY_HITS\" Value=\"4\" /><ExtendedStat Code=\"FAIRWAY_OPP\" Value=\"4\" /><ExtendedStat Code=\"GREENS\" Value=\"4\" /><ExtendedStat Code=\"GREENS_REG\" Value=\"3\" /></StatsItem><StatsItem Type=\"ST\" Code=\"HOLE\" Pos=\"16\"><ExtendedStat Code=\"DTP\" Pos=\"0\" Value=\"43.26\" /><ExtendedStat Code=\"DTP\" Pos=\"1\" Value=\"47yds\" /><ExtendedStat Code=\"PUTT_DIST\" Pos=\"0\" Value=\"9.83\" /><ExtendedStat Code=\"PUTT_DIST\" Pos=\"1\" Value=\"32\'3 &quot;\" /><ExtendedStat Code=\"PUTTS\" Value=\"6\" /><ExtendedStat Code=\"GREENS\" Value=\"4\" /><ExtendedStat Code=\"GREENS_REG\" Value=\"3\" /></StatsItem><StatsItem Type=\"ST\" Code=\"HOLE\" Pos=\"17\"><ExtendedStat Code=\"DRIVE_DIST\" Pos=\"0\" Value=\"858\" /><ExtendedStat Code=\"DRIVE_DIST\" Pos=\"1\" Value=\"938\" /><ExtendedStat Code=\"DTP\" Pos=\"0\" Value=\"46.43\" /><ExtendedStat Code=\"DTP\" Pos=\"1\" Value=\"51yds\" /><ExtendedStat Code=\"PUTT_DIST\" Pos=\"0\" Value=\"8.23\" /><ExtendedStat Code=\"PUTT_DIST\" Pos=\"1\" Value=\"27\'0 &quot;\" /><ExtendedStat Code=\"PUTTS\" Value=\"6\" /><ExtendedStat Code=\"FAIRWAY_HITS\" Value=\"3\" /><ExtendedStat Code=\"FAIRWAY_OPP\" Value=\"4\" /><ExtendedStat Code=\"GREENS\" Value=\"4\" /><ExtendedStat Code=\"GREENS_REG\" Value=\"2\" /></StatsItem><StatsItem Type=\"ST\" Code=\"HOLE\" Pos=\"18\"><ExtendedStat Code=\"DRIVE_DIST\" Pos=\"0\" Value=\"877\" /><ExtendedStat Code=\"DRIVE_DIST\" Pos=\"1\" Value=\"959\" /><ExtendedStat Code=\"DTP\" Pos=\"0\" Value=\"29.29\" /><ExtendedStat Code=\"DTP\" Pos=\"1\" Value=\"96\'1 &quot;\" /><ExtendedStat Code=\"PUTT_DIST\" Pos=\"0\" Value=\"2.77\" /><ExtendedStat Code=\"PUTT_DIST\" Pos=\"1\" Value=\"9\'1 &quot;\" /><ExtendedStat Code=\"PUTTS\" Value=\"8\" /><ExtendedStat Code=\"FAIRWAY_HITS\" Value=\"2\" /><ExtendedStat Code=\"FAIRWAY_OPP\" Value=\"4\" /><ExtendedStat Code=\"GREENS\" Value=\"4\" /><ExtendedStat Code=\"GREENS_REG\" Value=\"3\" /><ExtendedStat Code=\"SAND_ATT\" Value=\"1\" /><ExtendedStat Code=\"SAND_MADE\" Value=\"0\" /></StatsItem></StatsItems></Athlete></Composition></Competitor></Stats></Competition><!--d37cf694b60d4eed9c68bda083765ad7--></OdfBody>\n'
        const json = JSON.parse(xml2json(xml, { compact: true, spaces: 2 }));
        xmlData.value = json;
    }
})


const parseODFTime = (timeString) => {
  
  const hours = timeString?.substring(0, 2);
  const minutes = timeString?.substring(2, 4);
  const seconds = timeString?.substring(4, 6);
  const milliseconds = timeString?.substring(6, 9);

  const isoString = `${hours}:${minutes}:${seconds}.${milliseconds}`;

  return isoString;
}

const fileDetails = computed(()=>{
    if(xmlData.value)
    {
        return xmlData.value?.OdfBody?._attributes
    }
})

const eventDetails = computed(()=>{
    if(xmlData.value.OdfBody)
    {
        return xmlData.value?.OdfBody?.Competition?.ExtendedInfos?.SportDescription
    }
})

const LocationDetails = computed(()=>{
    if(xmlData.value.OdfBody)
    {
        return xmlData.value?.OdfBody?.Competition?.ExtendedInfos?.VenueDescription
    }
})

const playerDetails = computed(() => {
    if(xmlData.value.OdfBody)
    {
        return xmlData.value.OdfBody.Competition.Stats.Competitor.Composition.Athlete.Description
    }
})

const statisticalData = computed(()=>{
    if(xmlData.value.OdfBody)
    {
        const items = xmlData?.value.OdfBody?.Competition?.Stats?.Competitor?.Composition?.Athlete.StatsItems?.StatsItem


        var response = {}
        var careerStats = []
        var holeStats = []
        
        items.forEach(el => {

            if(el._attributes.Code === 'COURSE')
            {
                var data = {}
                let stat = el.ExtendedStat;
                data.extendedStat = stat
                careerStats.push(data);
            }
            else
            {
                var data = {}
                let stat = el.ExtendedStat;
                data.extendedStat = stat
                holeStats.push(data);
            }
        });
        response.career = careerStats
        response.holeStats = holeStats      
        console.log(response);
        return response
    }
})

watch(statisticalData,(newValue)=>{

    MainCareerStats.value = renderCareerStats(newValue.career)
    
})



const renderCareerStats = (data) =>
{
    var response = [];

    data.forEach(stat => {
        console.log(stat.extendedStat);
        const extendedStat = stat.extendedStat;
        var result = []
        var res = {}
        extendedStat.forEach(el => {
            // console.log(el._attributes.Code);
            if(el._attributes.Code === 'TOTAL_GAINED')
            {
                res.totalGained = el._attributes.Value
                console.log(el._attributes.Code);
                console.log(el._attributes.Value);
                console.log(res);
                
            }
            if(el._attributes.Code === 'BIRDIE')
            {
                res.birdie = el._attributes.Value
                 console.log(el._attributes.Code);
                 console.log(el._attributes.Value);
                console.log(res);
            }
            if(el._attributes.Code === 'BOGEY')
            {
                res.bogey = el._attributes.Value
                 console.log(el._attributes.Code);
                 console.log(el._attributes.Value);
                console.log(res);
            }
            if(el._attributes.Code === 'DBL_BOGEY')
            {
                res.double_bogey = el._attributes.Value
                 console.log(el._attributes.Code);
                 console.log(el._attributes.Value);
                console.log(res);
            }
            if(el._attributes.Code === 'EAGLES')
            {
                res.eagles = el._attributes.Value
                 console.log(el._attributes.Code);
                 console.log(el._attributes.Value);
                console.log(res);
            }
            if(el._attributes.Code === 'PAR')
            {
                res.par = el._attributes.Value
                 console.log(el._attributes.Code);
                 console.log(el._attributes.Value);
                console.log(res);
            }
            if(el._attributes.Code === 'PUTTS')
            {
                res.putts = el._attributes.Value
                 console.log(el._attributes.Code);
                 console.log(el._attributes.Value);
                console.log(res);
            }

            
        });
        result.push(res)
        response.push(result)
        
    });
    return response;
}


</script>

<style  scoped>

.inside-height{
    height: 30rem;
}

.inside-first-height
{
    height: 10rem;
}
.inside-second-height
{
    height: 17rem;
}

.tile p{
    font-size: small;
}

.profile-img
{
    height: 12rem;
}

</style>